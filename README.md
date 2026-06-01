# Pokemon Battle - Ateliware

Aplicacao Laravel desenvolvida para a Ateliware.

O projeto permite comparar Pokemons usando dados da PokéAPI: o usuario escolhe os nomes, a aplicacao compara o HP de cada um e exibe o resultado ordenando o maior HP como vencedor. Em caso de HP igual, a interface indica empate.

## Stack

- PHP 8.5
- Laravel 13.12
- Laravel Sail
- PostgreSQL
- Inertia.js 3
- Vue 3
- Vite 8
- Tailwind CSS 4
- PrimeVue 4
- PHPUnit 12
- Laravel Boost

## traefik
Os endereços do Traefik estão configurados para a aplicação e para o mailpit. 

[Aplicação - http://ateliware.localhost/](http://ateliware.localhost/)

[Mailpit - http://mailpit.localhost](http://mailpit.localhost)

## Organização
Isolei as funcionalidades em 2 packages:
- packages/pokeapi - focado em API
- packages/pokebattle - focado no uso

A ideia é manter os componentes isoados e reusáveis entre projetos. 

## Integração a API:
Usei configuração para manter a URL base para a integração principal. 

Adicionalmente fiz integração com a API que captura os nomes dos pokemons. 
Ela é usada para autocomplete do nome na tela do usuário. 
Não é requerido que seja utilizada. Quando um pokemon é pesquisado, é adicionado automaticamente a tabela de pokemons, e, assim, usado como autocomplete na próxima seleção.

Para comodidade, adicionei o comando `sail artisan pokeapi:names` que preenche a tabela com os dados de uma API alternativa que retona os nomes.

## Sobre a batalha
A batalha eu fiz podendo ser 2 ou mais competidores. 
A quantidade de pokemons eu fiz configurável dentro do painel de controle. 

## Sobre autenticação
A autenticação fica a cargo do jetstream com verificação de e-mail.

Adicionei o mailpit para facilitar a verificação de email. 

## Testes
O projeto usa PHPUnit. O `phpunit.xml` inclui os testes da aplicacao principal e dos packages locais:

## Instalacao e execucao

| cmd   | Descrição |
|--------|--------|
| `cp .env.example .env`    | Copie o arquivo de ambiente     |
| `vendor/bin/sail up -d` | Suba os containers     |
| `vendor/bin/sail composer install`  | Instale dependencias PHP     |
| `vendor/bin/sail npm install` | Instale dependencias JavaScript |
| `vendor/bin/sail artisan key:generate` | Gere a chave da aplicacao |
| `vendor/bin/sail artisan migrate ` | Rode as migrations |
| `vendor/bin/sail artisan pokeapi:names` |Sincronize os nomes dos Pokemons |
| `vendor/bin/sail npm run dev` | Rode o frontend em desenvolvimento |