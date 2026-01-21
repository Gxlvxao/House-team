<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Services\IdealistaExportService;

class RunIdealistaCertification extends Command
{
    protected $signature = 'idealista:full-certification';
    protected $description = 'Certificação Idealista Completa (Com ID Logger e Fixes de E-mail)';

    protected $service;
    protected $baseUrl;
    protected $headers;
    protected $contactId;
    protected $propertyId;

    public function handle(IdealistaExportService $service)
    {
        $this->service = $service;
        $this->baseUrl = config('services.idealista.base_url');
        $this->headers = $service->getHeaders('write');

        $this->info("🚀 INICIANDO CERTIFICAÇÃO FINAL (COM LOG DE IDs)");
        $this->info("URL Alvo: " . $this->baseUrl);
        $this->newLine();

        $this->runContacts();
        $this->runAuthTests();
        $this->runScopeAndVisibility();
        $this->runPropertyTypesHappyPath();
        $this->runPropertyTypesComplex(); 
        $this->runValidationErrors();
        $this->runLifecycle(); // <--- AQUI ESTÃO OS FIXES DO E-MAIL
        $this->runImages();

        $this->info("✅ PROTOCOLO CONCLUÍDO. ATUALIZE SUA PLANILHA COM OS IDs ACIMA!");
    }

    private function runContacts()
    {
        $this->info("--- CONTACTS ---");

        // Contact01: Create
        $c1 = ['name' => 'Certification Admin', 'email' => 'admin@house.pt', 'primaryPhoneNumber' => '912345678'];
        $this->exec('Contact01', 'POST', '/v1/contacts', $c1, 201, function($r) {
            $this->contactId = $r['contactId'] ?? null;
        });

        // Fallback
        if (!$this->contactId) $this->contactId = 33900828; 

        // Errors
        $c2 = ['name' => 'Admin', 'primaryPhoneNumber' => '912345678'];
        $this->exec('Contact02', 'POST', '/v1/contacts', $c2, 400);

        $c3 = ['name' => 'Admin', 'email' => 'invalid_email', 'primaryPhoneNumber' => '912345678'];
        $this->exec('Contact03', 'POST', '/v1/contacts', $c3, 400);

        // Update
        $c4 = $c1; $c4['name'] = 'Certification Admin Updated';
        $this->exec('Contact04', 'PUT', "/v1/contacts/{$this->contactId}", $c4, 200);

        // List/Get
        $this->exec('Contact05', 'GET', "/v1/contacts/{$this->contactId}", [], 200);
        $this->exec('Contact06', 'GET', "/v1/contacts?numPage=1&maxItems=10", [], 200);
    }

    private function runAuthTests()
    {
        $this->info("--- AUTHENTICATION ---");
        $badH = $this->headers; $badH['Authorization'] = 'Bearer invalid_token';
        $this->exec('Property01', 'POST', '/v1/properties', [], 401, null, $badH);

        $badH2 = ['feedKey' => 'invalid', 'Content-Type' => 'application/json'];
        $this->exec('Property02', 'POST', '/v1/properties', [], 401, null, $badH2);
    }

    private function runScopeAndVisibility()
    {
        $this->info("--- SCOPE & VISIBILITY ---");
        
        // Property03 (Sale) - ESSE É O ID PRINCIPAL DO TESTE!
        $p3 = $this->base('flat', 'sale', 'SALE');
        $this->exec('Property03', 'POST', '/v1/properties', $p3, 201, function($r) {
            $this->propertyId = $r['propertyId'] ?? null;
        });

        $p4 = $this->base('flat', 'rent', 'RENT'); $p4['operation'] = ['type' => 'rent', 'price' => 1200];
        $this->exec('Property04', 'POST', '/v1/properties', $p4, 201);

        $p5 = $this->base('flat', 'sale', 'SCOPE-ID'); $p5['scope'] = 'idealista';
        $this->exec('Property05', 'POST', '/v1/properties', $p5, 201);

        $p6 = $this->base('flat', 'sale', 'SCOPE-MICRO'); $p6['scope'] = 'microsite';
        $this->exec('Property06', 'POST', '/v1/properties', $p6, 201);

        $p7 = $this->base('flat', 'sale', 'VIS-FULL'); $p7['address']['visibility'] = 'full';
        $this->exec('Property07', 'POST', '/v1/properties', $p7, 201);

        $p8 = $this->base('flat', 'sale', 'VIS-STREET'); $p8['address']['visibility'] = 'street'; 
        $this->exec('Property08', 'POST', '/v1/properties', $p8, 201);

        $p9 = $this->base('flat', 'sale', 'VIS-HIDDEN'); $p9['address']['visibility'] = 'hidden';
        $this->exec('Property09', 'POST', '/v1/properties', $p9, 201);
    }

