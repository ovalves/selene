<?php
/**
 * @copyright   2019 - Selene
 * @author      Vinicius Oliveira <vinicius_o.a@live.com>
 * @category    Micro Framework
 * @since       2019-07-01
 */

if (!function_exists('env')) {

    /**
     * Busca valores em variáveis de ambientes
     *
     * @param string $name
     * @return string
     */
    function env(string $name)
    {
        return (!empty($_ENV[$name])) ? $_ENV[$name] : '';
    }
}
