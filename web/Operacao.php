<?php

declare(strict_types=1);

namespace Dojo\Web;

final class Operacao
{
    public function __construct(
        public readonly string $chave,
        public readonly string $rotulo,
        public readonly string $simbolo,
    ) {
    }

    public static function todas(): array
    {
        return [
            new self('soma', 'Soma', '+'),
            new self('diferenca', 'Diferença', '−'),
            new self('produto', 'Produto', '×'),
            new self('divisao', 'Divisão', '÷'),
            new self('resto', 'Resto', 'mod'),
        ];
    }
}
