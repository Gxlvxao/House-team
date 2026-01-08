<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Nova Lead - House Team</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; color: #333; line-height: 1.6; margin: 0; padding: 0; background-color: #f4f4f4; }
        .container { max-width: 600px; margin: 20px auto; background: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .header { background: #020617; color: #ffffff; padding: 20px; text-align: center; border-bottom: 4px solid #dc2626; }
        .header h2 { margin: 0; font-size: 20px; text-transform: uppercase; letter-spacing: 1px; }
        .content { padding: 30px; }
        .label { font-size: 11px; font-weight: bold; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; margin-top: 15px; display: block; }
        .value { font-size: 15px; color: #0f172a; font-weight: 500; margin-bottom: 5px; }
        .highlight { color: #dc2626; font-weight: 700; }
        .section-title { margin-top: 25px; margin-bottom: 15px; padding-bottom: 10px; border-bottom: 1px solid #e2e8f0; color: #020617; font-size: 16px; font-weight: 700; text-transform: uppercase; }
        .footer { background: #f8fafc; padding: 15px; text-align: center; font-size: 11px; color: #94a3b8; border-top: 1px solid #e2e8f0; }
        ul { margin: 5px 0; padding-left: 20px; color: #0f172a; }
        li { margin-bottom: 3px; }
        .btn { display: inline-block; background: #25d366; color: #fff; text-decoration: none; padding: 8px 16px; border-radius: 4px; font-size: 12px; font-weight: bold; margin-top: 5px; }
        .property-ref { background: #eff6ff; color: #1e3a8a; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: bold; display: inline-block; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Nova Lead</h2>
            <div style="font-size: 12px; opacity: 0.8; margin-top: 5px;">{{ $data['subject'] ?? 'Contacto Geral' }}</div>
        </div>
        
        <div class="content">
            <p style="margin-top: 0;">Olá, recebeste um novo pedido de contacto através do website.</p>

            {{-- DADOS DO CLIENTE --}}
            <div class="section-title">Dados do Cliente</div>
            
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="width: 50%; vertical-align: top;">
                        <span class="label">Nome</span>
                        <div class="value highlight">{{ $data['name'] }}</div>
                    </td>
                    <td style="width: 50%; vertical-align: top;">
                        <span class="label">Email</span>
                        <div class="value"><a href="mailto:{{ $data['email'] }}" style="color: #2563eb; text-decoration: none;">{{ $data['email'] }}</a></div>
                    </td>
                </tr>
                <tr>
                    <td style="width: 50%; vertical-align: top;">
                        <span class="label">Telefone</span>
                        @if(!empty($data['phone']))
                            <div class="value">{{ $data['phone'] }}</div>
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $data['phone']) }}" class="btn">WhatsApp</a>
                        @else
                            <div class="value text-muted">-</div>
                        @endif
                    </td>
                    <td style="width: 50%; vertical-align: top;">
                        @if(!empty($data['property_code']))
                            <span class="label">Ref. Imóvel</span>
                            <div class="value"><span class="property-ref">{{ $data['property_code'] }}</span></div>
                        @endif
                    </td>
                </tr>
            </table>

            @if(!empty($data['address']) && !empty($data['property_type']))
                <span class="label">Morada (Imóvel/Cliente)</span>
                <div class="value">{{ $data['address'] }}</div>
            @endif

            {{-- MENSAGEM GERAL --}}
            @if(!empty($data['message']))
                <div class="section-title">Mensagem</div>
                <div class="value" style="white-space: pre-wrap; background: #f8fafc; padding: 15px; border-radius: 6px; border: 1px solid #e2e8f0; font-size: 14px;">{{ $data['message'] }}</div>
            @endif

            {{-- DETALHES DE AVALIAÇÃO --}}
            @if(!empty($data['property_type']))
                <div class="section-title">Detalhes da Propriedade (Avaliação)</div>
                
                <table style="width: 100%; border-collapse: collapse; margin-bottom: 15px;">
                    <tr>
                        <td style="padding-bottom: 10px; width: 33%;">
                            <span class="label">Tipo</span>
                            <div class="value">{{ $data['property_type'] }}</div>
                        </td>
                        <td style="padding-bottom: 10px; width: 33%;">
                            <span class="label">Ano</span>
                            <div class="value">{{ $data['year'] ?? '-' }}</div>
                        </td>
                        <td style="padding-bottom: 10px; width: 33%;">
                            <span class="label">Área</span>
                            <div class="value">{{ $data['area'] ?? '0' }} m²</div>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding-bottom: 10px;">
                            <span class="label">Tipologia</span>
                            <div class="value">T{{ $data['bedrooms'] ?? '0' }}</div>
                        </td>
                        <td style="padding-bottom: 10px;">
                            <span class="label">WC</span>
                            <div class="value">{{ $data['bathrooms'] ?? '0' }}</div>
                        </td>
                        <td style="padding-bottom: 10px;">
                            <span class="label">Garagem</span>
                            <div class="value">
                                @if(!empty($data['garages']) && $data['garages'] > 0)
                                    {{ $data['garages'] }} lug. ({{ $data['parking_type'] ?? 'Box' }})
                                @else
                                    Não
                                @endif
                            </div>
                        </td>
                    </tr>
                </table>

                @if(!empty($data['condition']))
                    <span class="label">Estado de Conservação</span>
                    <div class="value">{{ $data['condition'] }}</div>
                @endif

                @if(!empty($data['features']))
                    <span class="label">Características</span>
                    <div class="value">
                        <ul>
                            @foreach($data['features'] as $feature)
                                <li>{{ $feature }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div style="background: #f0fdf4; border: 1px solid #dcfce7; padding: 15px; border-radius: 6px; margin-top: 15px;">
                    <span class="label" style="color: #166534; margin-top: 0;">Dados Proprietário</span>
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 5px;">
                        <div>
                            <span style="font-size: 12px; color: #166534;">É Proprietário?</span>
                            <div class="value" style="font-weight: bold;">{{ $data['is_owner'] ?? '-' }}</div>
                        </div>
                        @if(!empty($data['estimated_value']))
                            <div style="text-align: right;">
                                <span style="font-size: 12px; color: #166534;">Valor Estimado</span>
                                <div class="value highlight" style="color: #16a34a;">€ {{ number_format((float)$data['estimated_value'], 2, ',', '.') }}</div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

        </div>
        <div class="footer">
            &copy; {{ date('Y') }} House Team - Sistema Automático de Leads.<br>
            Não responda diretamente a este email automático.
        </div>
    </div>
</body>
</html>