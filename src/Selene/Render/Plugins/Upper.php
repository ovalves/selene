<?php
/**
 * @copyright   2019 - Selene
 * @author      Vinicius Oliveira <vinicius_o.a@live.com>
 * @category    Micro Framework
 * @since       2019-02-27
 */

namespace Selene\Render\Plugins;

/**
 * Plugin uppercase
 */
class Upper implements PluginInterface
{
    /**
     * Retorna a assinatura do plugin
     *
     * @return array
     */
    public function __invoke($input) : string
    {
        if (!is_string($input)) {
            return $input;
        }

        return strtoupper($input);
    }
}
