<?php
/**
 * @copyright   2017 - Selene
 * @author      Vinicius Oliveira <vinicius_o.a@live.com>
 * @category    Micro Framework
 * @since       2017-10-18
 */

namespace Selene\Controllers;

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
     * Guarda o objeto da view
     *
     * @var View
     */
    protected $view;

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
     * Instância o objeto da view
     *
     * @param View $view
     * @return void
     */
    final public function injectViewOnBaseController(View $view) : void
    {
        $this->view = $view;
    }
}
