<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Consultant;

class ConsultantSeeder extends Seeder
{
    public function run(): void
    {
        // Líder
        Consultant::create([
            'name' => 'Hugo Gaito',
            'role' => 'Broker Empreendedor',
            'photo' => 'Hugo.png',
            'order' => 1
        ]);

        $team = [
            ['name' => 'Carlos Pinto', 'role' => 'Consultor Imobiliário', 'photo' => 'carlos.png', 'phone' => '+351917204561'],
            ['name' => 'Mariana Faria', 'role' => 'Consultora Imobiliária', 'photo' => 'mariana.png', 'phone' => '+351961222024'],
            ['name' => 'Leonor Tudela', 'role' => 'Consultora Imobiliária', 'photo' => 'leonor.png', 'phone' => '+351962501264', 'bio' => 'Sou licenciada em Gestão de Marketing...'],
            ['name' => 'Anabela Inácio', 'role' => 'Consultora Imobiliária', 'photo' => 'anabela.png', 'phone' => '+351964872394', 'bio' => 'O meu nome é Anabela Inácio...'],
            ['name' => 'Sandra Guedes', 'role' => 'Consultora Imobiliária', 'photo' => 'sandra.png', 'phone' => '+351934188303', 'bio' => 'O meu nome é Sandra Guedes...'],
            ['name' => 'Sofia Leitão', 'role' => 'Consultora Imobiliária', 'photo' => 'sofia.png', 'phone' => '+351917715544', 'email' => 'sofialeitao@remax.pt', 'bio' => 'O meu nome é Sofia...'],
            ['name' => 'Inês Lobo', 'role' => 'Consultora Imobiliária', 'photo' => 'ines.png', 'phone' => '+351913163655', 'email' => 'inesamaral@remax.pt', 'bio' => 'O meu nome é Inês Lobo...'],
            ['name' => 'Matilde Pereira', 'role' => 'Consultora Imobiliária', 'photo' => 'matilde.png', 'phone' => '+351967823022', 'email' => 'matildepereira@remax.pt', 'bio' => 'O meu nome é Matilde Pereira...'],
            ['name' => 'Marília Miranda', 'role' => 'Consultora Imobiliária', 'photo' => 'marilia.png', 'phone' => '+351910970808', 'email' => 'mmiranda@remax.pt'],
            ['name' => 'Margarida Lopes', 'role' => 'Consultora Imobiliária', 'photo' => 'margarida.png', 'phone' => '+351967635312', 'bio' => 'Desde 2008 a trabalhar no Ramo Imobiliário...'],
            ['name' => 'Marina Machado', 'role' => 'Assistente', 'photo' => 'marina.png', 'phone' => '+351916123562', 'bio' => 'O seu percurso profissional...'],
            ['name' => 'Pedro Santos', 'role' => 'Consultor Imobiliário', 'photo' => 'pedro.png', 'phone' => '+351917827196', 'bio' => 'Desde sempre ligado à área comercial...'],
            ['name' => 'Hugo Carvalho', 'role' => 'Consultor Imobiliário', 'photo' => 'hugo2.png', 'phone' => '+351961407430', 'bio' => 'Possuo um forte interesse pela área imobiliária...'],
            ['name' => 'David Simões', 'role' => 'Consultor Imobiliário', 'photo' => 'david.png', 'phone' => '+351961044596', 'bio' => 'Com vasta experiência na área comercial...'],
        ];

        foreach ($team as $index => $member) {
            Consultant::create(array_merge($member, ['order' => $index + 2]));
        }
    }
}