<?php
/**
 * @copyright   2017 - Selene
 * @author      Vinicius Oliveira <vinicius_o.a@live.com>
 * @category    Micro Framework
 * @since       2017-10-18
 */

namespace Selene\Controllers;

use Psr\Container\ContainerInterface;
use Selene\Render\View;

/**
 * Classe base para as controllers
 */
class BaseController
{
    /**
     * Define a constante de injeção da view na base controller
     */
    const INJECT_VIEW = 'injectViewOnBaseController';

    /**
     * Define a constante de injeção do service container na base controller
     */
    const INJECT_SERVICE_CONTAINER = 'injectContainerOnBaseController';

    /**
     * Guarda o objeto da view
     *
     * @var View
     */
    protected $view;

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * Retorna objeto de render view
     *
     * @return View
     */
    protected function view() : View
    {
        return $this->view;
    }

    /**
     * Retorna objeto de Service Container
     *
     * @return ContainerInterface
     */
    protected function container() : ContainerInterface
    {
        return $this->container;
    }

    /**
     * Instância o objeto da view
     *
     * @param View $view
     * @return void
     */
    final public function injectViewOnBaseController(View $view) : void
    {
        $this->view = $view;
    }

    /**
     * Instância o objeto de Service Container
     *
     * @param ContainerInterface $container
     * @return void
     */
    final public function injectContainerOnBaseController(ContainerInterface $container) : void
    {
        $this->container = $container;
    }
}
