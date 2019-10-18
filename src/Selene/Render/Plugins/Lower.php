<?php
/**
 * @copyright   2019 - Selene
 * @author      Vinicius Oliveira <vinicius_o.a@live.com>
 * @category    Micro Framework
 * @since       2019-02-27
 */

namespace Selene\Render\Plugins;

use Selene\Render\PluginConstant;

/**
 * Plugin lowercase
 */
class Lower implements PluginInterface
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

        return strtolower($input);
    }
}
