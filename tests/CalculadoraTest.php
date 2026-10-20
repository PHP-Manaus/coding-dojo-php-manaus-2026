<?php

declare(strict_types=1);

namespace Dojo\Tests;

use Dojo\Calculadora;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class CalculadoraTest extends TestCase
{
    private Calculadora $calculadora;

    protected function setUp(): void
    {
        $this->calculadora = new Calculadora();
    }

    #[Test]
    public function soma_dois_numeros(): void
    {
        $this->assertSame(2.0, $this->calculadora->soma(1, 1));
        $this->assertSame(5.0, $this->calculadora->soma(2, 3));
    }

    #[Test]
    public function soma_numeros_negativos_e_decimais(): void
    {
        $this->assertSame(-1.0, $this->calculadora->soma(2, -3));
        $this->assertEqualsWithDelta(0.3, $this->calculadora->soma(0.1, 0.2), 0.000001);
    }

    #[Test]
    public function subtrai_dois_numeros(): void
    {
        $this->assertSame(1.0, $this->calculadora->subtracao(3, 2));
        $this->assertSame(-1.0, $this->calculadora->subtracao(2, 3));
    }

    #[Test]
    public function multiplica_dois_numeros(): void
    {
        $this->assertSame(6.0, $this->calculadora->multiplicacao(2, 3));
        $this->assertSame(0.0, $this->calculadora->multiplicacao(5, 0));
    }

    #[Test]
    public function divide_dois_numeros(): void
    {
        $this->assertSame(2.0, $this->calculadora->divisao(6, 3));
        $this->assertSame(2.5, $this->calculadora->divisao(5, 2));
    }

    #[Test]
    public function nao_divide_por_zero(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Não é possível dividir por zero.');

        $this->calculadora->divisao(5, 0);
    }

    #[Test]
    public function calcula_o_resto_da_divisao(): void
    {
        $this->assertSame(1.0, $this->calculadora->resto(7, 3));
        $this->assertSame(0.0, $this->calculadora->resto(6, 3));
        $this->assertSame(1.5, $this->calculadora->resto(5.5, 2));
    }

    #[Test]
    public function nao_calcula_resto_com_divisor_zero(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->calculadora->resto(5, 0);
    }

    #[Test]
    #[DataProvider('operacoes')]
    public function calcula_e_descreve_o_resultado(float $num1, float $num2, string $operacao, string $esperado): void
    {
        $this->assertSame($esperado, $this->calculadora->calcular($num1, $num2, $operacao));
    }

    public static function operacoes(): array
    {
        return [
            'soma' => [1, 1, 'soma', 'Resultado da soma 2'],
            'subtração' => [5, 3, 'subtracao', 'Resultado da subtração 2'],
            'multiplicação' => [2, 3, 'multiplicacao', 'Resultado da multiplicação 6'],
            'divisão' => [5, 2, 'divisao', 'Resultado da divisão 2.5'],
        ];
    }

    #[Test]
    public function recusa_operacao_desconhecida(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Operação desconhecida: potencia.');

        $this->calculadora->calcular(2, 3, 'potencia');
    }
}
