<?php

declare(strict_types=1);

namespace Dojo\Tests\Web;

use Dojo\Web\EntradaFormulario;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class EntradaFormularioTest extends TestCase
{
    #[Test]
    public function sem_parametros_a_entrada_nao_foi_enviada(): void
    {
        $entrada = EntradaFormulario::deParametros([]);

        $this->assertFalse($entrada->foiEnviada());
        $this->assertSame([], $entrada->erros);
    }

    #[Test]
    public function formulario_submetido_em_branco_acusa_os_dois_campos(): void
    {
        $entrada = EntradaFormulario::deParametros(['num1' => '', 'num2' => '']);

        $this->assertTrue($entrada->foiEnviada());
        $this->assertFalse($entrada->estaValida());
        $this->assertSame('Informe um número.', $entrada->erroDe('num1'));
        $this->assertSame('Informe um número.', $entrada->erroDe('num2'));
    }

    #[Test]
    public function texto_nao_numerico_e_recusado(): void
    {
        $entrada = EntradaFormulario::deParametros(['num1' => 'abc', 'num2' => '2']);

        $this->assertSame('Informe um número válido.', $entrada->erroDe('num1'));
        $this->assertNull($entrada->erroDe('num2'));
    }

    #[Test]
    public function parametro_que_nao_e_string_nao_quebra(): void
    {
        $entrada = EntradaFormulario::deParametros(['num1' => ['5'], 'num2' => '2']);

        $this->assertSame('', $entrada->num1);
        $this->assertNull($entrada->numero1);
        $this->assertFalse($entrada->estaValida());
    }

    #[Test]
    #[DataProvider('numerosAceitos')]
    public function converte_o_texto_digitado_em_numero(string $digitado, float $esperado): void
    {
        $entrada = EntradaFormulario::deParametros(['num1' => $digitado, 'num2' => '1']);

        $this->assertTrue($entrada->estaValida());
        $this->assertSame($esperado, $entrada->numero1);
    }

    public static function numerosAceitos(): array
    {
        return [
            'inteiro' => ['1234', 1234.0],
            'decimal com ponto' => ['1.5', 1.5],
            'decimal com vírgula' => ['1,5', 1.5],
            'negativo' => ['-7,25', -7.25],
            'milhar com decimal' => ['1.234,5', 1234.5],
            'milhar sem decimal' => ['1.234.567', 1234567.0],
            'com espaços em volta' => ['  42  ', 42.0],
        ];
    }
}
