<?php
/**
 * @copyright   2019 - Vindite
 * @author      Vinicius Oliveira <vinicius_o.a@live.com>
 * @category    Micro Framework
 * @since       2019-02-17
 */

namespace Vindite\Loader;

use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;
use Exception;

/**
 * Responsável por instânciar as classes da aplicação
 */
class AppLoader
{
    /**
     * Guarda os diretórios que serão carregados da aplicação
     *
     * @var array
     */
    protected $directories;
    
    /**
     * Adiciona um diretório a a fila de diretórios
     */
    public function addDirectory($directory)
    {
        $this->directories[] = $directory;
    }
    
    /**
     * Inicia o finder para encontrar os diretórios da aplicação
     */
    public function load()
    {
        spl_autoload_register([$this, 'finder']);
    }
    
    /**
     * Procura uma classe da aplicação e carrega
     */
    protected function finder($class)
    {
        if (empty($this->directories)) {
            return false;
        }
        
        if (!array_walk($this->directories, [$this, 'finderClassInDirectory'], $class)) {
            array_walk_recursive($this->directories, [$this, 'finderRecursiveInDirectory'], $class);
        }
    }

    /**
     * Itera pelo diretório para encontrar as classes
     *
     * @param string $directory
     * @param string $argKey
     * @param string $class
     * @return bool
     */
    protected function finderClassInDirectory($directory, $argKey, $class) : bool
    {
        if (empty($directory)) {
            return false;
        }

        if (file_exists("{$directory}/{$class}.php")) {
            require_once "{$directory}/{$class}.php";
            return true;
        }

        return false;
    }

    /**
     * Itera recursivamente pelos diretórios para encontrar as classes
     *
     * @param string $directory
     * @param string $argKey
     * @param string $class
     * @return bool
     */
    protected function finderRecursiveInDirectory($directory, $argKey, $class) : bool
    {
        if (empty($directory)) {
            return false;
        }

        $recursiveDirectory = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($directory),
            RecursiveIteratorIterator::SELF_FIRST
        );

        if (!file_exists($recursiveDirectory)) {
            return false;
        }

        foreach ($recursiveDirectory as $entry) {
            if (!is_dir($entry)) {
                return false;
            }

            if (!file_exists("{$entry}/{$class}.php")) {
                return false;
            }

            require_once "{$entry}/{$class}.php";
            return true;
        }

        return false;
    }
}
