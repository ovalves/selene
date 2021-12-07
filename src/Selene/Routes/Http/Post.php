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
 * Reponsável por adicionar action post a fila do roteador.
 */
class Post extends HttpAbstract
{
    public function __invoke(string $groupIdentifier, array $queue, string $resource, mixed $callback = null) : array
    {
        return $this->resolve(RouteConstant::POST, $groupIdentifier, $queue, $resource, $callback);
    }
}
