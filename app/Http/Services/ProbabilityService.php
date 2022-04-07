<?php

namespace App\Http\Services;

final class ProbabilityService
{
    /**
     * Реализация теоремы Бернулли
     *
     * @param int $n Количество независимых испытаний(аккаунтов)
     * @param int $k Точное количество наступлений события(сколько аккаунтов должно выйграть точно)
     * @param float $p Вероятность наступления события(шанс на победу одним аккаунтов)
     * @return float|int
     */
    private function bernulliTheorem(int $n, int $k, float $p): float|int
    {
        $Ckn = gmp_strval(gmp_fact($n)) / ( gmp_strval(gmp_fact($k)) * gmp_strval(gmp_fact($n-$k)) );
        return $Ckn * pow($p, $k) * pow(1 - $p, $n-$k);
    }

    private function probabilityToPercent(float $p): string
    {
        return round($p * 100, 2) . "%<br>";
    }


    /**
     * @param int $accounts
     * @param int $winners
     * @param int $participants
     * @return \Illuminate\Http\JsonResponse|string
     */
    public function calculate(int $accounts, int $winners, int $participants): \Illuminate\Http\JsonResponse|string
    {
        $output = "";
        //Вероятность одной победы
        $p = $winners / $participants;

        // Рассчет для одной победы
        $toWin = 1;
        $output .= $this->outputGenerate($accounts, $toWin, $p);

        if($accounts > 2) {
            $output .= $this->outputGenerate($accounts, 2, $p);
        }

        if($accounts > 3) {
            $output .= $this->outputGenerate($accounts, 3, $p);
        }

        if($accounts > 1) {
            $output .= $this->outputGenerate($accounts, $accounts, $p);
        }

        return $output;
    }

    /**
     * @param int $accounts
     * @param int $toWin
     * @param float $p
     * @return string
     */
    public function outputGenerate(int $accounts, int $toWin, float $p): string
    {
        $sum = 0;
        for($n = $toWin; $n <= $accounts; $n++){
            $sum += $this->bernulliTheorem($accounts, $n, $p);
        }

        if(is_nan($sum) ) {
            return "<span style='font-family: Muller;'>Я такое считать не буду. Интегральная теорема Муавра-Лапласа тебе в помощь</span><br>";
        }

        return $this->outputText($toWin) . $this->probabilityToPercent($sum);
    }

    private function outputText(int $toWin): string
    {
        $text = match ($toWin) {
            1 => "Вероятность попадания хотя бы одним аккаунтом: ",
            2 => "Вероятность попадания хотя бы двумя аккаунтами: ",
            3 => "Вероятность попадания хотя бы тремя аккаунтами: ",
            default => "Вероятность попадания всеми аккаунтами: "
        };

        return "<span class='font-text'>" . $text. "</span>";
    }
}
