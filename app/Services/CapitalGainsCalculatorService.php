<?php

namespace App\Services;

class CapitalGainsCalculatorService
{
    // Coeficientes baseados na última Portaria conhecida.
    // Devem ser atualizados anualmente (geralmente em Outubro).
    private const COEFFICIENTS = [
        2025 => 1.00, 2024 => 1.00, 2023 => 1.00, 2022 => 1.05, 2021 => 1.13,
        2020 => 1.14, 2019 => 1.14, 2018 => 1.15, 2017 => 1.17, 2016 => 1.19,
        2015 => 1.20, 2014 => 1.21, 2013 => 1.22, 2012 => 1.24, 2011 => 1.28,
        2010 => 1.33, 2009 => 1.34, 2008 => 1.35, 2007 => 1.38, 2006 => 1.41,
        2005 => 1.45, 2004 => 1.48, 2003 => 1.51, 2002 => 1.56, 2001 => 1.62,
        2000 => 1.69, 1999 => 1.74, 1998 => 1.78, 1997 => 1.82, 1996 => 1.86,
        1995 => 1.92, 1994 => 1.99, 1993 => 2.09, 1992 => 2.23, 1991 => 2.44,
        1990 => 2.73
    ];

    // Escalões IRS Provisórios 2025 (Confirmar com OE2025 aprovado)
    private const IRS_BRACKETS_2025 = [
        ['limit' => 8059,  'rate' => 0.1250, 'deduction' => 0.00],
        ['limit' => 12160, 'rate' => 0.1600, 'deduction' => 282.07],
        ['limit' => 17233, 'rate' => 0.2150, 'deduction' => 950.87],
        ['limit' => 22306, 'rate' => 0.2440, 'deduction' => 1450.63],
        ['limit' => 28400, 'rate' => 0.3140, 'deduction' => 3011.65],
        ['limit' => 41629, 'rate' => 0.3490, 'deduction' => 4005.65],
        ['limit' => 44987, 'rate' => 0.4310, 'deduction' => 7418.98],
        ['limit' => 83696, 'rate' => 0.4460, 'deduction' => 8093.79],
        ['limit' => INF,   'rate' => 0.4800, 'deduction' => 10939.45],
    ];

    public function calculate(array $data): array
    {
        $saleValue = (float) $data['sale_value'];
        $acquisitionValue = (float) $data['acquisition_value'];
        $acquisitionYear = (int) $data['acquisition_year'];
        $expenses = (float) ($data['expenses_total'] ?? 0);

        // Lógica de Coeficiente (Default para anos muito antigos usa o último disponível ou lógica customizada)
        $coefficient = self::COEFFICIENTS[$acquisitionYear] ?? ($acquisitionYear < 1990 ? 2.73 : 1.00);
        $updatedAcquisitionValue = $acquisitionValue * $coefficient;

        $grossGain = $saleValue - $updatedAcquisitionValue - $expenses;

        // --- CENÁRIOS DE ISENÇÃO TOTAL ---

        // 1. Venda ao Estado (Isenção total art. 102.º CIT)
        if (($data['sold_to_state'] ?? 'Não') === 'Sim') {
            return $this->buildResult($saleValue, $updatedAcquisitionValue, $expenses, 0, $grossGain, 0, 0, $coefficient, 'Isento (Venda ao Estado)');
        }

        // 2. Aquisição anterior a 1989 (Regime transitório)
        if ($acquisitionYear < 1989) {
            return $this->buildResult($saleValue, $updatedAcquisitionValue, $expenses, 0, $grossGain, 0, 0, $coefficient, 'Isento (Aquisição anterior a 1989)');
        }

        // 3. Prejuízo (Menos-valia)
        if ($grossGain <= 0) {
            return $this->buildResult($saleValue, $updatedAcquisitionValue, $expenses, 0, $grossGain, 0, 0, $coefficient, 'Sem Mais-Valia');
        }

        // --- CÁLCULO DA MATÉRIA COLETÁVEL ---

        $taxableGainBase = $grossGain;
        $reinvestmentValue = 0.0;

        // Isenção por Reinvestimento ou Amortização (HPP >= 12 meses)
        if (($data['hpp_status'] ?? 'Não') === 'Sim') {
            $reinvestIntention = $data['reinvest_intention'] ?? 'Não';
            $amortizeCredit = $data['amortize_credit'] ?? 'Não';
            
            $reinvest = ($reinvestIntention === 'Sim') ? (float) ($data['reinvestment_amount'] ?? 0) : 0;
            $amortize = ($amortizeCredit === 'Sim') ? (float) ($data['amortization_amount'] ?? 0) : 0;
            
            $reinvestmentValue = $reinvest + $amortize;

            if ($reinvestmentValue >= $saleValue) {
                // Se reinvestiu tudo o que realizou (valor de venda), o ganho é totalmente isento
                $taxableGainBase = 0; 
            } elseif ($reinvestmentValue > 0) {
                // Fórmula Proporcional: (Valor Venda - Reinvestido) / Valor Venda
                $nonExemptRatio = ($saleValue - $reinvestmentValue) / $saleValue;
                $taxableGainBase = $grossGain * $nonExemptRatio;
            }
        }

        // Regra de Englobamento Obrigatório (Residentes): Apenas 50% do saldo é tributado
        $taxableGain = $taxableGainBase * 0.5;

        // --- CÁLCULO DO IMPOSTO ESTIMADO ---

        $annualIncome = (float) ($data['annual_income'] ?? 0);
        $isJoint = ($data['joint_tax_return'] ?? 'Não') === 'Sim';
        
        $estimatedTax = $this->calculateEstimatedTax($taxableGain, $annualIncome, $isJoint);

        return $this->buildResult(
            $saleValue,
            $updatedAcquisitionValue,
            $expenses,
            $reinvestmentValue,
            $grossGain,
            $taxableGain,
            $estimatedTax,
            $coefficient,
            'Tributável'
        );
    }

