<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: sans-serif; color: #333; line-height: 1.6; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #eee; border-radius: 10px; background: #f9f9f9; }
        .header { background: #0f172a; color: #fff; padding: 15px; border-radius: 8px 8px 0 0; text-align: center; }
        .content { background: #fff; padding: 20px; border: 1px solid #ddd; border-top: none; border-radius: 0 0 8px 8px; }
        .label { font-weight: bold; color: #555; display: block; margin-top: 10px; font-size: 0.9em; text-transform: uppercase; }
        .value { margin-bottom: 5px; color: #000; }
        .highlight { color: #2563eb; font-weight: bold; }
        .footer { font-size: 12px; color: #999; text-align: center; margin-top: 20px; }
        .section-title { margin-top: 20px; padding-bottom: 5px; border-bottom: 2px solid #eee; color: #0f172a; font-size: 1.1em; font-weight: bold; }
        ul { margin: 5px 0; padding-left: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Nova Lead: {{ $data['subject'] ?? 'Contacto Geral' }}</h2>
        </div>
        <div class="content">
            <p>Recebeu um novo pedido de contacto através do site.</p>

            <div class="section-title">Dados do Cliente</div>
            
            <span class="label">Nome:</span>
            <div class="value highlight">{{ $data['name'] }}</div>

            <span class="label">Email:</span>
            <div class="value"><a href="mailto:{{ $data['email'] }}">{{ $data['email'] }}</a></div>

            @if(!empty($data['phone']))
                <span class="label">Telefone:</span>
                <div class="value">{{ $data['phone'] }}</div>
            @endif

            @if(!empty($data['address']) && !empty($data['property_type']))
                <span class="label">Morada (Cliente/Imóvel):</span>
                <div class="value">{{ $data['address'] }}</div>
            @endif

            {{-- SE FOR UMA MENSAGEM GERAL --}}
            @if(!empty($data['message']))
                <div class="section-title">Mensagem</div>
                <div class="value" style="white-space: pre-wrap; background: #f0f4f8; padding: 15px; border-radius: 5px;">{{ $data['message'] }}</div>
            @endif

            {{-- SE FOR UMA AVALIAÇÃO DE IMÓVEL --}}
            @if(!empty($data['property_type']))
                <div class="section-title">Detalhes da Propriedade</div>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                    <div><span class="label">Tipo:</span> <span class="value">{{ $data['property_type'] }}</span></div>
                    <div><span class="label">Ano:</span> <span class="value">{{ $data['year'] ?? 'N/A' }}</span></div>
                    <div><span class="label">Área:</span> <span class="value">{{ $data['area'] ?? '0' }} m²</span></div>
                    <div><span class="label">Tipologia:</span> <span class="value">T{{ $data['bedrooms'] ?? '0' }}</span></div>
                    <div><span class="label">WC:</span> <span class="value">{{ $data['bathrooms'] ?? '0' }}</span></div>
                </div>

                @if(!empty($data['parking_type']) || !empty($data['garages']))
                    <span class="label">Estacionamento:</span>
                    <div class="value">
                        {{ $data['parking_type'] ?? 'Não especificado' }} 
                        @if(!empty($data['garages']) && $data['garages'] > 0)
                            ({{ $data['garages'] }} lugares)
                        @endif
                    </div>
                @endif

                @if(!empty($data['condition']))
                    <span class="label">Condição:</span>
                    <div class="value">{{ $data['condition'] }}</div>
                @endif

                @if(!empty($data['features']))
                    <span class="label">Características:</span>
                    <div class="value">
                        <ul>
                            @foreach($data['features'] as $feature)
                                <li>{{ $feature }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="section-title">Informação Adicional</div>
                
                <span class="label">É Proprietário?</span>
                <div class="value">{{ $data['is_owner'] ?? 'Não especificado' }}</div>

                @if(!empty($data['estimated_value']))
                    <span class="label">Valor Estimado pelo Cliente:</span>
                    <div class="value highlight">€ {{ number_format((float)$data['estimated_value'], 2, ',', '.') }}</div>
                @endif
            @endif

        </div>
        <div class="footer">
            Email enviado automaticamente pelo sistema House Team.
        </div>
    </div>
</body>
</html>