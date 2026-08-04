<?php

declare(strict_types=1);

namespace Dojo\Web;

use RuntimeException;

final class Renderizador
{
    public function __construct(private readonly string $diretorio)
    {
    }

    public function renderizar(string $template, array $dados = []): string
    {
        $arquivoDoTemplate = $this->diretorio . '/' . $template . '.php';

        if (!is_file($arquivoDoTemplate)) {
            throw new RuntimeException(sprintf('Template não encontrado: %s', $template));
        }

        extract($dados, EXTR_SKIP);

        ob_start();

        require $arquivoDoTemplate;

        return (string) ob_get_clean();
    }

    public function escapar(string $valor): string
    {
        return htmlspecialchars($valor, ENT_QUOTES, 'UTF-8');
    }

    public function numero(int|float $numero): string
    {
        return $this->escapar(FormatadorNumero::formatar($numero));
    }
}
