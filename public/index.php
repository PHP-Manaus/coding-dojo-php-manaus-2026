<?php

declare(strict_types=1);

$autoload = dirname(__DIR__) . '/vendor/autoload.php';

if (!is_file($autoload)) {
    exit('Dependencias nao instaladas. Rode: make install');
}

require_once $autoload;

$entrada = Dojo\Web\EntradaFormulario::deParametros($_GET);

$resultados = [
    'soma' => null,
    'diferenca' => null,
    'produto' => null,
    'divisao' => null,
    'resto' => null,
];

$renderizador = new Dojo\Web\Renderizador(dirname(__DIR__) . '/web/templates');

echo $renderizador->renderizar('calculadora', [
    'entrada' => $entrada,
    'resultados' => $resultados,
    'operacoes' => Dojo\Web\Operacao::todas(),
    'caminhoLogo' => is_file(__DIR__ . '/img/php-manaus-logotipo.png') ? '/img/php-manaus-logotipo.png' : null,
]);
