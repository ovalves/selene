<?php
/**
 * @copyright   2019 - Vindite
 * @author      Vinicius Oliveira <vinicius_o.a@live.com>
 * @category    Micro Framework
 * @since       2019-10-12
 */

namespace Vindite\Config;

/**
 * Gerencia os arquivos de configuração do framework
 */
trait ConfigAwareTrait
{
    /**
     * Mapeamento dos arquivos de config
     *
     * @var array
     */
    protected $mapConfig = [
        ConfigConstant::AUTH    => \Auth::class,
        ConfigConstant::SESSION => \Session::class
    ];

    /**
     * Guarda os arquivos ja carregados
     *
     * @var array
     */
    protected $config = [];

    /**
     * Carrega a configuração por tipo
     *
     * @return array
     */
    protected function loadConfig($prefix)
    {
        if (isset($this->config[$prefix])) {
            return $this->config[$prefix];
        }

        $this->config = new $this->mapConfig[$prefix];

        return $this->config->__invoke();
    }
}
