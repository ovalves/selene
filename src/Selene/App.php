<?php
/**
 * @copyright   2019 - Selene
 * @author      Vinicius Oliveira <vinicius_o.a@live.com>
 * @category    Micro Framework
 * @since       2019-02-09
 */

namespace Selene;

use Psr\Container\ContainerInterface;
use Selene\Loader\AppLoader;
use Selene\Routes\Route;
use Selene\Request\Request;
use Selene\Middleware\Middleware;
use Selene\Render\View;
use Selene\Session\Session;
use Selene\Auth\Auth;
use Selene\Container\Container;

/**
 * Bootstrap do framework
 */
final class App
{
    /**
     * Define o container de rotas
     */
    const ROUTE = 'router';

    /**
     * Define o container de middleware
     */
    const MIDDLEWARE = 'middleware';

    /**
     * Define o container de request
     */
    const REQUEST = 'request';

    /**
     * Define o container de autenticação
     */
    const AUTH = 'auth';

    /**
     * Define o container da sessão
     */
    const SESSION = 'session';

    /**
     * Define  container da view
     */
    const VIEW = 'view';

    /**
     * Gurada o objeto usado como Container no framework
     *
     * @var ContainerInterface
     */
    protected $container = null;

    protected $booted = false;

    /**
     * Constructor
     *
     * @param ContainerInterface $container
     * @return void
     */
    public function __construct()
    {
        if ($this->isBooted()) {
            return;
        }

        $this->container = new Container;
        $this->make();
        $this->booted();
    }

    /**
     * impede que a classe seja clonada
     */
    final protected function __clone()
    {

    }

    /**
     * impede que a classe seja reconstruida
     */
    final protected function __wakeup()
    {

    }

    /**
     * Retorna o objeto do roteador
     *
     * @return Selene\Routes\Route
     */
    public function route() : Route
    {
        return $this->container->get(self::ROUTE);
    }

    /**
     * Retorna o objeto do middleware
     *
     * @return Selene\Middleware\Middleware
     */
    public function middleware() : MiddlewareInterface
    {
        return $this->container->get(self::MIDDLEWARE);
    }

    /**
     * Retorna o objeto da request
     *
     * @return Selene\Request\Request
     */
    public function request() : Request
    {
        return $this->container->get(self::REQUEST);
    }

    /**
     * Retorna o objeto da view
     *
     * @return Selene\Request\Request
     */
    public function view() : View
    {
        return $this->container->get(self::VIEW);
    }

    /**
     * Retorna o objeto da sessão
     *
     * @return Selene\Session\Session
     */
    public function session() : Session
    {
        return $this->container->get(self::SESSION);
    }

    /**
     * Retorna o objeto de autenticação
     *
     * @return Selene\Auth\Auth
     */
    public function auth() : Auth
    {
        return $this->container->get(self::AUTH);
    }

    /**
     * Retorna a resposta como json
     *
     * @param mixed $data
     * @return json
     */
    public function json($data)
    {
        echo \json_encode($data);
    }

    protected function booted() : void
    {
        $this->booted = true;
    }

    protected function isBooted() : bool
    {
        return (bool) $this->booted === true;
    }

    /**
     * Instancia os componentes básicos do framework
     *
     * @return void
     */
    protected function make() : void
    {
        if (is_null($this->container)) {
            throw new Exception("Uma instância do ContainerInterface é requerida");
        }

        $this->init();
        $this->makeRequest();
        $this->makeMiddleware();
        $this->makeRouter();
        $this->makeSession();
        $this->makeAuth();
        $this->makeView();
        $this->makeErrorhandler();
        $this->injectAppRootPathOnView();
        $this->injectViewOnRouterDispatcher();
    }

    /**
     * Init and loads all app main folders
     *
     * @return void
     */
    protected function init() : void
    {
        $loader = new AppLoader;

        $loader->addDirectory('App/Controllers');
        $loader->addDirectory('App/Models');
        $loader->addDirectory('App/Gateway');
        $loader->addDirectory('App/Config');
        $loader->load();

        /**
         * Loads configuration file
         */
        $configuration = require 'App/Config/app.php';
        $this->container->setConfiguration($configuration);
    }

    /**
     * Criando o container da request e suas dependencias
     *
     * @return void
     */
    protected function makeRequest() : void
    {
        $this->container->setPrefix(self::REQUEST)->set(
            \Selene\Request\Request::class,
            [
                $_GET,
                $_POST,
                $_REQUEST,
                $_SERVER
            ]
        );
    }

    /**
     * Criando o container de middlaware e suas dependencias
     */
    protected function makeMiddleware() : void
    {
        $this->container->setPrefix(self::MIDDLEWARE)->set(
            \Selene\Middleware\Middleware::class
        );
    }

    /**
     * Criando o container da view e suas dependencias
     *
     * @return void
     */
    protected function makeView() : void
    {
        $this->container->setPrefix(self::VIEW)->set(
            \Selene\Render\View::class,
            [
                \Selene\Render\Compiler\PluginCompiler::class,
                \Selene\Render\Compiler\TemplateCompiler::class
            ]
        );
    }

    /**
     * Criando o container de rota e suas dependencias
     */
    protected function makeRouter() : void
    {
        $this->container->setPrefix(self::ROUTE)->set(
            \Selene\Routes\Route::class,
            [
                $this->container->get(self::REQUEST),
                $this->container->get(self::MIDDLEWARE)
            ]
        );
    }

    /**
     * Criando o objeto da sessão e suas dependencias
     *
     * @return Selene\Session\Session
     */
    protected function makeSession() : void
    {
        if (!session_id()) {
            session_start();
        }

        $this->container->setPrefix(self::SESSION)->set(
            \Selene\Session\Session::class
        );
    }

    /**
     * Criando o objeto de autenticação e suas dependencias
     *
     * @return Selene\Auth\Auth
     */
    protected function makeAuth() : void
    {
        $this->container->setPrefix(self::AUTH)->set(
            \Selene\Auth\Auth::class,
            [
                $this->container->get(self::SESSION)
            ]
        );
    }

    /**
     * Creates the error handler
     *
     * @return void
     */
    protected function makeErrorHandler() : void
    {
        $whoops = new \Whoops\Run;
        $whoops->prependHandler(new \Whoops\Handler\PrettyPageHandler);
        $whoops->register();
    }

    /**
     * Seta o path root da aplicação
     *
     * @return void
     */
    protected function injectAppRootPathOnView() : void
    {
        $request = $this->container->get(self::REQUEST);
        $view    = $this->container->get(self::VIEW);
        $view->setRootPath($request->getDocumentRoot());
    }

    /**
     * Injeta a view no roteador
     *
     * @return void
     */
    protected function injectViewOnRouterDispatcher() : void
    {
        $this->container->get(self::ROUTE)->injectViewOnRouterDispatcher(
            $this->container->get(self::VIEW)
        );
    }
}