    private function runPropertyTypesHappyPath()
    {
        $this->info("--- SIMPLE TYPES ---");
        $this->exec('Flat01', 'POST', '/v1/properties', $this->base('flat', 'sale', 'FLAT'), 201);
        
        $house = $this->base('house', 'sale', 'HOUSE');
        $house['features'] = ['type' => 'independent', 'rooms' => 4, 'bathroomNumber' => 3, 'areaConstructed' => 200, 'areaPlot' => 500, 'conservation' => 'good', 'energyCertificateRating' => 'A'];
        $this->exec('House01', 'POST', '/v1/properties', $house, 201);

        $gar = $this->base('garage', 'sale', 'GARAGE');
        $gar['features'] = ['areaConstructed' => 15, 'garageCapacity' => 'car_sedan'];
        $this->exec('Garage01', 'POST', '/v1/properties', $gar, 201);

        $off = $this->base('office', 'sale', 'OFFICE');
        $off['features'] = ['areaConstructed' => 80, 'conservation' => 'good', 'energyCertificateRating' => 'B', 'officeBuilding' => true, 'liftNumber' => 1, 'roomsSplitted' => 'withWalls', 'conditionedAirType' => 'cold', 'parkingSpacesNumber' => 0];
        $this->exec('Office01', 'POST', '/v1/properties', $off, 201);

        $stor = $this->base('storage', 'sale', 'STORAGE');
        $stor['features'] = ['areaConstructed' => 10];
        $this->exec('StorageRoom01', 'POST', '/v1/properties', $stor, 201);
    }

    private function runPropertyTypesComplex()
    {
        $this->info("--- COMPLEX TYPES ---");
        $ch = $this->base('countryhouse', 'sale', 'CHOUSE');
        $ch['features'] = ['type' => 'countryhouse', 'rooms' => 3, 'bathroomNumber' => 1, 'areaConstructed' => 150, 'areaPlot' => 2000, 'conservation' => 'toRestore', 'energyCertificateRating' => 'exempt'];
        $this->exec('CountryHouse01', 'POST', '/v1/properties', $ch, 201);

        $com1 = $this->base('commercial', 'sale', 'COMM1');
        $com1['features'] = ['type' => 'retail', 'commercialMainActivity' => 'clothing_store', 'areaConstructed' => 100, 'conservation' => 'good', 'energyCertificateRating' => 'C', 'location' => 'on_the_street', 'rooms' => 1];
        $this->exec('Commercial01', 'POST', '/v1/properties', $com1, 201);

        $com2 = $this->base('commercial', 'rent', 'COMM2');
        $com2['features'] = ['type' => 'retail', 'commercialMainActivity' => 'restaurant', 'areaConstructed' => 100, 'conservation' => 'good', 'energyCertificateRating' => 'C', 'location' => 'on_the_street', 'rooms' => 1, 'isATransfer' => true, 'transferPrice' => 50000];
        $this->exec('Commercial02', 'POST', '/v1/properties', $com2, 201);

        $l1 = $this->base('land', 'sale', 'LAND-URB');
        $l1['features'] = ['areaPlot' => 1000, 'type' => 'land_urban', 'roadAccess' => true, 'accessType' => 'urban', 'classification' => 'residential'];
        $this->exec('Land01', 'POST', '/v1/properties', $l1, 201);

        $l2 = $this->base('land', 'sale', 'LAND-BLD');
        $l2['features'] = ['areaPlot' => 5000, 'type' => 'land_countrybuildable', 'roadAccess' => true, 'accessType' => 'road', 'classification' => 'residential'];
        $this->exec('Land02', 'POST', '/v1/properties', $l2, 201);

        $l3 = $this->base('land', 'sale', 'LAND-NON');
        $l3['features'] = ['areaPlot' => 10000, 'type' => 'countrynonbuildable', 'roadAccess' => false];
        $this->exec('Land03', 'POST', '/v1/properties', $l3, 201);

        $bd = $this->base('building', 'sale', 'BUILD');
        $bd['features'] = ['areaConstructed' => 1000, 'energyCertificateRating' => 'exempt', 'floorsBuilding' => 5, 'parkingSpacesNumber' => 10, 'conservation' => 'good', 'classification' => 'residential'];
        $this->exec('Building01', 'POST', '/v1/properties', $bd, 201);

        $rm = $this->base('room', 'rent', 'ROOM');
        $rm['features'] = ['type' => 'shared_flat', 'rooms' => 3, 'bathroomNumber' => 1, 'areaConstructed' => 20, 'liftAvailable' => true, 'smokingAllowed' => false, 'couplesAllowed' => true, 'availableFrom' => date('Y-m', strtotime('+1 month')), 'bedType' => 'single', 'minimalStay' => 1, 'occupiedNow' => false, 'petsAllowed' => false, 'tenantNumber' => 2];
        $this->exec('Room01', 'POST', '/v1/properties', $rm, 201);
    }

