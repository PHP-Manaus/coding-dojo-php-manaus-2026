<?php

declare(strict_types=1);

namespace Dojo\Web;

final class FormatadorNumero
{
    public static function formatar(int|float $numero): string
    {
        if (is_float($numero) && is_nan($numero)) {
            return 'indefinido';
        }

        if (is_float($numero) && is_infinite($numero)) {
            return $numero > 0 ? 'infinito' : '-infinito';
        }

        if (is_int($numero) || fmod($numero, 1.0) === 0.0) {
            return number_format((float) $numero, 0, ',', '.');
        }

        $formatado = rtrim(rtrim(number_format($numero, 6, ',', '.'), '0'), ',');

        if ($formatado === '0' || $formatado === '-0') {
            return str_replace('.', ',', sprintf('%g', $numero));
        }

        return $formatado;
    }
}
