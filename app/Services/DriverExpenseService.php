<?php

namespace App\Services;

use PhpParser\Node\Expr\Cast\Array_;

class DriverExpenseService
{
    public function calculateDriverExpenses(array $drivers, array $expenses)
    {
        $result = [];
        $header = ['Expense/Driver', 'Amount, $'];
        $totalSum = 0;
        $totalSumDrivers = [];

        foreach ($drivers as $key => $value) {
            array_push($header, $value);
            array_push($totalSumDrivers, 0);
        }
        array_push($result, $header);

        for ($i = 0; $i < count($expenses); $i++) {
            $data = [];
            $title = array_keys($expenses);
            $amount = array_values($expenses);
            $roundedAmount = round($amount[$i], 2);
            $totalSum += $amount[$i];
            $divivedSum = bcdiv($amount[$i], count($drivers), 2);
            array_push($data, $title[$i], $roundedAmount);

            if (round($amount[$i] / count($drivers), 3, PHP_ROUND_HALF_DOWN) == $divivedSum) {
                foreach ($drivers as $key => $value) {
                    $totalSumDrivers[$key] = $totalSumDrivers[$key] + round($divivedSum, 2, PHP_ROUND_HALF_DOWN);
                    $sum = round($divivedSum, 2, PHP_ROUND_HALF_DOWN);
                    array_push($data, $sum);
                }
            } else {
                $roundedSumArr = [];
                $roundedSum = 0;
                foreach ($drivers as $key => $value) {
                    $downSum = round($divivedSum, 2, PHP_ROUND_HALF_DOWN);
                    $sum = $downSum;
                    $roundedSum += $sum;
                    array_push($roundedSumArr, $sum);
                }

                // IF row sum = rowsum - 0.01 it will allocate sum incorrectly.
                $expectionSum = bcadd($roundedSum, ((count($totalSumDrivers) - 1) * 0.01), 2);

                if ($expectionSum == $roundedAmount) {
                    foreach ($totalSumDrivers as $key => $val) {
                        $roundedSumArr[$key] += 0.01;
                        $roundedSum += 0.01;
                    }

                    $maxValue = max($totalSumDrivers);
                    $maxKey = '';
                    $reversedArray = array_reverse($totalSumDrivers, true);

                    foreach ($reversedArray as $key => $value) {
                        if ($value == $maxValue) {
                            $maxKey = $key;
                            break;
                        }
                    }
                    $roundedSumArr[$maxKey] -= 0.01;
                } else {
                    $minVal = min($totalSumDrivers);
                    foreach ($totalSumDrivers as $key => $val) {
                        if (round($roundedSum, 2) == $roundedAmount) {
                            break;
                        }

                        if ($minVal == $val) {

                            if (round($roundedSum, 2) !== $roundedAmount) {

                                $roundedSumArr[$key] += 0.01;
                                $roundedSum += 0.01;
                            }
                        }
                    }
                    if (round($roundedSum, 2) !== $roundedAmount) {
                        foreach ($roundedSumArr as $key => $value) {
                            if ($minVal == $val) {
                                if (round($roundedSum, 2) == $roundedAmount) {
                                    break;
                                }
                                if (round($roundedSum, 2) !== $roundedAmount) {
                                    $roundedSumArr[$key] += 0.01;
                                    $roundedSum += 0.01;
                                }
                            }
                        }
                    }
                }

                foreach ($roundedSumArr as $key => $value) {

                    $totalSumDrivers[$key] = $totalSumDrivers[$key] + $value;
                    array_push($data, round($value, 2));
                }
            }
            array_push($result, $data);
        }

        array_unshift($totalSumDrivers, 'Total:', round($totalSum, 2));
        array_push($result, $totalSumDrivers);

        return $result;
    }
}
