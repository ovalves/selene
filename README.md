## Selene Framework

O Selene é uma micro-framework PHP.

Selene foi desenvolvido, para tornar mais simples as tarefas comuns utilizadas na maioria dos projetos da web, selene possui:

- Sistema de MVC
- Sistema de roteamento
- Sistema de injeção de dependência
- Gerenciamento de sessão
- Autenticação de usuário
- Query Builder para banco de dados Mysql e MongoDB.
- Sistema de template engine
- Sistema de Middleware
- Sistema de redirecionamento de usuário
- Gerenciamento do sistema de arquivos
- Gerenciamento de Logs

## Instalação

É recomendável que você use [Composer](https://getcomposer.org/) para instalar selene.

```bash
$ composer require ovalves/selene "dev-master@dev"
```

Isso instalará Selene e todas as suas dependências. Selene requer PHP 8.0 ou superior.

## Uso básico

Crie um arquivo index.php com o seguinte conteúdo:

```php
<?php

require 'vendor/autoload.php';

/*
|--------------------------------------------------------------------------
| Obtendo uma instância de Selene Framework
|--------------------------------------------------------------------------
|
| '/var/www/html/app/' é o mapeamento da raiz da nossa aplicação
*/
$app = Selene\App\Factory::create('/var/www/html/app/');

/*
|--------------------------------------------------------------------------
| Usando o roteador para registrar as rotas da sua aplicação
|--------------------------------------------------------------------------
| No caso abaixo, estamos criando um grupo nomeado 'auth'
|
| A criação de grupo de rotas serve para facilitar a utilização dos middlewares
*/
$app->route()->group('auth', function () use ($app) {

    /*
    |--------------------------------------------------------------------------
    | Neste caso, estamos adicionando o middleware de autentição
    |--------------------------------------------------------------------------
    | Esse middleware será executado em todas as rotas que pertencerem a esse grupo
    */
    $app->route()->middleware([new Selene\Middleware\Handler\Auth]);

    /*
    |--------------------------------------------------------------------------
    | Esta rota responde como um callable
    |--------------------------------------------------------------------------
    */
    $app->route()->get('/callable', function () use ($app) {
        $app->json('Hello World!!!');
    });

    /*
    |--------------------------------------------------------------------------
    | Mapeamento de método HTTP da request com a solicita~ HTTP de solicitação
    |--------------------------------------------------------------------------
    */
    $app->route()->get('/', 'HomeController@index');
    $app->route()->get('/show/{id}', 'HomeController@show');
    $app->route()->update('/show/{id}', 'HomeController@show');
    $app->route()->delete('/show/{id}', 'HomeController@show');
    $app->route()->post('/show', 'HomeController@login');
->run();
```
## Exemplos

Para mais exemplos, acesse https://github.com/ovalves/selene-skeleton.

## Licença

O Selene framework é licenciado usa a licença MIT license. Veja [License File](LICENSE) para maiores informações.
