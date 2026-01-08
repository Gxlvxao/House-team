<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ $title }}</title>
    <style>
        /* Configuração de Fonte para suportar UTF-8 e Euro */
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            color: #334155; /* Slate-700 */
            line-height: 1.5;
        }

        /* Cabeçalho */
        .header {
            text-align: center;
            margin-bottom: 40px;
            border-bottom: 3px solid #dc2626; /* Vermelho HT */
            padding-bottom: 20px;
        }
        .logo {
            font-size: 28px;
            font-weight: 900;
            color: #020617; /* Navy HT */
            text-transform: uppercase;
            letter-spacing: -1px;
            margin-bottom: 10px;
        }
        .logo span {
            color: #94a3b8; /* Slate-400 para 'TEAM' ou subtil */
        }
        .report-title {
            font-size: 18px;
            font-weight: bold;
            color: #020617;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .meta {
            font-size: 10px;
            color: #64748b;
            margin-top: 5px;
        }

        /* Tabela de Dados */
        .table-container {
            margin-top: 20px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            overflow: hidden;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        .table th {
            background-color: #f1f5f9;
            color: #0f172a;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 10px;
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
        }
        .table td {
            padding: 12px 15px;
            border-bottom: 1px solid #e2e8f0;
            vertical-align: middle;
        }
        .table tr:last-child td {
            border-bottom: none;
        }
        .table tr:nth-child(even) {
            background-color: #f8fafc;
        }

        /* Estilos de Texto */
        .label {
            font-weight: bold;
            color: #475569;
            text-transform: capitalize;
        }
        .value {
            font-weight: normal;
            color: #0f172a;
            text-align: right;
        }
        .highlight {
            color: #dc2626; /* Vermelho Destaque */
            font-weight: bold;
            font-size: 13px;
        }

        /* Rodapé */
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 9px;
            color: #94a3b8;
            border-top: 1px solid #e2e8f0;
            padding-top: 15px;
            padding-bottom: 15px;
        }
        .footer a {
            color: #dc2626;
            text-decoration: none;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        {{-- Simulação de Logo Textual --}}
        <div class="logo">HOUSE <span style="color: #64748b;">TEAM</span></div>
        <h2 class="report-title">{{ $title }}</h2>
        <div class="meta">Gerado em: {{ $date }}</div>
    </div>

    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th>Descrição</th>
                    <th style="text-align: right;">Valor</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $key => $value)
                    {{-- Filtra campos internos que não devem aparecer no PDF --}}
                    @if(!in_array($key, ['lead_name', 'lead_email', '_token', 'location', 'purpose']))
                        <tr>
                            <td class="label">
                                {{-- Formata a chave (ex: propertyValue -> Property Value) --}}
                                {{ ucwords(str_replace(['_', 'fmt'], [' ', ''], preg_replace('/(?<!^)[A-Z]/', ' $0', $key))) }}
                            </td>
                            <td class="value {{ in_array($key, ['totalPayable', 'monthlyPayment', 'estimated_tax_fmt']) ? 'highlight' : '' }}">
                                {{-- Se for numérico, formata como moeda, se não, imprime direto --}}
                                @if(is_numeric($value))
                                    {{ number_format((float)$value, 2, ',', '.') }} €
                                @else
                                    {{ $value }}
                                @endif
                            </td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="footer">
        <p>Este documento é meramente informativo e não dispensa a consulta de profissionais especializados ou propostas oficiais bancárias.</p>
        <p>House Team Consultores | <a href="https://houseteamconsultores.pt">www.houseteamconsultores.pt</a></p>
    </div>
</body>
</html>