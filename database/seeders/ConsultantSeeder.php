<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Consultant;

class ConsultantSeeder extends Seeder
{
    public function run(): void
    {
        // 1. O Líder (Broker)
        Consultant::updateOrCreate(
            ['name' => 'Hugo Gaito'], // Chave única para verificar existência
            [
                'role' => 'Broker Empreendedor',
                'photo' => 'Hugo.png',
                'phone' => null,
                'email' => null,
                'bio' => null,
                'order' => 1,
                'is_active' => true,
            ]
        );

        // 2. A Equipa
        $team = [
            [
                'name' => 'Carlos Pinto',
                'role' => 'Consultor Imobiliário',
                'photo' => 'carlos.png',
                'phone' => '+351917204561',
                'email' => null,
                'bio' => ''
            ],
            [
                'name' => 'Mariana Faria',
                'role' => 'Consultora Imobiliária',
                'photo' => 'mariana.png',
                'phone' => '+351961222024',
                'email' => null,
                'bio' => ''
            ],
            [
                'name' => 'Leonor Tudela',
                'role' => 'Consultora Imobiliária',
                'photo' => 'leonor.png',
                'phone' => '+351962501264',
                'email' => null,
                'bio' => 'Sou licenciada em Gestão de Marketing, com 25 anos de experiência profissional, sempre focada no cuidado ao outro. Sou uma pessoa alegre, dedicada, humilde, responsável e cumpridora, que gosta de novos desafios, que se preocupa com o outro e, sobretudo, que gosta de se relacionar…'
            ],
            [
                'name' => 'Anabela Inácio',
                'role' => 'Consultora Imobiliária',
                'photo' => 'anabela.png',
                'phone' => '+351964872394',
                'email' => null,
                'bio' => 'O meu nome é Anabela Inácio, fui bancária durante 26 anos. Sempre dedicada à área comercial, passando do financiamento automóvel a Gestora de conta de Clientes, considero o ramo Imobiliário um valor acrescentado a toda a minha relação com pessoas. Procuro estar sempre bem informada…'
            ],
            [
                'name' => 'Sandra Guedes',
                'role' => 'Consultora Imobiliária',
                'photo' => 'sandra.png',
                'phone' => '+351934188303',
                'email' => null,
                'bio' => 'O meu nome é Sandra Guedes, tenho 50 anos e sou uma apaixonada por “pessoas” e por “casas”, pelos sonhos e projetos envolvidos, pelas histórias nelas vividas… Sou licenciada em Gestão de Recursos Humanos, com mais de 25 anos de experiência profissional, a lidar com…'
            ],
            [
                'name' => 'Sofia Leitão',
                'role' => 'Consultora Imobiliária',
                'photo' => 'sofia.png',
                'phone' => '+351917715544',
                'email' => 'sofialeitao@remax.pt',
                'bio' => 'O meu nome é Sofia, tenho 52 anos e para mim o imobiliário é a forma que encontrei de satisfazer um interesse pessoal e ao mesmo tempo realizar os sonhos de outras pessoas. Morei fora de Portugal durante 17 anos, e durante esse período era…'
            ],
            [
                'name' => 'Inês Lobo',
                'role' => 'Consultora Imobiliária',
                'photo' => 'ines.png',
                'phone' => '+351913163655',
                'email' => 'inesamaral@remax.pt',
                'bio' => 'O meu nome é Inês Lobo, tenho 28 anos e sou consultora imobiliária. Licenciei-me em Psicologia, mas sempre tive uma grande paixão pelo ramo imobiliário, por isso escolhi a RE/MAX para trabalhar. A par com a Equipa House Team consultores, integro a RE/MAX – ExpoGroup,…'
            ],
            [
                'name' => 'Matilde Pereira',
                'role' => 'Consultora Imobiliária',
                'photo' => 'matilde.png',
                'phone' => '+351967823022',
                'email' => 'matildepereira@remax.pt',
                'bio' => 'O meu nome é Matilde Pereira. Desde que me conheço como pessoa que estou no mundo do empreendedorismo! Tenho um gosto especial por relações com pessoas e por fazer parte da vida dos meus clientes. Acredito que para se ser grande tem de se sonhar…'
            ],
            [
                'name' => 'Marília Miranda',
                'role' => 'Consultora Imobiliária',
                'photo' => 'marilia.png',
                'phone' => '+351910970808',
                'email' => 'mmiranda@remax.pt',
                'bio' => ''
            ],
            [
                'name' => 'Margarida Lopes',
                'role' => 'Consultora Imobiliária',
                'photo' => 'margarida.png',
                'phone' => '+351967635312',
                'email' => null,
                'bio' => 'Desde 2008 a trabalhar no Ramo Imobiliário, tendo me iniciado na área da Consultoria Financeira e, mais tarde, em 2014, encontrado a minha vocação comercial como Consultora Imobiliária. Desde essa data tenho sido reconhecida e premiada todos os anos pelo trabalho e volume de negócios…'
            ],
            [
                'name' => 'Marina Machado',
                'role' => 'Assistente',
                'photo' => 'marina.png',
                'phone' => '+351916123562',
                'email' => null,
                'bio' => 'O seu percurso profissional por áreas de trabalho muito distintas, formação na área da saúde e 5 anos de experiência no ramo imobiliário, determinou a sua elevada capacidade de adaptação e experiência no contacto com pessoas. No ramo imobiliário foi consultora mas é a componente…'
            ],
            [
                'name' => 'Pedro Santos',
                'role' => 'Consultor Imobiliário',
                'photo' => 'pedro.png',
                'phone' => '+351917827196',
                'email' => null,
                'bio' => 'Desde sempre ligado à área comercial, abracei este projeto em 2015. Pode contar com o meu profissionalismo e dedicação.'
            ],
            [
                'name' => 'Hugo Carvalho',
                'role' => 'Consultor Imobiliário',
                'photo' => 'hugo2.png',
                'phone' => '+351961407430',
                'email' => null,
                'bio' => 'Possuo um forte interesse pela área imobiliária, visto que gosto de casas e das suas ínfimas particularidades. Para mim uma “casa” não se trata apenas de algo físico, mas sim de um conceito construtivo, das vivências associadas e memórias que se constroem. É com base…'
            ],
            [
                'name' => 'David Simões',
                'role' => 'Consultor Imobiliário',
                'photo' => 'david.png',
                'phone' => '+351961044596',
                'email' => null,
                'bio' => 'Com vasta experiência na área comercial e no ramo imobiliário, conte com o meu profissionalismo'
            ],
        ];

        foreach ($team as $index => $member) {
            Consultant::updateOrCreate(
                ['name' => $member['name']], // Evita duplicação pelo nome
                [
                    'role' => $member['role'],
                    'photo' => $member['photo'],
                    'phone' => $member['phone'],
                    'email' => $member['email'] ?? null,
                    'bio' => $member['bio'] ?? null,
                    'order' => $index + 2, // Começa em 2 pois o Líder é 1
                    'is_active' => true,
                ]
            );
        }
    }
}