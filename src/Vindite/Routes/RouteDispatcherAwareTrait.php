<?php
/**
 * @copyright   2019 - Vindite
 * @author      Vinicius Oliveira <vinicius_o.a@live.com>
 * @category    Micro Framework
 * @since       2019-02-15
 */

namespace Vindite\Routes;

use Vindite\Routes\RouteException;
use Vindite\Render\View;
use Vindite\Controllers\BaseController;

trait RouteDispatcherAwareTrait
{
    /**
     * Guarda a instância da view
     *
     * @var View
     */
    protected $dispatcherView;

    /**
     * Injeta a view no roteador
     *
     * @return void
     */
    public function injectViewOnRouterDispatcher(View $view) : void
    {
        $this->dispatcherView = $view;
    }

    /**
     * Injeta as classes necessárias na base controller
     *
     * @return void
     */
    protected function injectOnBaseController() : void
    {
        $controller = new \ReflectionClass($this->controller);
        $parent     = $controller->getParentClass();

        if (!$controller->isSubclassOf(BaseController::class)) {
            throw new RouteException(
                \sprintf(
                    "A controller (%s) precisa herdar da controller base (%s)",
                    get_class($this->controller),
                    BaseController::class
                )
            );
        }

        if (!$parent->hasMethod(BaseController::INJECT_VIEW)) {
            throw new RouteException(
                \sprintf(
                    "Método (%s) responsável por injetar a classe de view não foi encontrado na controller base (%s)",
                    BaseController::INJECT_VIEW,
                    BaseController::class
                )
            );
        }

        $reflectionMethod = new \ReflectionMethod($this->controller, BaseController::INJECT_VIEW);
        $reflectionMethod->invoke($this->controller, $this->dispatcherView);
    }
}