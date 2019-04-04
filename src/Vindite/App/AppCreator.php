<?php
/**
 * @copyright   2019 - Vindite
 * @author      Vinicius Oliveira <vinicius_o.a@live.com>
 * @category    Micro Framework
 * @since       2019-02-17
 */

namespace Vindite\App;

use Psr\Container\ContainerInterface;

/**
 * Bootstrap das classes do framework
 */
final class AppCreator
{
    /**
     * Gurada o objeto usado como Container no framework
     *
     * @var ContainerInterface
     */
    protected static $container = null;

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
     * Define  container da view
     */
    const VIEW = 'view';

    /**
     * Constructor
     *
     * @param ContainerInterface $container
     * @return void
     */
    public function __construct(ContainerInterface $container)
    {
        self::$container = $container;
    }

    /**
     * Retorna o objeto do container
     *
     * @param string $prefix
     * @return ContainerInterface
     */
    public static function container(string $prefix = null) : ContainerInterface
    {
        if (is_null($prefix)) {
            return self::$container;
        }

        self::$container->setPrefix($prefix);
        return self::$container;
    }

    /**
     * Instancia os componentes básicos do framework
     *
     * @return void
     */
    public function make() : void
    {
        if (is_null(self::$container)) {
            throw new Exception("Uma instância do ContainerInterface é requerida");
        }

        $this->makeRequest();
        $this->makeMiddleware();
        $this->makeRouter();
        $this->makeView();
        $this->injectAppRootPathOnView();
        $this->injectViewOnRouterDispatcher();
    }

    /**
     * Criando o container da request
     *
     * @return void
     */
    private function makeRequest() : void
    {
        /**
         * Criando o container da request e suas dependencias
         */
        self::container(self::REQUEST)->set(
            \Vindite\Request\Request::class,
            [
                $_POST,
                $_GET,
                $_REQUEST,
                $_SERVER
            ]
        );
    }

    /**
     * Criando o container de middlaware e suas dependencias
     */
    private function makeMiddleware() : void
    {
        self::container(self::MIDDLEWARE)->set(
            \Vindite\Middleware\Middleware::class
        );
    }

    /**
     * Criando o container da view
     *
     * @return void
     */
    private function makeView() : void
    {
        self::container(self::VIEW)->set(
            \Vindite\Render\View::class,
            [
                \Vindite\Render\Compiler\PluginCompiler::class,
                \Vindite\Render\Compiler\TemplateCompiler::class
            ]
        );
    }

    /**
     * Criando o container de rota e suas dependencias
     */
    private function makeRouter() : void
    {
        self::container(self::ROUTE)->set(
            \Vindite\Routes\Route::class,
            [
                self::container()->get(self::REQUEST),
                self::container()->get(self::MIDDLEWARE)
            ]
        );
    }

    /**
     * Seta o path root da aplicação
     *
     * @return void
     */
    private function injectAppRootPathOnView() : void
    {
        $request = self::container()->get(self::REQUEST);
        $view = self::container()->get(self::VIEW);
        $view->setRootPath($request->getDocumentRoot());
    }

    /**
     * Injeta a view no roteador
     *
     * @return void
     */
    private function injectViewOnRouterDispatcher() : void
    {
        self::container()->get(self::ROUTE)->injectViewOnRouterDispatcher(
            self::container()->get(self::VIEW)
        );
    }
}
