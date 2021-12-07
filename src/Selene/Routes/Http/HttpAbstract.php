<?php
/**
 * @copyright   2019 - Selene
 * @author      Vinicius Oliveira <vinicius_o.a@live.com>
 * @category    Micro Framework
 * @since       2019-02-16
 */

namespace Selene\Routes\Http;

use Selene\Routes\RouteAwareResolveCallbackTrait;
use Selene\Routes\RouteConstant;
use Selene\Routes\RouteException;

/**
 * Reponsável por adicionar rotas a fila do roteador.
 */
abstract class HttpAbstract
{
    use RouteAwareResolveCallbackTrait;

    /**
     * Adiciona uma rota a fila de rotas.
     *
     * @param mixed $callback
     */
    public function resolve($routeType, string $groupIdentifier, array $queue, string $resource, $callback = null) : array
    {
        if (empty($routeType)) {
            throw new RouteException('Método HTTP desconhecido', 404);
        }

        if (empty($resource)) {
            throw new RouteException('Recurso não encontrado', 404);
        }

        $this->resolveCallback($callback);

        if ($this->isCallable()) {
            $queue[$groupIdentifier][] = [
                $routeType => [
                    RouteConstant::ROUTE_RESOURCE => $resource,
                    RouteConstant::ROUTE_CALLBACK => $callback,
                ],
            ];

            return $queue;
        }

        if (empty($this->getController())) {
            throw new RouteException('Controller não encontrada', 404);
        }

        if (empty($this->getMethod())) {
            throw new RouteException('Ação da controller não encontrada', 404);
        }

        $queue[$groupIdentifier][] = [
            $routeType => [
                RouteConstant::ROUTE_RESOURCE => $resource,
                RouteConstant::ROUTE_CLASS => $this->getController(),
                RouteConstant::ROUTE_ACTION => $this->getMethod(),
            ],
        ];

        return $queue;
    }
}
