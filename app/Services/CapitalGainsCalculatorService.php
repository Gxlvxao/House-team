<?php

namespace App\Services;

class CapitalGainsCalculatorService
{
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

        // Se construiu a própria casa, o coeficiente pode depender de outras regras, mas aqui usamos o ano base
        // (Nota: Num cenário real complexo, "self_built" poderia alterar a data base, mas mantemos simples)
        $coefficient = self::COEFFICIENTS[$acquisitionYear] ?? ($acquisitionYear < 1990 ? 2.73 : 1.00);
        $updatedAcquisitionValue = $acquisitionValue * $coefficient;

        $grossGain = $saleValue - $updatedAcquisitionValue - $expenses;

        // --- ISENÇÕES TOTAIS ---

        // Venda ao Estado
        if (($data['sold_to_state'] ?? 'Não') === 'Sim') {
            return $this->buildResult($saleValue, $updatedAcquisitionValue, $expenses, 0, $grossGain, 0, 0, $coefficient, 'Isento (Venda ao Estado)');
        }

        // Aquisição antes de 1989
        if ($acquisitionYear < 1989) {
            return $this->buildResult($saleValue, $updatedAcquisitionValue, $expenses, 0, $grossGain, 0, 0, $coefficient, 'Isento (Anterior a 1989)');
        }

        if ($grossGain <= 0) {
            return $this->buildResult($saleValue, $updatedAcquisitionValue, $expenses, 0, $grossGain, 0, 0, $coefficient, 'Sem Mais-Valia');
        }

        // --- CÁLCULO DA MATÉRIA COLETÁVEL (CORREÇÃO AQUI) ---

        $taxableGainBase = $grossGain;
        $amountToExclude = 0.0; // Valor total a abater à mais-valia (Reinvestimento + Amortização)

        $isHPP = ($data['hpp_status'] ?? 'Não') === 'Sim';

        // 1. Reinvestimento em NOVA habitação (Apenas se vendeu HPP)
        if ($isHPP && ($data['reinvest_intention'] ?? 'Não') === 'Sim') {
            $amountToExclude += (float) ($data['reinvestment_amount'] ?? 0);
        }

        // 2. Amortização de Crédito Habitação (Válido para HPP e Secundários - Norma Mais Habitação)
        // Se o user preencheu, assumimos que é elegível
        if (($data['amortize_credit'] ?? 'Não') === 'Sim') {
            $amountToExclude += (float) ($data['amortization_amount'] ?? 0);
        }

        // 3. Reformados (PPR/Seguros) - Apenas se vendeu HPP
        if ($isHPP && ($data['retired_status'] ?? 'Não') === 'Sim') {
             // O formulário não tem campo específico "valor investido em PPR", 
             // mas assumimos que entra no "reinvestment_amount" se a lógica for simplificada,
             // ou se não houver campo, ignoramos por agora para não complicar sem dados.
        }

        // Aplicar a exclusão proporcional
        if ($amountToExclude >= $saleValue) {
            $taxableGainBase = 0; // Tudo isento
        } elseif ($amountToExclude > 0) {
            // A parte do ganho proporcional ao valor reinvestido/amortizado fica isenta
            // Fórmula: Mais-Valia Tributável = Mais-Valia Total * (1 - (Valor Reinvestido / Valor Realização))
            $ratio = ($saleValue - $amountToExclude) / $saleValue;
            $taxableGainBase = $grossGain * $ratio;
        }

        // Regra dos 50% (Englobamento)
        $taxableGain = $taxableGainBase * 0.5;

        // --- CÁLCULO IMPOSTO ---

        $annualIncome = (float) ($data['annual_income'] ?? 0);
        $isJoint = ($data['joint_tax_return'] ?? 'Não') === 'Sim';
        
        $estimatedTax = $this->calculateEstimatedTax($taxableGain, $annualIncome, $isJoint);

        return $this->buildResult(
            $saleValue,
            $updatedAcquisitionValue,
            $expenses,
            $amountToExclude, // Passamos o total abatido para mostrar na View
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

        $incomeBase = $isJoint ? ($income / 2) : $income;
        $incomeWithGain = $isJoint ? (($income + $gain) / 2) : ($income + $gain);

        // 1. IRS Normal
        $taxBase = $this->calculateIRS($incomeBase);
        $taxFinal = $this->calculateIRS($incomeWithGain);
        $irsNormal = max(0, $taxFinal - $taxBase);

        // 2. Taxa Solidariedade (>80k)
        $solidarityTax = $this->calculateSolidarityTax($incomeWithGain);
        $solidarityBase = $this->calculateSolidarityTax($incomeBase);
        $solidarityDiff = max(0, $solidarityTax - $solidarityBase);

        $totalPerPerson = $irsNormal + $solidarityDiff;

        return $isJoint ? $totalPerPerson * 2 : $totalPerPerson;
    }

    private function calculateIRS(float $income): float
    {
        if ($income <= 0) return 0;

        foreach (self::IRS_BRACKETS_2025 as $bracket) {
            if ($income <= $bracket['limit']) {
                return ($income * $bracket['rate']) - $bracket['deduction'];
            }
        }
        // Fallback (acima do último escalão)
        return ($income * 0.48) - 10939.45;
    }

    private function calculateSolidarityTax(float $income): float
    {
        if ($income <= 80000) return 0.0;

        $tax = 0.0;
        // Nível 1: 80k a 250k (2.5%)
        if ($income > 80000) {
            $taxable = min($income, 250000) - 80000;
            $tax += $taxable * 0.025;
        }
        // Nível 2: > 250k (5%)
        if ($income > 250000) {
            $taxable = $income - 250000;
            $tax += $taxable * 0.05;
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