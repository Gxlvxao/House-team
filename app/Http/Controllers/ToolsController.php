<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CapitalGainsCalculatorService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class ToolsController extends Controller
{
    protected $calculator;

    public function __construct(CapitalGainsCalculatorService $calculator)
    {
        $this->calculator = $calculator;
    }

    public function showGainsSimulator() { return view('tools.gains'); }
    public function showCreditSimulator() { return view('tools.credit'); }
    public function showImtSimulator() { return view('tools.imt'); }

    public function calculateGains(Request $request)
    {
        $validated = $request->validate([
            'acquisition_value' => 'required|numeric|min:0',
            'acquisition_year' => 'required|integer|min:1900|max:2025',
            'acquisition_month' => 'required|string',
            'sale_value' => 'required|numeric|min:0',
            'sale_year' => 'required|integer|min:1900|max:2025',
            'sale_month' => 'required|string',
            'has_expenses' => 'required|string|in:Sim,Não',
            'expenses_works' => 'nullable|numeric|min:0',
            'expenses_imt' => 'nullable|numeric|min:0',
            'expenses_commission' => 'nullable|numeric|min:0',
            'expenses_other' => 'nullable|numeric|min:0',
            'sold_to_state' => 'required|string|in:Sim,Não',
            
            'hpp_status' => 'required_unless:sold_to_state,Sim|nullable|string',
            'retired_status' => 'required_unless:sold_to_state,Sim|nullable|string|in:Sim,Não',
            'self_built' => 'required_unless:sold_to_state,Sim|nullable|string|in:Sim,Não',
            'reinvest_intention' => 'required_unless:sold_to_state,Sim|nullable|string|in:Sim,Não',
            'reinvestment_amount' => 'nullable|numeric|min:0',
            'amortize_credit' => 'required_unless:sold_to_state,Sim|nullable|string|in:Sim,Não',
            'amortization_amount' => 'nullable|numeric|min:0',
            'joint_tax_return' => 'required_unless:sold_to_state,Sim|nullable|string|in:Sim,Não',
            'annual_income' => 'required_unless:sold_to_state,Sim|nullable|numeric|min:0',
            'public_support' => 'required_unless:sold_to_state,Sim|nullable|string|in:Sim,Não',
            'public_support_year' => 'nullable|integer',
            'public_support_month' => 'nullable|string',
            
            'lead_name' => 'required|string|max:255',
            'lead_email' => 'required|email|max:255'
        ]);

        $totalExpenses = 0.0;
        if ($validated['has_expenses'] === 'Sim') {
            $totalExpenses = 
                (float) ($validated['expenses_works'] ?? 0) + 
                (float) ($validated['expenses_imt'] ?? 0) + 
                (float) ($validated['expenses_commission'] ?? 0) + 
                (float) ($validated['expenses_other'] ?? 0);
        }
        $validated['expenses_total'] = $totalExpenses;

        $results = $this->calculator->calculate($validated);

        if ($request->filled('lead_email')) {
            $this->sendEmailWithPdf(
                $validated['lead_email'],
                $validated['lead_name'],
                'Simulação de Mais-Valias',
                'pdfs.simulation',
                ['data' => $validated, 'results' => $results]
            );
        }

        return response()->json($results);
    }

    public function sendCreditSimulation(Request $request)
    {
        $data = $request->validate([
            'propertyValue' => 'required', 'loanAmount' => 'required', 'years' => 'required',
            'tan' => 'required', 'monthlyPayment' => 'required', 'mtic' => 'required',
            'lead_name' => 'required|string', 'lead_email' => 'required|email'
        ]);

        $this->sendEmailWithPdf(
            $data['lead_email'],
            $data['lead_name'],
            'Simulação Crédito Habitação',
            'pdfs.simple-report', 
            ['title' => 'Relatório Crédito Habitação', 'data' => $data]
        );

        return response()->json(['success' => true]);
    }

    public function sendImtSimulation(Request $request)
    {
        $data = $request->validate([
            'propertyValue' => 'required', 'location' => 'required', 'purpose' => 'required',
            'finalIMT' => 'required', 'finalStamp' => 'required', 'totalPayable' => 'required',
            'lead_name' => 'required|string', 'lead_email' => 'required|email'
        ]);

        $this->sendEmailWithPdf(
            $data['lead_email'],
            $data['lead_name'],
            'Simulação IMT e Selo',
            'pdfs.simple-report',
            ['title' => 'Relatório IMT e Imposto de Selo', 'data' => $data]
        );

        return response()->json(['success' => true]);
    }

    public function sendContact(Request $request)
    {
        $data = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|max:255',
            'phone'   => 'nullable|string|max:20',
            'subject' => 'nullable|string|max:255',
            'message' => 'nullable|string', 
            'property_type' => 'nullable|string',
            'year'          => 'nullable|integer',
            'area'          => 'nullable|numeric',
            'bedrooms'      => 'nullable|integer',
            'bathrooms'     => 'nullable|integer',
            'garages'       => 'nullable|integer',
            'parking_type'  => 'nullable|string',
            'features'      => 'nullable|array',
            'condition'     => 'nullable|string',
            'address'       => 'nullable|string',
            'is_owner'      => 'nullable|string',
            'estimated_value' => 'nullable|numeric',
        ]);

        if (empty($data['subject'])) {
            $data['subject'] = 'Novo Contacto Geral';
        }

        try {
            $adminEmail = 'admin@houseteam.pt'; 

            Mail::send('emails.contact-lead', ['data' => $data], function ($message) use ($adminEmail, $data) {
                $message->to($adminEmail)
                        ->subject('[House Team] ' . $data['subject']);
            });

            return back()->with('success', 'O seu pedido foi enviado com sucesso! Entraremos em contacto brevemente.');

        } catch (\Exception $e) {
            Log::error('Erro ao enviar contacto: ' . $e->getMessage());
            return back()->with('error', 'Ocorreu um erro ao enviar a mensagem. Por favor tente novamente.');
        }
    }

    private function sendEmailWithPdf($email, $name, $type, $pdfView, $viewData)
    {
        try {
            $viewData['date'] = date('d/m/Y');
            
            $pdf = Pdf::loadView($pdfView, $viewData);

            Mail::send('emails.simulation-lead', ['name' => $name, 'simulationType' => $type], function ($message) use ($email, $type, $pdf) {
                $message->to($email)
                    ->subject($type . ' - Resultado Detalhado')
                    ->attachData($pdf->output(), 'simulacao.pdf');
            });

        } catch (\Exception $e) {
            Log::error('Erro ao enviar email de simulação: ' . $e->getMessage());
        }
    }
}