    private function calculateEstimatedTax(float $gain, float $income, bool $isJoint): float
    {
        if ($gain <= 0) return 0;

        // Se for conjunto, dividimos por 2 para achar a taxa, e no final multiplicamos o imposto por 2 (splitting)
        $incomeBase = $isJoint ? ($income / 2) : $income;
        
        // Rendimento Global para determinar a taxa (Salário + Mais-Valia)
        $incomeWithGain = $isJoint ? (($income + $gain) / 2) : ($income + $gain);

        // 1. Calcular IRS Normal (Progressivo)
        $taxBase = $this->calculateIRS($incomeBase);
        $taxFinal = $this->calculateIRS($incomeWithGain);
        
        $irsNormal = max(0, $taxFinal - $taxBase);

        // 2. Calcular Taxa Adicional de Solidariedade (Para rendimentos muito altos)
        // Aplica-se sobre o rendimento COLETÁVEL agregado (income + gain)
        // Se for conjunto, a taxa aplica-se individualmente a cada sujeito passivo após o splitting? 
        // Simplificação: Aplica-se ao rendimento sujeito às taxas gerais.
        
        $solidarityTax = $this->calculateSolidarityTax($incomeWithGain);
        
        // Diferencial da solidariedade (quanto a mais-valia acrescentou de taxa solidária)
        // Nota: Se o salário já pagava solidariedade, pagamos apenas a diferença.
        $solidarityBase = $this->calculateSolidarityTax($incomeBase);
        $solidarityDiff = max(0, $solidarityTax - $solidarityBase);

        $totalTaxPerPerson = $irsNormal + $solidarityDiff;

        return $isJoint ? $totalTaxPerPerson * 2 : $totalTaxPerPerson;
    }

    private function calculateIRS(float $income): float
    {
        if ($income <= 0) return 0;

        foreach (self::IRS_BRACKETS_2025 as $bracket) {
            if ($income <= $bracket['limit']) {
                return ($income * $bracket['rate']) - $bracket['deduction'];
            }
        }
        // Fallback para último escalão (já coberto pelo INF, mas por segurança)
        return ($income * 0.48) - 10939.45;
    }

    /**
     * Calcula a Taxa Adicional de Solidariedade (Lei n.º 2/2020 e Art 68-A CIRS)
     * 80.000€ a 250.000€ -> 2.5%
     * > 250.000€ -> 5%
     */
    private function calculateSolidarityTax(float $income): float
    {
        if ($income <= 80000) return 0.0;

        $tax = 0.0;

        // Nível 1: Entre 80k e 250k
        if ($income > 80000) {
            $taxableAmount = min($income, 250000) - 80000;
            $tax += $taxableAmount * 0.025;
        }

        // Nível 2: Acima de 250k
        if ($income > 250000) {
            $taxableAmount = $income - 250000;
            $tax += $taxableAmount * 0.05;
        }

        return $tax;
    }

    private function buildResult($sale, $acqUpd, $exp, $reinvest, $gross, $taxable, $tax, $coef, $status): array
    {
        return [
            'sale_fmt' => number_format($sale, 2, ',', '.'),
            'coefficient' => number_format($coef, 2, ',', '.'),
            'acquisition_updated_fmt' => number_format($acqUpd, 2, ',', '.'),
            'expenses_fmt' => number_format($exp, 2, ',', '.'),
            'reinvestment_fmt' => number_format($reinvest, 2, ',', '.'),
            'gross_gain_fmt' => number_format($gross, 2, ',', '.'),
            'taxable_gain_fmt' => number_format($taxable, 2, ',', '.'),
            'estimated_tax_fmt' => number_format($tax, 2, ',', '.'),
            'status' => $status,
            'raw_tax' => $tax,
            'raw_gross' => $gross
        ];
    }
}