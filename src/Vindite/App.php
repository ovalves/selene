<?php
/**
 * @copyright   2019 - Vindite
 * @author      Vinicius Oliveira <vinicius_o.a@live.com>
 * @category    Micro Framework
 * @since       2019-02-09
 */

namespace Vindite;

use Vindite\App\AppCreator;
use Vindite\Routes\Route;
use Vindite\Request\Request;
use Vindite\Middleware\Middleware;
use Vindite\Render\View;
use Vindite\Container\Container;

/**
 * Bootstrap do framework
 */
final class App
{
    /**
     * Guarda a instância da aplicação
     *
     * @var App
     */
    protected static $instance = null;

    /**
     * Impede que a classe seja instanciada
     */
    final protected function __construct()
    {

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
     * Retorna uma instância da aplicação
     *
     * @return self
     */
    public static function getInstance() : self
    {
        if (self::$instance === null) {
            self::$instance = new self;
            (new AppCreator(new Container))->make();
        }

        return self::$instance;
    }

    /**
     * Retorna o objeto do roteador
     *
     * @return Vindite\Routes\Route
     */
    public function route() : Route
    {
        return AppCreator::container()->get(AppCreator::ROUTE);
    }

    /**
     * Retorna o objeto do middleware
     *
     * @return Vindite\Middleware\Middleware
     */
    public function middleware() : MiddlewareInterface
    {
        return AppCreator::container()->get(AppCreator::MIDDLEWARE);
    }

    /**
     * Retorna o objeto da request
     *
     * @return Vindite\Request\Request
     */
    public function request() : Request
    {
        return AppCreator::container()->get(AppCreator::REQUEST);
    }

    /**
     * Retorna o objeto da view
     *
     * @return Vindite\Request\Request
     */
    public function view() : View
    {
        return AppCreator::container()->get(AppCreator::VIEW);
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
}
