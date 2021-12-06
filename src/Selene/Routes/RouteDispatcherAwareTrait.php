<?php
/**
 * @copyright   2019 - Selene
 * @author      Vinicius Oliveira <vinicius_o.a@live.com>
 * @category    Micro Framework
 * @since       2019-02-15
 */

namespace Selene\Routes;

use Selene\Controllers\BaseController;
use Selene\Render\View;

trait RouteDispatcherAwareTrait
{
    /**
     * Guarda a instância da view.
     *
     * @var View
     */
    protected $dispatcherView;

    /**
     * Injeta a view no roteador.
     */
    public function injectViewOnRouterDispatcher(View $view): void
    {
        $this->dispatcherView = $view;
    }

    /**
     * Injeta as classes necessárias na base controller.
     */
    protected function injectOnBaseController(): void
    {
        $controller = new \ReflectionClass($this->controller);
        $parent = $controller->getParentClass();

        if (!$controller->isSubclassOf(BaseController::class)) {
            throw new RouteException(\sprintf('A controller (%s) precisa herdar da controller base (%s)', get_class($this->controller), BaseController::class));
        }

        $this->injectViewOnBaseController($parent);
        $this->injectContainerOnBaseController($parent);
    }

    /**
     * Inject the view on base controller.
     */
    private function injectViewOnBaseController(\ReflectionClass $parent): void
    {
        if (!$parent->hasMethod(BaseController::INJECT_VIEW)) {
            throw new RouteException(\sprintf('Método (%s) responsável por injetar a classe de view não foi encontrado na controller base (%s)', BaseController::INJECT_VIEW, BaseController::class));
        }

        $reflectionMethod = new \ReflectionMethod($this->controller, BaseController::INJECT_VIEW);
        $reflectionMethod->invoke($this->controller, $this->dispatcherView);
    }

    /**
     * Inject the service container on base controller.
     */
    private function injectContainerOnBaseController(\ReflectionClass $parent): void
    {
        if (!$parent->hasMethod(BaseController::INJECT_SERVICE_CONTAINER)) {
            throw new RouteException(\sprintf('Método (%s) responsável por injetar a classe de Service Container não foi encontrado na controller base (%s)', BaseController::INJECT_SERVICE_CONTAINER, BaseController::class));
        }

        $reflectionMethod = new \ReflectionMethod($this->controller, BaseController::INJECT_SERVICE_CONTAINER);
        $reflectionMethod->invoke($this->controller, $this->container);
    }
}