    private function runValidationErrors()
    {
        $this->info("--- VALIDATION ERRORS ---");
        $f2 = $this->base('flat', 'sale', 'ERR-AREA'); $f2['features']['areaConstructed'] = 5;
        $this->exec('Flat02', 'POST', '/v1/properties', $f2, 400);

        $f3 = $this->base('flat', 'sale', 'ERR-USE'); $f3['features']['areaConstructed'] = 50; $f3['features']['areaUsable'] = 60;
        $this->exec('Flat03', 'POST', '/v1/properties', $f3, 400);

        $f4 = $this->base('flat', 'sale', 'ERR-BATH'); $f4['features']['bathroomNumber'] = 0;
        $this->exec('Flat04', 'POST', '/v1/properties', $f4, 400);

        $f5 = $this->base('flat', 'sale', 'ERR-PARK'); $f5['features']['parkingAvailable'] = false; $f5['features']['parkingIncludedInPrice'] = true;
        $this->exec('Flat05', 'POST', '/v1/properties', $f5, 400);

        $r2 = $this->base('room', 'sale', 'ERR-OP'); $r2['type'] = 'bedroom';
        $this->exec('Room02', 'POST', '/v1/properties', $r2, 400);
    }

    private function runLifecycle()
    {
        $this->info("--- LIFECYCLE (FIXED AS PER EMAIL: PUT STATE) ---");
        // Usamos o ID do Property03
        $this->exec('Property10', 'GET', "/v1/properties/{$this->propertyId}", [], 200);
        $this->exec('Property11', 'GET', "/v1/properties/99999999", [], 404);
        $this->exec('Property12', 'GET', "/v1/properties?numPage=1&maxItems=10", [], 200);

        $u13 = $this->base('flat', 'sale', 'UPDATED'); $u13['operation']['price'] = 290000;
        $this->exec('Property13', 'PUT', "/v1/properties/{$this->propertyId}", $u13, 200);

        $u14 = $u13; $u14['type'] = 'house';
        $this->exec('Property14', 'PUT', "/v1/properties/{$this->propertyId}", $u14, 400);

        $this->exec('Property15', 'PUT', "/v1/properties/99999999", $u13, 404);

        // --- CORREÇÃO DO E-MAIL: PUT state=inactive (Não DELETE) ---
        $deactivate = ['state' => 'inactive'];
        $this->exec('Property16', 'PUT', "/v1/properties/{$this->propertyId}", $deactivate, 200);
        $this->exec('Property17', 'PUT', "/v1/properties/99999999", $deactivate, 404);

        // --- CORREÇÃO DO E-MAIL: PUT state=active (Não POST) ---
        $reactivate = ['state' => 'active'];
        $this->exec('Property18', 'PUT', "/v1/properties/{$this->propertyId}", $reactivate, 200);
        $this->exec('Property19', 'PUT', "/v1/properties/99999999", $reactivate, 404);
    }

