<?php
/**
 * @copyright   2019 - Vindite
 * @author      Vinicius Oliveira <vinicius_o.a@live.com>
 * @category    Micro Framework
 * @since       2019-02-27
 */

namespace Vindite\Render\Plugins;

/**
 * Interface para os plugins da template engine
 */
interface PluginInterface
{
    public function __invoke($string) : string;
}
