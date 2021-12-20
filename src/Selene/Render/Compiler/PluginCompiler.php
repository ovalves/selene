<?php
/**
 * @copyright   2019 - Selene
 * @author      Vinicius Oliveira <vinicius_o.a@live.com>
 * @category    Micro Framework
 * @since       2019-02-27
 */

namespace Selene\Render\Compiler;

use Selene\Render\Plugins;

/**
 * Responsável por compilar os plugins.
 */
final class PluginCompiler
{
    /**
     * Guarda o array de plugins definidos pelo framework.
     *
     * @var array
     */
    private $pluginsFramework = [];

    /**
     * Guarda o array de plugins definidos pela aplicação cliente.
     *
     * @var array
     */
    private $pluginsCustom = [];

    /**
     * Guarda os plugins que estão presentes no template.
     *
     * @var array
     */
    private $matches = [];

    /**
     * Undocumented function.
     */
    public function __construct()
    {
        $this->initPlugins();
    }

    /**
     * Inicia os plugins da template engine.
     */
    private function initPlugins(): void
    {
        $this->addFrameworkPlugins([
            Plugins\PluginConstant::UPPER => Plugins\Upper::class,
            Plugins\PluginConstant::LOWER => Plugins\Lower::class,
        ]);
    }

    /**
     * Adiciona um plugin ao array de plugins do framework.
     */
    private function addFrameworkPlugins(array $plugins): void
    {
        $this->pluginsFramework = $plugins;
    }

    /**
     * Adiciona um plugin ao array de plugins do framework.
     *
     * @param array $plugins
     */
    public function addCustomPlugins($plugin) : void
    {
        $this->pluginsCustom[] = $plugin;
    }

    /**
     * Chama o plugin executando-o.
     *
     * @param mixed $variable
     * @param mixed $plugin
     * @return mixed
     */
    public function callPlugin($variable, $plugin)
    {
        if (empty($variable)) {
            return false;
        }

        if (!isset($this->pluginsFramework[$plugin])) {
            return $variable;
        }

        return (new $this->pluginsFramework[$plugin]())($variable);
    }
}
