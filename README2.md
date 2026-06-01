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

## URLs locais

- Aplicacao: `http://ateliware.localhost`
- Batalha: `http://ateliware.localhost/battle`
- Mailpit: `http://mailpit.localhost`

As URLs seguem o `APP_URL` configurado no `.env.example` e o roteamento local do ambiente Sail/Traefik.

## Funcionalidades

### Pagina inicial

A rota `/` renderiza a pagina `Home` via Inertia. Ela apresenta o projeto, link para a batalha, link para o Mailpit, tecnologias usadas e informacoes locais de banco de dados.

Arquivos principais:

- `routes/web.php`
- `resources/js/Pages/Home.vue`
- `resources/js/Components/Home/*`

### Batalha Pokemon

A rota `/battle` renderiza a pagina `Battle`, com dois cards de busca. Cada card permite digitar ou selecionar um Pokemon e buscar seus dados.

Fluxo da tela:

1. O autocomplete consulta `/battle/names?query=...`.
2. O backend busca sugestoes na tabela `pokemon`, filtrando por prefixo e retornando ate 10 nomes.
3. Ao selecionar ou buscar um nome, o frontend chama `/battle/pokemon?name=...`.
4. O backend consulta a PokéAPI em tempo real e retorna nome, HP e imagem.
5. A tela compara os HPs e mostra o resultado em ordem decrescente.

Arquivos principais:

- `packages/pokebattle/routes/web.php`
- `packages/pokebattle/src/Http/Controllers/BattleController.php`
- `packages/pokebattle/resources/js/Pages/Battle.vue`
- `packages/pokebattle/resources/js/Components/PokemonCard.vue`

### Sincronizacao de nomes da PokéAPI

O pacote `ateliware/pokeapi` fornece o comando:

```bash
vendor/bin/sail artisan pokeapi:names
```

Esse comando percorre as paginas do endpoint `/pokemon` da PokéAPI, salva ou atualiza os nomes na tabela `pokemon` e remove registros locais que nao vierem mais da API.

Arquivos principais:

- `packages/pokeapi/src/Commands/PokeApiNamesCommand.php`
- `packages/pokeapi/src/Clients/PokeApiClient.php`
- `packages/pokeapi/src/Models/Pokemon.php`
- `packages/pokeapi/database/migrations/2026_05_30_025816_create_pokemon_table.php`

## Arquitetura

O projeto e uma aplicacao Laravel principal com dois packages locais carregados via Composer path repositories.

### Aplicacao principal

Responsavel pelo bootstrap Laravel, Inertia/Vue, Vite, pagina inicial, configuracoes de ambiente e orquestracao dos packages.

### Package `ateliware/pokeapi`

Encapsula a integracao com a PokéAPI.

Responsabilidades:

- Configurar a URL base da PokéAPI via `config('pokeapi.base_url')`.
- Registrar migrations e comando Artisan.
- Fornecer `PokeApiClient` para consumo HTTP.
- Representar dados com DTOs (`PokemonData` e `PokemonHitPoint`).
- Persistir nomes e URLs na model `Pokemon`.

Configuracao:

```php
'base_url' => env('POKEAPI_BASE_URL', 'https://pokeapi.co/api/v2'),
```

### Package `ateliware/pokebattle`

Encapsula a funcionalidade de batalha.

Responsabilidades:

- Registrar rotas de batalha.
- Renderizar a pagina Inertia `Battle`.
- Expor endpoints JSON para sugestoes e detalhes do Pokemon.
- Integrar a UI Vue do package ao resolver de paginas do Vite/Inertia.

## Rotas

| Metodo | URI | Nome | Descricao |
| --- | --- | --- | --- |
| GET | `/` | - | Pagina inicial do projeto |
| GET | `/battle` | `battle.index` | Tela da batalha |
| GET | `/battle/names` | `battle.names` | Sugestoes de nomes a partir da tabela `pokemon` |
| GET | `/battle/pokemon` | `battle.pokemon` | Dados de HP e imagem consultados na PokéAPI |

## Banco de dados

O ambiente usa PostgreSQL. As credenciais padrao do `.env.example` sao:

