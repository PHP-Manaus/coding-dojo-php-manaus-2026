# Coding Dojo PHP Manaus 2026

Esqueleto do dojo deste ano. A ideia é praticar TDD com PHPUnit em PHP puro, com tudo
rodando via Docker. Ninguém precisa instalar PHP na própria máquina.

## O desafio

> Crie uma calculadora que tenha quatro operações. Que receba dois valores e retorne a
> operação (soma, subtração, multiplicação, divisão).
> Recebe: `num1`, `num2`, `operacao` e devolve `"Resultado da soma 2"`

Extra: um script em PHP que recebe dois números do usuário via formulário `input` e
mostra a soma, diferença, produto, divisão do primeiro pelo segundo e o resto da divisão
do primeiro pelo segundo. Os campos se chamam `num1` e `num2`, e seus valores chegam em
`$num1` e `$num2` através do `$_GET`.

## Começando

```bash
make setup
make test-ambiente
```

Se o `make test-ambiente` passou, o ambiente está funcionando e o dojo pode começar.

Antes do primeiro teste do dojo, o `make test` mostra "No tests executed!" e não falha. É
o esperado: a suíte `Dojo` só tem os testes escritos durante o dojo.

O PHPUnit só encontra arquivos cujo nome termina em `Test.php`, como
`tests/CalculadoraTest.php`. Se já existe arquivo PHP em `tests/` (fora de `Ambiente/` e
`Web/`) e nenhum teste foi encontrado, o `make test` falha com "No tests executed!": confira
o nome do arquivo.

## Comandos

| Comando | O que faz |
| --- | --- |
| `make help` | Lista todos os comandos |
| `make setup` | Prepara o projeto do zero (imagem + dependências) |
| `make test` | Roda a suíte `Dojo` (`tests/`, menos `tests/Ambiente/` e `tests/Web/`) |
| `make test-ambiente` | Roda a suíte `Ambiente` (confere se o ambiente funciona) |
| `make test-web` | Roda a suíte `Web` (`tests/Web/`) |
| `make test-all` | Roda todas as suítes |
| `make test-filter FILTER=soma` | Roda só os testes que casam com o nome, em todas as suítes |
| `make coverage` | Suíte `Dojo` + relatório de cobertura no terminal |
| `make coverage-html` | Gera o relatório de cobertura em `coverage/` |
| `make watch` | Re-roda a suíte a cada alteração (precisa do `inotify-tools`) |
| `make up` | Sobe o servidor em http://localhost:8000 |
| `make down` | Derruba os containers |
| `make shell` | Abre um shell dentro do container |
| `make clean` | Remove `vendor/`, caches e cobertura |

Os alvos instalam as dependências sozinhos caso o `vendor/` não exista, então dá para ir
direto no `make test-ambiente` depois de clonar.

Para trocar a porta do servidor: `APP_PORT=8087 make up`.

O `make up` usa o servidor embutido do PHP, que é feito para desenvolvimento.

## Estrutura

```
.
├── compose.yaml         serviço app (PHP CLI + servidor embutido)
├── Dockerfile           PHP 8.4 + Composer + pcov
├── Makefile             atalhos para tudo
├── phpunit.xml          configuração do PHPUnit
├── public/              o que o navegador acessa
│   ├── css/estilos.css
│   ├── img/             logo da comunidade
│   └── index.php        junta a entrada, os resultados e o template
├── src/                 o desafio, namespace Dojo\
│   └── Calculadora.php
├── tests/               namespace Dojo\Tests\
│   ├── Ambiente/        teste de fumaça do ambiente (suíte Ambiente)
│   └── Web/             testes do código de apresentação (suíte Web)
└── web/                 código de apresentação, namespace Dojo\Web\
    ├── EntradaFormulario.php  lê e valida o que veio no $_GET
    ├── FormatadorNumero.php   exibe número no formato pt-BR
    ├── Operacao.php           rótulo e símbolo de cada operação
    ├── Renderizador.php       junta template com dados e escapa a saída
    └── templates/
        └── calculadora.php    só marcação, sem regra de negócio
```

O autoload é PSR-4: `Dojo\` aponta para `src/`, `Dojo\Web\` para `web/` e `Dojo\Tests\`
para `tests/`. Ao criar uma classe nova em `src/`, não precisa fazer mais nada, é só rodar
os testes.

## Organização do código

`src/` contém apenas o código do desafio. É onde a `Calculadora` deve ser implementada e é
o único diretório medido pelo relatório de cobertura.

`web/` contém o código de apresentação: leitura do formulário, formatação de número e
geração do HTML. Não deve conter regra de negócio.

Os testes ficam em três suítes do PHPUnit. A `Dojo` reúne tudo em `tests/`, menos
`tests/Ambiente/` e `tests/Web/`: é ela que o `make test`, o `make watch` e o
`make coverage` rodam, então só aparecem os testes escritos durante o dojo. A `Ambiente`
confere se Docker, Composer e autoload estão funcionando, e roda com `make test-ambiente`.
A `Web` cobre o código de apresentação, que já vem pronto, e roda com `make test-web`. O
`make test-all`, o `make test-filter` e o `composer test` rodam todas as suítes.

`public/index.php` recebe a requisição, usa as classes de `web/` para montar os dados da
página e é o ponto onde a `Calculadora` será chamada.

## A parte web

O frontend já está pronto. O que falta é o cálculo.

Em `public/index.php` existe o array `$resultados`, com as cinco chaves em `null`. Enquanto
uma chave estiver `null`, o cartão correspondente é exibido com a marcação "a implementar".
Ao preencher a chave com o valor devolvido pela `Calculadora`, o cartão passa a exibir o
resultado. Os números já chegam validados e convertidos em `$entrada->numero1` e
`$entrada->numero2`.

## O ritmo do dojo

1. Red: escreva um teste que falha, descrevendo o próximo comportamento desejado.
2. Green: escreva o código mais simples que faz o teste passar.
3. Refactor: melhore o código com a suíte verde te protegendo.
