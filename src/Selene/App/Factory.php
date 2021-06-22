<?php
/**
 * @copyright   2019 - Selene
 * @author      Vinicius Oliveira <vinicius_o.a@live.com>
 * @category    Micro Framework
 * @since       2019-12-23
 */

namespace Selene\App;

use Selene\App;

/**
 * Framework Bootstrap
 */
final class Factory
{
    /**
     * @var App
     */
    protected static $instance = null;

    final public function __construct()
    {
        //
    }

    final public function __clone()
    {
        //
    }

    final public function __wakeup()
    {
        //
    }

    /**
     * Return framework instance
     *
     * @return App
     */
    public static function create() : App
    {
        if (self::$instance === null) {
            self::$instance = new App;
        }

        return self::$instance;
    }
}
