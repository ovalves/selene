<?php
/**
 * @copyright   2019 - Selene
 * @author      Vinicius Oliveira <vinicius_o.a@live.com>
 * @category    Micro Framework
 * @since       2019-12-21
 */

namespace Selene\Config;

use Exception;

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
        $this->configuration = include 'App/Config/app.php';

        if (empty($this->configuration)) {
            throw new Exception("Failed to open the framework configuration file");
        }
    }

    /**
     * Return the config of aplication
     *
     * @return array
     */
    public function getConfig(string $type = null) : array
    {
        if (empty($type)) {
            return $this->configuration;
        }

        if (!isset($this->configuration[$type])) {
            throw new Exception(
                sprintf(
                    "The configuration of '%s' does not exist. Please check your configuration file",
                    $type
                )
            );
        }

        return $this->configuration[$type];
    }
}
