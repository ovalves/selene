<?php
/**
 * @copyright   2019 - Selene
 * @author      Vinicius Oliveira <vinicius_o.a@live.com>
 * @category    Micro Framework
 * @since       2019-10-12
 */

namespace Selene\Config;

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
    protected function loadConfig($prefix) : array
    {
        if (isset($this->config[$prefix])) {
            return $this->config[$prefix]->__invoke();
        }

        $this->config[$prefix] = new $this->mapConfig[$prefix];

        return $this->config[$prefix]->__invoke();
    }
}
