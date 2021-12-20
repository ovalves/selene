<?php
/**
 * @copyright   2019 - Selene
 * @author      Vinicius Oliveira <vinicius_o.a@live.com>
 * @category    Micro Framework
 * @since       2019-02-09
 */

namespace Selene;

use Psr\Container\ContainerInterface;
use Selene\Auth\Auth;
use Selene\Config\ConfigConstant;
use Selene\Container\Container;
use Selene\Container\ServiceContainer;
use Selene\Loader\AppLoader;
use Selene\Middleware\Middleware;
use Selene\Redirect\Redirect;
use Selene\Render\View;
use Selene\Request\Request;
use Selene\Routes\Route;
use Selene\Session\Session;

final class App
{
    /**
     * Gurada o objeto usado como Container no framework.
     *
     * @var ContainerInterface
     */
    private $container = null;

    /**
     * Define o root path do app.
     *
     * @var string
     */
    public static $rootPath;

    /**
     * Constructor.
     *
     * @return void
     */
    public function __construct(string $root = '')
    {
        self::$rootPath = $root;
        $this->container = new Container();
        $this->make();
    }

    /**
     * Retorna o objeto do roteador.
     *
     * @return Selene\Routes\Route
     */
    public function route(): Route
    {
        return $this->container->get(ServiceContainer::ROUTE);
    }

    /**
     * Retorna o objeto do middleware.
     *
     * @return Selene\Middleware\Middleware
     */
    public function middleware(): Middleware
    {
        return $this->container->get(ServiceContainer::MIDDLEWARE);
    }

    /**
     * Retorna o objeto da request.
     *
     * @return Selene\Request\Request
     */
    public function request(): Request
    {
        return $this->container->get(ServiceContainer::REQUEST);
    }

    /**
     * Retorna o objeto da view.
     *
     * @return Selene\Render\View
     */
    public function view(): View
    {
        return $this->container->get(ServiceContainer::VIEW);
    }

    /**
     * Retorna o objeto da sessão.
     *
     * @return Selene\Session\Session
     */
    public function session(): Session
    {
        return $this->container->get(ServiceContainer::SESSION);
    }

    /**
     * Retorna o objeto de redirect.
     */
    public function redirect(): Redirect
    {
        return $this->container->get(ServiceContainer::REDIRECT);
    }

    /**
     * Retorna o objeto de autenticação.
     *
     * @return Selene\Auth\Auth
     */
    public function auth(): Auth
    {
        return $this->container->get(ServiceContainer::AUTH);
    }

    /**
     * Retorna a resposta como json.
     *
     * @param  mixed $data
     * @return json
     */
    public function json($data)
    {
        echo \json_encode($data);
    }

    /**
     * Retorna objeto de Service Container.
     */
    public function container(): ContainerInterface
    {
        return $this->container;
    }

    public static function rootPath()
    {
        return self::$rootPath;
    }

    /**
     * Instancia os componentes básicos do framework.
     */
    private function make(): void
    {
        if (is_null($this->container)) {
            throw new Exception('Uma instância do ContainerInterface é requerida');
        }

        $this->init();
        $this->makeRequest();
        $this->makeMiddleware();
        $this->makeRouter();
        $this->makeSession();
        $this->makeRedirect();
        $this->makeAuth();
        $this->makeView();
        $this->makeErrorhandler();
        $this->injectAppRootPathOnView();
        $this->injectViewOnRouterDispatcher();
    }

    /**
     * Init and loads all app main folders.
     */
    private function init(): void
    {
        $loader = new AppLoader();

        $loader->addDirectory(\Selene\App::rootPath() . 'src/Controllers');
        $loader->addDirectory(\Selene\App::rootPath() . 'src/Models');
        $loader->addDirectory(\Selene\App::rootPath() . 'src/Gateway');
        $loader->addDirectory(\Selene\App::rootPath() . 'src/Config');
        $loader->load();

        $this->container->setPrefix(ServiceContainer::APPLICATION_CONFIG)->set(
            \Selene\Config\ApplicationConfig::class
        );
    }

