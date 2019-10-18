<?php
/**
 * @copyright   2019 - Selene
 * @author      Vinicius Oliveira <vinicius_o.a@live.com>
 * @category    Micro Framework
 * @since       2019-03-05
 */

namespace Selene\Render\Parser;

use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;

/**
 * Responsável por fazer o parser dos includes do template
 */
trait IncludeParser
{
    /**
     * Guarda os dados do template que serão compilados
     *
     * @var array
     */
    private $matches = [];

    /**
     * Define a regex que será usada para busca dos dados de include no template
     *
     * @var string
     */
    private $matchIncludeTag = '/\{{2}\s*(include|require)\s*(.+?)\s*}{2}/';

    /**
     * Faz o parser dos includes da template engine
     *
     * @param string $content
     * @return string
     */
    protected function parserIncludes(string $content) : string
    {
        preg_match_all($this->matchIncludeTag, $content, $this->matches);
        if (empty($this->matches)) {
            return $content;
        }

        foreach ($this->matches[2] as $key => $match) {
            $include = explode('/', $match);
            $includeFile = $this->findInDirectory($include);

            if (empty($includeFile)) {
                continue;
            }

            $includedFileContent = file_get_contents($includeFile);

            $content = str_replace(
                $this->matches[0][$key],
                $includedFileContent,
                $content
            );
        }

        return $content;
    }

    /**
     * Procura o template no diretório das views da aplicação
     *
     * @param array $includes
     * @return void
     */
    private function findInDirectory(array $parts)
    {
        if (empty($parts)) {
            return false;
        }

        $requestedFile = $this->requestedFile($parts);
        $requestedDirectory = $this->requestedDirectory($parts);

        $recursiveDirectory = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator(
                \Selene\Render\ViewAbstract::APP_VIEW_DIRECTORY.$requestedDirectory
            ),
            RecursiveIteratorIterator::SELF_FIRST
        );

        if (empty($recursiveDirectory)) {
            return false;
        }

        foreach ($recursiveDirectory as $entry) {
            if (!$entry->isFile()) {
                continue;
            }

            if ($entry->getFileName() === $requestedFile) {
                return $entry->getPathname();
                break;
            }
        }

        return false;
    }

    /**
     * Retorna o nome do arquivo requisitado
     *
     * @param array $parts
     * @return string
     */
    private function requestedFile(array $parts) : string
    {
        if (empty($parts)) {
            return false;
        }

        return (count($parts) > 1) ? end($parts) : reset($parts);
    }

    /**
     * Retorna o diretório onde o tempalte requisitado se encontra
     *
     * @param array $parts
     * @return string
     */
    private function requestedDirectory(array $parts) : string
    {
        if (empty($parts)) {
            return false;
        }

        if ((count($parts) == 1)) {
            return reset($parts);
        }

        array_pop($parts);
        $directory = '';
        foreach ($parts as $part) {
            if (empty($part)) {
                continue;
            }

            $directory .= $part . DIRECTORY_SEPARATOR;
        }

        return $directory;
    }
}
