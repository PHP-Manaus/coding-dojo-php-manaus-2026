<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="color-scheme" content="light dark">
    <title>Calculadora | Coding Dojo PHP Manaus 2026</title>
    <?php if ($caminhoLogo !== null): ?>
        <link rel="icon" type="image/png" sizes="200x200" href="<?= $this->escapar($caminhoLogo) ?>">
        <link rel="apple-touch-icon" href="<?= $this->escapar($caminhoLogo) ?>">
    <?php endif; ?>
    <link rel="stylesheet" href="/css/estilos.css">
</head>
<body>
    <a class="pular-para-conteudo" href="#conteudo">Pular para o conteúdo</a>

    <div class="pagina">
        <header class="cabecalho">
            <?php if ($caminhoLogo !== null): ?>
                <img class="cabecalho__logo" src="<?= $this->escapar($caminhoLogo) ?>" alt="Comunidade PHP Manaus" width="200" height="200">
            <?php endif; ?>
            <div class="cabecalho__texto">
                <p class="cabecalho__evento">Coding Dojo PHP Manaus 2026</p>
                <h1 class="cabecalho__titulo">Calculadora</h1>
            </div>
        </header>

        <main id="conteudo">
            <form class="formulario" method="get" action="/">
                <fieldset class="grupo-campos">
                    <legend class="grupo-campos__legenda">Valores</legend>

                    <div class="campo">
                        <label class="campo__rotulo" for="num1">Número 1</label>
                        <input
                            class="campo__entrada"
                            id="num1"
                            name="num1"
                            type="text"
                            inputmode="decimal"
                            autocomplete="off"
                            value="<?= $this->escapar($entrada->num1) ?>"
                            <?= $entrada->erroDe('num1') !== null ? 'aria-invalid="true" aria-describedby="erro-num1"' : '' ?>
                        >
                        <?php if ($entrada->erroDe('num1') !== null): ?>
                            <p class="campo__erro" id="erro-num1"><?= $this->escapar($entrada->erroDe('num1')) ?></p>
                        <?php endif; ?>
                    </div>

                    <div class="campo">
                        <label class="campo__rotulo" for="num2">Número 2</label>
                        <input
                            class="campo__entrada"
                            id="num2"
                            name="num2"
                            type="text"
                            inputmode="decimal"
                            autocomplete="off"
                            value="<?= $this->escapar($entrada->num2) ?>"
                            <?= $entrada->erroDe('num2') !== null ? 'aria-invalid="true" aria-describedby="erro-num2"' : '' ?>
                        >
                        <?php if ($entrada->erroDe('num2') !== null): ?>
                            <p class="campo__erro" id="erro-num2"><?= $this->escapar($entrada->erroDe('num2')) ?></p>
                        <?php endif; ?>
                    </div>

                    <button class="botao" type="submit">Calcular</button>
                </fieldset>
            </form>

            <section class="resultados" aria-labelledby="titulo-resultados">
                <h2 class="resultados__titulo" id="titulo-resultados">Resultados</h2>

                <dl class="resultados__grade">
                    <?php foreach ($operacoes as $operacao): ?>
                        <?php $resultado = $resultados[$operacao->chave] ?? null; ?>
                        <div class="cartao<?= $resultado === null ? ' cartao--vazio' : '' ?>">
                            <dt class="cartao__rotulo">
                                <?= $this->escapar($operacao->rotulo) ?>
                                <?php if ($entrada->estaValida()): ?>
                                    <span class="cartao__expressao">
                                        <?= $this->numero($entrada->numero1) ?>
                                        <?= $this->escapar($operacao->simbolo) ?>
                                        <?= $this->numero($entrada->numero2) ?>
                                    </span>
                                <?php endif; ?>
                            </dt>
                            <dd class="cartao__valor">
                                <?php if ($resultado !== null): ?>
                                    <?= $this->numero($resultado) ?>
                                <?php elseif ($entrada->estaValida()): ?>
                                    <span class="status-pendente">a implementar</span>
                                <?php else: ?>
                                    <span aria-hidden="true">–</span>
                                    <span class="apenas-leitor-de-tela">sem valor</span>
                                <?php endif; ?>
                            </dd>
                        </div>
                    <?php endforeach; ?>
                </dl>
            </section>
        </main>
    </div>
</body>
</html>
