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
    public function __invoke(string $groupIdentifier, array $queue, string $resource, mixed $callback = null): array
    {
        return $this->resolve(RouteConstant::GET, $groupIdentifier, $queue, $resource, $callback);
    }
}
