<?php
/**
 * @copyright   2019 - Selene
 * @author      Vinicius Oliveira <vinicius_o.a@live.com>
 * @category    Micro Framework
 * @since       2019-12-21
 */

namespace Selene\Config;

/**
 * Gerencia os arquivos de configuração do framework
 */
class ApplicationConfig
{
    /**
     * Guarda os arquivos ja carregados
     *
     * @var array
     */
    protected $configuration = [];

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->configuration = require 'App/Config/app.php';
    }

    /**
     * Return the config of aplication
     *
     * @return array
     */
    public function getConfig(string $type = null) : array
    {
        if (!empty($type)) {
            return $this->configuration[$type];
        }

        return $this->configuration;
    }
}
