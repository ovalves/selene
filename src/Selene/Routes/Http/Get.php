<?php
/**
 * @copyright   2019 - Selene
 * @author      Vinicius Oliveira <vinicius_o.a@live.com>
 * @category    Micro Framework
 * @since       2019-02-09
 */

namespace Selene\Routes\Http;

use Selene\Routes\RouteConstant;

/**
 * Reponsável por adicionar action get a fila do roteador.
 */
class Get extends HttpAbstract
{
    /**
     * Adiciona uma rota a fila de rotas.
     *
     * @param mixed $callback
     */
    public function __invoke(array $queue, string $resource, $callback = null): array
    {
        return $this->resolve(RouteConstant::GET, $queue, $resource, $callback);
    }
}
