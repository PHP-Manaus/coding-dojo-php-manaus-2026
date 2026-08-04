<?php

declare(strict_types=1);

namespace Dojo\Tests\Web;

use Dojo\Web\EntradaFormulario;
use Dojo\Web\FormatadorNumero;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class FormatadorNumeroTest extends TestCase
{
    #[Test]
    #[DataProvider('numerosFormatados')]
    public function formata_no_padrao_brasileiro(int|float $numero, string $esperado): void
    {
        $this->assertSame($esperado, FormatadorNumero::formatar($numero));
    }

    public static function numerosFormatados(): array
    {
        return [
            'inteiro' => [42, '42'],
            'float sem casas' => [42.0, '42'],
            'decimal' => [1.5, '1,5'],
            'negativo' => [-7.25, '-7,25'],
            'milhar' => [1234.5, '1.234,5'],
            'zero' => [0, '0'],
        ];
    }

    #[Test]
    #[DataProvider('divisoesPorZero')]
    public function divisao_por_zero_vira_texto_legivel(float $numero, string $esperado): void
    {
        $this->assertSame($esperado, FormatadorNumero::formatar($numero));
    }

    public static function divisoesPorZero(): array
    {
        return [
            'infinito positivo' => [INF, 'infinito'],
            'infinito negativo' => [-INF, '-infinito'],
            'indeterminado' => [NAN, 'indefinido'],
        ];
    }

    #[Test]
    public function valor_pequeno_nao_e_exibido_como_zero(): void
    {
        $formatado = FormatadorNumero::formatar(1e-7);

        $this->assertNotSame('0', $formatado);
        $this->assertStringContainsString('e-', $formatado);
    }

    #[Test]
    #[DataProvider('numerosParaIdaEVolta')]
    public function o_numero_formatado_e_aceito_de_volta_no_formulario(float $numero): void
    {
        $formatado = FormatadorNumero::formatar($numero);

        $entrada = EntradaFormulario::deParametros(['num1' => $formatado, 'num2' => '1']);

        $this->assertTrue($entrada->estaValida(), sprintf('"%s" foi recusado na volta', $formatado));
        $this->assertSame($numero, $entrada->numero1);
    }

    public static function numerosParaIdaEVolta(): array
    {
        return [
            'decimal' => [1.5],
            'milhar com decimal' => [1234.5],
            'milhar sem decimal' => [1234567.0],
            'negativo' => [-7.25],
            'valor muito pequeno' => [1e-7],
        ];
    }
}
