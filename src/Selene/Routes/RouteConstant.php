<?php
/**
 * @copyright   2019 - Selene
 * @author      Vinicius Oliveira <vinicius_o.a@live.com>
 * @category    Micro Framework
 * @since       2019-02-09
 */

namespace  Selene\Routes;

/**
 * Define as constantes usadas no router.
 */
class RouteConstant
{
    /**
     * Define o tipo de resource corrente como get.
     */
    public const GET = 'get';

    /**
     * Define o tipo de resource corrente como.
     */
    public const POST = 'post';

    /**
     * Define o tipo de resource corrente como.
     */
    public const PUT = 'put';

    /**
     * Define o tipo de resource corrente como.
     */
    public const PATCH = 'patch';

    /**
     * Define o tipo de resource corrente como.
     */
    public const DELETE = 'delete';

    /**
     * Define a chave resource da fila do router.
     */
    public const ROUTE_RESOURCE = 'resource';

    /**
     * Define a chave class da fila do router.
     */
    public const ROUTE_CLASS = 'class';

    /**
     * Define a chave action da fila do router.
     */
    public const ROUTE_ACTION = 'action';

    /**
     * Define a chave da Closure da fila do router.
     */
    public const ROUTE_CALLBACK = 'callback';
}
