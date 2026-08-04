<?php

declare(strict_types=1);

namespace Dojo\Web;

final class EntradaFormulario
{
    private function __construct(
        public readonly string $num1,
        public readonly string $num2,
        public readonly ?float $numero1,
        public readonly ?float $numero2,
        public readonly array $erros,
    ) {
    }

    public static function deParametros(array $parametros): self
    {
        $num1 = self::texto($parametros, 'num1');
        $num2 = self::texto($parametros, 'num2');

        $numero1 = self::converter($num1);
        $numero2 = self::converter($num2);

        $erros = [];

        if ($num1 !== '' || $num2 !== '') {
            if ($numero1 === null) {
                $erros['num1'] = 'Informe um número válido.';
            }

            if ($numero2 === null) {
                $erros['num2'] = 'Informe um número válido.';
            }
        }

        return new self($num1, $num2, $numero1, $numero2, $erros);
    }

    public function foiEnviada(): bool
    {
        return $this->num1 !== '' || $this->num2 !== '';
    }

    public function estaValida(): bool
    {
        return $this->foiEnviada() && $this->erros === [];
    }

    public function erroDe(string $campo): ?string
    {
        return $this->erros[$campo] ?? null;
    }

    private static function texto(array $parametros, string $campo): string
    {
        $valor = $parametros[$campo] ?? '';

        return is_string($valor) ? trim($valor) : '';
    }

    private static function converter(string $entrada): ?float
    {
        $normalizado = str_replace(',', '.', $entrada);

        return is_numeric($normalizado) ? (float) $normalizado : null;
    }
}
