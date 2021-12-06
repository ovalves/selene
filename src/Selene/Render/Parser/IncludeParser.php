<?php
/**
 * @copyright   2019 - Selene
 * @author      Vinicius Oliveira <vinicius_o.a@live.com>
 * @category    Micro Framework
 * @since       2019-03-05
 */

namespace Selene\Render\Parser;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

/**
 * Responsável por fazer o parser dos includes do template.
 */
trait IncludeParser
{
    /**
     * Guarda os dados do template que serão compilados.
     *
     * @var array
     */
    private $matches = [];

    /**
     * Define a regex que será usada para busca dos dados de include no template.
     *
     * @var string
     */
    private $matchIncludeTag = '/\{{2}\s*(include|require)\s*(.+?)\s*}{2}/';

    /**
     * Faz o parser dos includes da template engine.
     *
     * @param string $content
     */
    protected function parserIncludes(string $file): string
    {
        $include = explode('/', $file);
        $includeFile = $this->findInDirectory($include);
        $code = file_get_contents($includeFile);

        preg_match_all('/{% ?(extends|include) ?\'?(.*?)\'? ?%}/i', $code, $matches, PREG_SET_ORDER);
        foreach ($matches as $value) {
            $code = str_replace($value[0], $this->parserIncludes($value[2]), $code);
        }

        return preg_replace('/{% ?(extends|include) ?\'?(.*?)\'? ?%}/i', '', $code);
    }

    /**
     * Procura o template no diretório das views da aplicação.
     *
     * @param  array       $includes
     * @return string|bool
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
     * Retorna o nome do arquivo requisitado.
     */
    private function requestedFile(array $parts): string
    {
        if (empty($parts)) {
            return false;
        }

        return (count($parts) > 1) ? end($parts) : reset($parts);
    }

    /**
     * Retorna o diretório onde o tempalte requisitado se encontra.
     */
    private function requestedDirectory(array $parts): string
    {
        if (empty($parts)) {
            return false;
        }

        if ((1 == count($parts))) {
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
