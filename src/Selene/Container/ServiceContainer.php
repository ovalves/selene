<?php
/**
 * @copyright   2019 - Selene
 * @author      Vinicius Oliveira <vinicius_o.a@live.com>
 * @category    Micro Framework
 * @since       2019-12-21
 */

namespace Selene\Container;

/**
 * Define the service`s container constants.
 */
class ServiceContainer
{
    public const APPLICATION_CONFIG = 'applicationConfig';
    public const ROUTE = 'router';
    public const MIDDLEWARE = 'middleware';
    public const REQUEST = 'request';
    public const AUTH = 'auth';
    public const SESSION = 'session';
    public const REDIRECT = 'redirect';
    public const VIEW = 'view';
    public const CONNECTION = 'connection';
    public const LOGGER = 'logger';
    public const TRANSACTION = 'transaction';
}
