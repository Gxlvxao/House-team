<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Sua Simulação - House Team</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; color: #333; line-height: 1.6; margin: 0; padding: 0; background-color: #f4f4f4; }
        .container { max-width: 600px; margin: 20px auto; background: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .header { background: #020617; color: #ffffff; padding: 25px; text-align: center; border-bottom: 4px solid #dc2626; }
        .header h1 { margin: 0; font-size: 22px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; }
        .content { padding: 35px 30px; }
        .greeting { font-size: 18px; color: #020617; margin-top: 0; }
        .highlight-box { background-color: #f0f9ff; border-left: 4px solid #0ea5e9; padding: 20px; border-radius: 4px; margin: 25px 0; }
        .highlight-text { font-weight: 600; color: #0369a1; font-size: 15px; }
        .cta-text { font-size: 14px; margin-top: 10px; color: #334155; }
        .footer { background: #f8fafc; padding: 20px; text-align: center; font-size: 12px; color: #94a3b8; border-top: 1px solid #e2e8f0; }
        .footer a { color: #dc2626; text-decoration: none; font-weight: bold; }
        .btn-attachment { display: inline-block; background: #dc2626; color: #fff; text-decoration: none; padding: 10px 20px; border-radius: 5px; font-weight: bold; font-size: 14px; margin-top: 10px; }
    </style>
</head>
<body>
    <div class="container">
        {{-- HEADER --}}
        <div class="header">
            <h1>House Team Consultores</h1>
        </div>
        
        {{-- CONTENT --}}
        <div class="content">
            <p class="greeting">Olá <strong>{{ $name }}</strong>,</p>

            <p>Obrigado por utilizar o nosso simulador. Conforme solicitado, enviamos em anexo o seu relatório detalhado de <strong>{{ $simulationType }}</strong>.</p>

            <p>Analisámos os dados preliminares e o resultado segue no ficheiro PDF junto a este email.</p>

            {{-- CAIXA DE AÇÃO (PERGUNTA) --}}
            <div class="highlight-box">
                <p class="highlight-text" style="margin-top: 0;">Para avançarmos com total segurança:</p>
                <p class="cta-text">Prefere que validemos consigo os dados por <strong>WhatsApp</strong> ou por uma <strong>chamada rápida</strong> (5 min)?</p>
                <p style="margin-bottom: 0; font-size: 14px;">👉 <em>Basta responder a este email com "WhatsApp" ou "Chamada" e o seu melhor horário.</em></p>
            </div>

            <p>Estamos à sua disposição para esclarecer qualquer dúvida sobre este relatório.</p>

            <br>
            <p style="margin-bottom: 0;">Com os melhores cumprimentos,</p>
            <p style="margin-top: 5px; font-weight: bold; color: #020617;">Equipa House Team</p>
        </div>

        {{-- FOOTER --}}
        <div class="footer">
            <a href="https://houseteamconsultores.pt">www.houseteamconsultores.pt</a>
            <br><br>
            &copy; {{ date('Y') }} House Team - Broker Empreendedor.
        </div>
    </div>
</body>
</html>