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
        private readonly bool $enviada,
    ) {
    }

    public static function deParametros(array $parametros): self
    {
        $enviada = array_key_exists('num1', $parametros) || array_key_exists('num2', $parametros);

        $num1 = self::texto($parametros, 'num1');
        $num2 = self::texto($parametros, 'num2');

        $numero1 = self::converter($num1);
        $numero2 = self::converter($num2);

        $erros = [];

        if ($enviada) {
            $erros = array_filter([
                'num1' => self::validar($num1, $numero1),
                'num2' => self::validar($num2, $numero2),
            ], static fn (?string $erro): bool => $erro !== null);
        }

        return new self($num1, $num2, $numero1, $numero2, $erros, $enviada);
    }

    public function foiEnviada(): bool
    {
        return $this->enviada;
    }

    public function estaValida(): bool
    {
        return $this->enviada && $this->erros === [];
    }

    public function erroDe(string $campo): ?string
    {
        return $this->erros[$campo] ?? null;
    }

    private static function validar(string $texto, ?float $numero): ?string
    {
        if ($texto === '') {
            return 'Informe um número.';
        }

        return $numero === null ? 'Informe um número válido.' : null;
    }

    private static function texto(array $parametros, string $campo): string
    {
        $valor = $parametros[$campo] ?? '';

        return is_string($valor) ? trim($valor) : '';
    }

    private static function converter(string $entrada): ?float
    {
        if ($entrada === '') {
            return null;
        }

        if (str_contains($entrada, ',')) {
            $normalizado = str_replace(['.', ','], ['', '.'], $entrada);
        } elseif (substr_count($entrada, '.') > 1) {
            $normalizado = str_replace('.', '', $entrada);
        } else {
            $normalizado = $entrada;
        }

        return is_numeric($normalizado) ? (float) $normalizado : null;
    }
}
