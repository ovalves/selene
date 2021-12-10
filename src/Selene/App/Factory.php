<?php
/**
 * @copyright   2019 - Selene
 * @author      Vinicius Oliveira <vinicius_o.a@live.com>
 * @category    Micro Framework
 * @since       2019-12-23
 */

namespace Selene\App;

use Selene\App;
use Symfony\Component\Dotenv\Dotenv;

/**
 * Framework Bootstrap.
 */
final class Factory
{
    /**
     * @var App
     */
    private static $instance = null;

    final public function __construct()
    {
    }

    final public function __clone()
    {
    }

    final public function __wakeup()
    {
    }

    /**
     * Return framework instance.
     */
    public static function create(string $root = ''): App
    {
        if (null === self::$instance) {
            $dotenv = new Dotenv();
            $dotenv->load($root. DIRECTORY_SEPARATOR .'.env');
            self::$instance = new App($root);
        }

        return self::$instance;
    }

    public static function getInstance(): App
    {
        return self::$instance;
    }
}