    /**
     * Criando o container da request e suas dependencias.
     */
    private function makeRequest(): void
    {
        $this->container->setPrefix(ServiceContainer::REQUEST)->set(
            \Selene\Request\Request::class,
            [
                $_GET,
                $_POST,
                $_REQUEST,
                $_SERVER,
            ]
        );
    }

    /**
     * Criando o container de middlaware e suas dependencias.
     */
    private function makeMiddleware(): void
    {
        $this->container->setPrefix(ServiceContainer::MIDDLEWARE)->set(
            \Selene\Middleware\Middleware::class
        );
    }

    /**
     * Criando o container da view e suas dependencias.
     */
    private function makeView(): void
    {
        $this->container->setPrefix(ServiceContainer::VIEW)->set(
            \Selene\Render\View::class,
            [
                \Selene\Render\Compiler\PluginCompiler::class,
                \Selene\Render\Compiler\TemplateCompiler::class,
            ]
        );
    }

    /**
     * Criando o container de rota e suas dependencias.
     */
    private function makeRouter(): void
    {
        $this->container->setPrefix(ServiceContainer::ROUTE)->set(
            \Selene\Routes\Route::class,
            [
                $this->container->get(ServiceContainer::REQUEST),
                $this->container->get(ServiceContainer::MIDDLEWARE),
            ]
        );
    }

    /**
     * Criando o container de rota e suas dependencias.
     */
    public function emit($request): mixed
    {
        return $this->container->get(ServiceContainer::ROUTE)->setRequest($request)->run();
    }

    /**
     * Criando o objeto da sessão e suas dependencias.
     *
     * @return Selene\Session\Session
     */
    private function makeSession(): void
    {
        $config = $this->container()->get(ServiceContainer::APPLICATION_CONFIG);

        if ($config->getConfig(ConfigConstant::ENABLE_SESSION_CONTAINER)) {
            $this->container->setPrefix(ServiceContainer::SESSION)->set(
                \Selene\Session\Session::class
            );
        }
    }

    /**
     * Criando o objeto de redirect e suas dependencias.
     */
    private function makeRedirect(): void
    {
        $config = $this->container()->get(ServiceContainer::APPLICATION_CONFIG);

        if ($config->getConfig(ConfigConstant::ENABLE_SESSION_CONTAINER)) {
            $this->container->setPrefix(ServiceContainer::REDIRECT)->set(
                \Selene\Redirect\Redirect::class,
                [
                    $this->container->get(ServiceContainer::SESSION),
                ]
            );
        }
    }

    /**
     * Criando o objeto de autenticação e suas dependencias.
     *
     * @return Selene\Auth\Auth
     */
    private function makeAuth(): void
    {
        $config = $this->container()->get(ServiceContainer::APPLICATION_CONFIG);

        if ($config->getConfig(ConfigConstant::ENABLE_AUTH_CONTAINER)) {
            $this->container->setPrefix(ServiceContainer::AUTH)->set(
                \Selene\Auth\Auth::class,
                [
                    $this->container->get(ServiceContainer::SESSION),
                ]
            );
        }
    }

    /**
     * Creates the error handler.
     */
    private function makeErrorHandler(): void
    {
        $whoops = new \Whoops\Run();
        $whoops->prependHandler(new \Whoops\Handler\PrettyPageHandler());
        $whoops->register();
    }

    /**
     * Seta o path root da aplicação.
     */
    private function injectAppRootPathOnView(): void
    {
        $request = $this->container->get(ServiceContainer::REQUEST);
        $view = $this->container->get(ServiceContainer::VIEW);
        $view->setRootPath($request->getDocumentRoot());
    }

    /**
     * Injeta a view no roteador.
     */
    private function injectViewOnRouterDispatcher(): void
    {
        $this->container->get(ServiceContainer::ROUTE)->injectViewOnRouterDispatcher(
            $this->container->get(ServiceContainer::VIEW)
        );
    }
}
