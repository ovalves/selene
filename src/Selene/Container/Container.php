<?php
/**
 * @copyright   2019 - Selene
 * @author      Vinicius Oliveira <vinicius_o.a@live.com>
 * @category    Micro Framework
 * @since       2019-02-09
 */

namespace Selene\Container;

use Psr\Container\ContainerInterface;
use Selene\Container\ContainerException;

/**
 * Define os containers usados na aplicação
 */
class Container implements ContainerInterface
{
    /**
     * Guarda containers criados
     *
     * @var array
     */
    private $containers;

    /**
     * Guarda as dependencias do objeto sendo criado
     *
     * @var array
     */
    private $dependencies;

    /**
     * Undocumented variable
     *
     * @var array
     */
    private $configuration = [];

    /**
     * Guarda o prefixo identificar do container corrente
     *
     * @var string
     */
    private $prefix;

    /**
     * Guarda o namespace da aplicação
     *
     * @var string
     */
    private $namespace;

    /**
     * Contructor
     */
    public function __construct()
    {
        $this->containers   = [];
        $this->dependencies = [];
        $this->prefix       = null;
        $this->namespace    = null;
    }

    /**
     * Define o prefixo identificar do container corrente
     *
     * @param string $prefix
     * @return self
     */
    public function setConfiguration($configuration) : self
    {
        $this->configuration = $configuration;
        return $this;
    }

    /**
     * Define o prefixo identificar do container corrente
     *
     * @param string $prefix
     * @return self
     */
    public function setPrefix(string $prefix = null) : self
    {
        $this->prefix = $prefix;
        return $this;
    }

    /**
     * Cria um novo container e suas dependencias
     *
     * @param Object $container
     * @param array $dependencies
     *
     * @return Object Container
     */
    public function set($container, array $dependencies = [])
    {
        if ($this->has($container)) {
            return $container;
        }

        try {
            foreach ($dependencies as $key => $value) {
                if (\is_object($value)) {
                    $this->dependencies[$key] = $value;
                    continue;
                }

                if (!\is_array($value) && \class_exists($value)) {
                    $this->dependencies[$key] = new $value;
                    continue;
                }

                $this->dependencies[$key] = $value;
            }

            $this->containers[$this->prefix] = new $container($this, ...$this->dependencies);
            return $this->containers[$this->prefix];
        } catch (\Throwable $e) {
            throw new ContainerException($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Retorna um determinado container do pool de containers criados
     *
     * @param $container
     * @return Object Container
     */
    public function get($container)
    {
        if (!$this->has($container)) {
            throw new ContainerException('Serviço não encontrado', 404);
        }

        return $this->containers[$container];
    }

    /**
     * Retorna todos os containers criados
     *
     * @return array
     */
    public function getContainers() : array
    {
        return $this->containers;
    }

    /**
     * Verifica se um container já foi criado
     *
     * @param Object $container
     * @return bool
     */
    public function has($container) : bool
    {
        return !empty($this->containers[$container]);
    }
}
