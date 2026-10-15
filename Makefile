UID := $(shell id -u)
GID := $(shell id -g)
export UID
export GID

COMPOSE     := docker compose
COMPOSE_RUN := $(COMPOSE) run --rm app

.DEFAULT_GOAL := help
.PHONY: help setup build install test test-web test-all test-filter coverage coverage-html watch up down shell clean

help:
	@echo "Comandos disponiveis:"
	@echo "  make setup                    Prepara o projeto do zero (imagem + dependencias)"
	@echo "  make build                    Constroi a imagem Docker"
	@echo "  make install                  Instala as dependencias do Composer"
	@echo "  make test                     Roda a suite Dojo (tests/, menos tests/Web)"
	@echo "  make test-web                 Roda a suite Web (tests/Web)"
	@echo "  make test-all                 Roda todas as suites"
	@echo "  make test-filter FILTER=soma  Roda testes por nome, em todas as suites"
	@echo "  make coverage                 Roda a suite Dojo com relatorio de cobertura"
	@echo "  make coverage-html            Gera o relatorio de cobertura em coverage/"
	@echo "  make watch                    Re-roda a suite a cada alteracao"
	@echo "  make up                       Sobe o servidor web em http://localhost:8000"
	@echo "  make down                     Derruba os containers"
	@echo "  make shell                    Abre um shell dentro do container"
	@echo "  make clean                    Remove artefatos gerados"

setup: build install

build:
	$(COMPOSE) build

install: vendor/autoload.php

vendor/autoload.php: composer.json $(wildcard composer.lock)
	$(COMPOSE_RUN) composer install

test: vendor/autoload.php
	$(COMPOSE_RUN) vendor/bin/phpunit --testsuite Dojo

test-web: vendor/autoload.php
	$(COMPOSE_RUN) vendor/bin/phpunit --testsuite Web

test-all: vendor/autoload.php
	$(COMPOSE_RUN) vendor/bin/phpunit

test-filter: vendor/autoload.php
	$(COMPOSE_RUN) vendor/bin/phpunit --filter "$(FILTER)"

coverage: vendor/autoload.php
	$(COMPOSE_RUN) vendor/bin/phpunit --testsuite Dojo --coverage-text

coverage-html: vendor/autoload.php
	$(COMPOSE_RUN) vendor/bin/phpunit --testsuite Dojo --coverage-html coverage

watch: vendor/autoload.php
	@command -v inotifywait >/dev/null 2>&1 || { echo "instale inotify-tools para usar make watch"; exit 1; }
	@$(MAKE) --no-print-directory test || true
	@while inotifywait -qq -r -e modify,create,delete src tests; do \
		clear; $(MAKE) --no-print-directory test || true; \
	done

up: vendor/autoload.php
	$(COMPOSE) up

down:
	$(COMPOSE) down --remove-orphans

shell:
	$(COMPOSE_RUN) sh

clean:
	rm -rf vendor .phpunit.cache coverage