    private function runImages()
    {
        $this->info("--- IMAGES (35s Delay) ---");
        $this->sleepLog();
        $img1 = 'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2';
        $img2 = 'https://images.unsplash.com/photo-1560185007-cde436f6a4d0';

        $p1 = ['images' => [['url' => $img1, 'label' => 'facade'], ['url' => $img2, 'label' => 'living']]];
        $this->exec('Image01', 'PUT', "/v1/properties/{$this->propertyId}/images", $p1, 202);
        $this->sleepLog();

        $this->exec('Image02', 'GET', "/v1/properties/{$this->propertyId}/images", [], 200);
        $this->sleepLog();

        $p3 = ['images' => [['url' => $img2, 'label' => 'living'], ['url' => $img1, 'label' => 'facade']]];
        $this->exec('Image03', 'PUT', "/v1/properties/{$this->propertyId}/images", $p3, 202);
        $this->sleepLog();

        $p4 = ['images' => [['url' => $img2, 'label' => 'kitchen'], ['url' => $img1, 'label' => 'facade']]];
        $this->exec('Image04', 'PUT', "/v1/properties/{$this->propertyId}/images", $p4, 202);
        $this->sleepLog();

        $p5 = ['images' => [['url' => $img1, 'label' => 'facade']]];
        $this->exec('Image05', 'PUT', "/v1/properties/{$this->propertyId}/images", $p5, 202);
        $this->sleepLog();

        $this->exec('Image06', 'DELETE', "/v1/properties/{$this->propertyId}/images", [], 200);
    }

    private function base($type, $op, $ref)
    {
        return [
            'type' => $type,
            'reference' => $ref . '-' . time() . '-' . rand(100,999),
            'address' => [
                'visibility' => 'hidden', 'precision' => 'exact', 'country' => 'Portugal',
                'streetName' => 'Av da Liberdade', 'streetNumber' => '100', 'postalCode' => '1250-145', 'town' => 'Lisboa'
            ],
            'operation' => ['type' => $op, 'price' => 250000],
            'features' => ['rooms' => 2, 'bathroomNumber' => 2, 'areaConstructed' => 90, 'conservation' => 'good', 'energyCertificateRating' => 'B', 'liftAvailable' => true],
            'descriptions' => [['language' => 'pt', 'text' => 'Full Certification Run']],
            'contactId' => $this->contactId
        ];
    }

    private function exec($id, $method, $uri, $data, $expect, $cb = null, $customHeaders = null)
    {
        $h = $customHeaders ?? $this->headers;
        $url = str_starts_with($uri, 'http') ? $uri : $this->baseUrl . $uri;

        $this->line("Running: $id...");
        
        try {
            if ($method === 'GET') $r = Http::withHeaders($h)->get($url, $data);
            elseif ($method === 'DELETE') $r = Http::withHeaders($h)->delete($url, $data);
            elseif ($method === 'PUT') $r = Http::withHeaders($h)->put($url, $data);
            else $r = Http::withHeaders($h)->post($url, $data);

            $status = $r->status();
            $success = ($status == $expect) || ($expect >= 200 && $expect < 300 && $status >= 200 && $status < 300);
            $tag = $success ? "✅ PASS" : "❌ FAIL";
            
            // --- ID SNIFFER (O SEGREDO!) ---
            if ($r->successful()) {
                $json = $r->json();
                if (isset($json['contactId'])) {
                    $this->warn(" >>> 🆔 CONTACT ID: " . $json['contactId']);
                }
                if (isset($json['propertyId'])) {
                    $this->warn(" >>> 🆔 PROPERTY ID: " . $json['propertyId']);
                }
            }
            // -----------------------------
            
            $this->info("$tag | $id | Status: $status");

            if ($cb && $r->successful()) $cb($r->json());

        } catch (\Exception $e) {
            $this->error("ERR: " . $e->getMessage());
        }
    }

    private function sleepLog() {
        $this->warn("⏳ Aguardando 35s...");
        sleep(35);
    }
}