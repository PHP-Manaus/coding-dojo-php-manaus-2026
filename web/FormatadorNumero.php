<?php

declare(strict_types=1);

namespace Dojo\Web;

final class FormatadorNumero
{
    public static function formatar(int|float $numero): string
    {
        if (is_int($numero) || fmod($numero, 1.0) === 0.0) {
            return number_format((float) $numero, 0, ',', '.');
        }

        return rtrim(rtrim(number_format($numero, 6, ',', '.'), '0'), ',');
    }
}
