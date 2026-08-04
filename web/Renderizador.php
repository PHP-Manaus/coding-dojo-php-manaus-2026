<?php

declare(strict_types=1);

namespace Dojo\Web;

use RuntimeException;
use Throwable;

final class Renderizador
{
    public function __construct(private readonly string $diretorio)
    {
    }

    public function renderizar(string $template, array $dados = []): string
    {
        $arquivo = $this->diretorio . '/' . $template . '.php';

        if (!is_file($arquivo)) {
            throw new RuntimeException(sprintf('Template não encontrado: %s', $template));
        }

        return $this->incluir($arquivo, $dados);
    }

    public function escapar(string $valor): string
    {
        return htmlspecialchars($valor, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }

    public function numero(int|float $numero): string
    {
        return $this->escapar(FormatadorNumero::formatar($numero));
    }

    public function valor(int|float|string $valor): string
    {
        return is_string($valor) ? $this->escapar($valor) : $this->numero($valor);
    }

    private function incluir(string $__arquivo, array $__dados): string
    {
        extract($__dados, EXTR_SKIP);

        ob_start();

        try {
            require $__arquivo;

            return (string) ob_get_clean();
        } catch (Throwable $erro) {
            ob_end_clean();

            throw $erro;
        }
    }
}