```dotenv
DB_CONNECTION=pgsql
DB_HOST=pgsql
DB_PORT=5432
DB_DATABASE=laravel
DB_USERNAME=sail
DB_PASSWORD=password
```

Tabela de dominio:

| Tabela | Campos principais | Uso |
| --- | --- | --- |
| `pokemon` | `id`, `name`, `url`, timestamps | Cache local de nomes e URLs retornados pela PokéAPI para alimentar o autocomplete |

O projeto tambem usa tabelas padrao do Laravel para users, cache, sessions, jobs e migrations.

## Instalacao e execucao

1. Copie o arquivo de ambiente:

```bash
cp .env.example .env
```

2. Suba os containers:

```bash
vendor/bin/sail up -d
```

3. Instale dependencias PHP:

```bash
vendor/bin/sail composer install
```

4. Instale dependencias JavaScript:

```bash
vendor/bin/sail npm install
```

5. Gere a chave da aplicacao:

```bash
vendor/bin/sail artisan key:generate
```

6. Rode as migrations:

```bash
vendor/bin/sail artisan migrate
```

7. Sincronize os nomes dos Pokemons:

```bash
vendor/bin/sail artisan pokeapi:names
```

8. Rode o frontend em desenvolvimento:

```bash
vendor/bin/sail npm run dev
```

Para gerar os assets de producao:

```bash
vendor/bin/sail npm run build
```

## Comandos uteis

```bash
# Subir ambiente
vendor/bin/sail up -d

# Parar ambiente
vendor/bin/sail stop

# Listar rotas da aplicacao
vendor/bin/sail artisan route:list --except-vendor

# Sincronizar nomes da PokéAPI
vendor/bin/sail artisan pokeapi:names

# Rodar formatter PHP
vendor/bin/sail bin pint --dirty --format agent

# Rodar testes
vendor/bin/sail artisan test --compact
```

## Testes

O projeto usa PHPUnit. O `phpunit.xml` inclui os testes da aplicacao principal e dos packages locais:

- `tests/Feature`
- `tests/Unit`
- `packages/pokeapi/tests`
- `packages/pokebattle/tests`

Cobertura existente:

- Cliente da PokéAPI paginando resultados.
- Cliente buscando HP e imagem por nome.
- Tratamento de erro quando a PokéAPI retorna 404.
- DTOs de dados de Pokemon.
- Model `Pokemon`.
- Comando `pokeapi:names`, incluindo insert/update e remocao de registros antigos.
- Endpoint de sugestoes `/battle/names`.
- Renderizacao da pagina `/battle`.
- Endpoint `/battle/pokemon`.

## Historico Git resumido

O historico mostra uma evolucao incremental:

1. `59fdff4` - configuracao inicial do projeto Laravel.
2. `5d4750a` - adicao do Laravel Boost.
3. `78fc342` - configuracao de Inertia + Vue.
4. `d705acb` - configuracao de componentes visuais.
5. `c40d722` - ajuste de namespace/resolucao no Vite.
6. `bc2d5dd` - criacao da pagina inicial.
7. `9e9a173` - criacao do package `ateliware/pokeapi`, com cliente, DTOs, model, migration, comando e testes.
8. `bd93b0a` - criacao do package `ateliware/pokebattle`.
9. `b41d77c` - adicao da URL da API em configuracao.
10. `2a86644` - inclusao dos testes dos packages na suite global.
11. `7e5b38e` - implementacao da tela de batalha, controller, endpoints e testes.
12. `9d8d674` - remocao de testes de exemplo.
13. `39df577` - teste de renderizacao da batalha.
14. `8b13b44` - ajuste de configuracao no PHPUnit.

## Observacoes de manutencao

- A lista de sugestoes depende da tabela `pokemon`; apos instalar ou recriar o banco, rode `pokeapi:names`.
- A busca de HP e imagem nao usa cache local; ela consulta a PokéAPI no momento da busca.
- O frontend resolve paginas tanto em `resources/js/Pages` quanto em `packages/pokebattle/resources/js/Pages`.
- Os packages locais sao registrados no Composer via `repositories` do tipo `path` com symlink.
- O `public/favicon.svg` usa um icone inspirado em pokeball.
