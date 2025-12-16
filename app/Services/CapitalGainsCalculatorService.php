<?php

namespace App\Services;

class CapitalGainsCalculatorService
{
    private const COEFFICIENTS = [
        2025 => 1.00, 2024 => 1.00, 2023 => 1.00, 2022 => 1.04, 2021 => 1.13,
        2020 => 1.14, 2019 => 1.14, 2018 => 1.14, 2017 => 1.15, 2016 => 1.16,
        2015 => 1.17, 2014 => 1.17, 2013 => 1.17, 2012 => 1.17, 2011 => 1.21,
        2010 => 1.25, 2009 => 1.27, 2008 => 1.25, 2007 => 1.29, 2006 => 1.31,
        2005 => 1.37, 2004 => 1.40, 2003 => 1.42, 2002 => 1.46, 2001 => 1.52,
        2000 => 1.63, 1999 => 1.66, 1998 => 1.68, 1997 => 1.73, 1996 => 1.76,
        1995 => 1.80, 1994 => 1.88, 1993 => 1.97, 1992 => 2.13, 1991 => 2.33,
        1990 => 2.63
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

        $coefficient = self::COEFFICIENTS[$acquisitionYear] ?? ($acquisitionYear < 1990 ? 2.63 : 1.00);
        $updatedAcquisitionValue = $acquisitionValue * $coefficient;

        $grossGain = $saleValue - $updatedAcquisitionValue - $expenses;

        if (isset($data['sold_to_state']) && $data['sold_to_state'] === 'Sim') {
            return $this->buildResult(
                $saleValue, $updatedAcquisitionValue, $expenses, 0, $grossGain, 0, 0, $coefficient, 
                'Isento (Venda ao Estado)'
            );
        }

        if ($acquisitionYear < 1989) {
            return $this->buildResult(
                $saleValue, $updatedAcquisitionValue, $expenses, 0, $grossGain, 0, 0, $coefficient, 
                'Isento (Aquisição anterior a 1989)'
            );
        }

        if ($grossGain <= 0) {
            return $this->buildResult(
                $saleValue, $updatedAcquisitionValue, $expenses, 0, $grossGain, 0, 0, $coefficient, 
                'Sem Mais-Valia'
            );
        }

        $taxableGainBase = $grossGain;
        $reinvestmentValue = 0.0;

        // CORREÇÃO 1: A isenção por reinvestimento só se aplica se o status for 'Sim' (HPP há >= 12 meses).
        if (isset($data['hpp_status']) && $data['hpp_status'] === 'Sim') {
            $reinvest = ($data['reinvest_intention'] === 'Sim') ? (float) ($data['reinvestment_amount'] ?? 0) : 0;
            $amortize = ($data['amortize_credit'] === 'Sim') ? (float) ($data['amortization_amount'] ?? 0) : 0;
            
            $reinvestmentValue = $reinvest + $amortize;

            if ($reinvestmentValue >= $saleValue) {
                $taxableGainBase = 0; // Ganho totalmente isento
            } elseif ($reinvestmentValue > 0) {
                // Cálculo da mais-valia não isenta (base para aplicação dos 50%)
                $nonExemptRatio = ($saleValue - $reinvestmentValue) / $saleValue;
                $taxableGainBase = $grossGain * $nonExemptRatio;
            }
        }

        // CORREÇÃO 2: Aplica-se a regra de tributação: apenas 50% do ganho (ou do ganho não isento) é tributável em IRS.
        $taxableGain = $taxableGainBase * 0.5;

        $annualIncome = (float) ($data['annual_income'] ?? 0);
        $isJoint = isset($data['joint_tax_return']) && $data['joint_tax_return'] === 'Sim';
        
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

        $incomeBase = $isJoint ? ($income / 2) : $income;
        $incomeWithGain = $isJoint ? (($income + $gain) / 2) : ($income + $gain);

        $taxBase = $this->calculateIRS($incomeBase);
        $taxFinal = $this->calculateIRS($incomeWithGain);

        $taxDiff = max(0, $taxFinal - $taxBase);

        return $isJoint ? $taxDiff * 2 : $taxDiff;
    }

    private function calculateIRS(float $income): float
    {
        if ($income <= 0) return 0;

        foreach (self::IRS_BRACKETS_2025 as $bracket) {
            if ($income <= $bracket['limit']) {
                return ($income * $bracket['rate']) - $bracket['deduction'];
            }
        }
        
        return 0;
    }

    /**
     * Formata os resultados numéricos para o formato PT-PT (vírgula como separador decimal).
     */
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