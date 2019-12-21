<?php
/**
 * @copyright   2019 - Selene
 * @author      Vinicius Oliveira <vinicius_o.a@live.com>
 * @category    Micro Framework
 * @since       2019-12-21
 */

namespace Selene\Container;

/**
 * Define the service`s container constants
 */
class ServiceContainer
{
    const APPLICATION_CONFIG = 'applicationConfig';
    const ROUTE              = 'router';
    const MIDDLEWARE         = 'middleware';
    const REQUEST            = 'request';
    const AUTH               = 'auth';
    const SESSION            = 'session';
    const VIEW               = 'view';
    const CONNECTION         = 'connection';
    const LOGGER             = 'logger';
    const TRANSACTION        = 'transaction';
}
