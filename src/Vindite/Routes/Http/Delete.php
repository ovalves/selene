<?php
/**
 * @copyright   2019 - Vindite
 * @author      Vinicius Oliveira <vinicius_o.a@live.com>
 * @category    Micro Framework
 * @since       2019-02-09
 */

namespace Vindite\Routes\Http;

use Vindite\Routes\RouteConstant;

/**
 * Reponsável por adicionar action delete a fila do roteador
 */
class Delete extends HttpAbstract
{
    /**
     * Adiciona uma rota a fila de rotas
     *
     * @param array $queue
     * @param string $resource
     * @param mixed $callback
     *
     * @return array
     */
    public function __invoke(array $queue, string $resource, $callback = null) : array
    {
        return $this->resolve(RouteConstant::DELETE, $queue, $resource, $callback);
    }
}
