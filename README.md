# Aplicação Bagda API Laravel

Aplicação para gerenciar itens de RPG que serão comprados pelos personagens dos jogadores.

Semelhante a um e-commerce para o jogo. Construido de forma a se encaixar em qualquer sistema de RPG.

Feita para ser consumida pela [SPA Bagda Angular](https://github.com/warcontent/bagda-angular).

Projeto produzido com Laravel v5.8.

## Getting Started

* Clone esse repositório: `git clone https://github.com/warcontent/bagda-laravel.git`.
* `cd seuprojeto` vá para a raiz do seu projeto.
* Execute `composer install --no-scripts` para instalar as dependências.
* Execute `cp .env.example .env` para criar o arquivo configuração.
* Execute `php artisan key:generate` para gerar a chave de criptografia.
* Execute `php artisan migrate --seed` para migrar o banco de dados.
* Pronto. :tada:
