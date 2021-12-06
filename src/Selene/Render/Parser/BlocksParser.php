<?php
/**
 * @copyright   2019 - Selene
 * @author      Vinicius Oliveira <vinicius_o.a@live.com>
 * @category    Micro Framework
 * @since       2019-03-05
 */

namespace Selene\Render\Parser;

/**
 * Responsável por fazer o parser dos includes do template
 */
trait BlocksParser
{
    /**
     * Guarda os dados do template que serão compilados
     *
     * @var array
     */
    private $blocks = [];

    /**
     * Faz o parser dos includes da template engine
     *
     * @param string $content
     * @return string
     */
    protected function parserBlocks(string $content) : string
    {
        preg_match_all('/{% ?block ?(.*?) ?%}(.*?){% ?endblock ?%}/is', $content, $matches, PREG_SET_ORDER);

        foreach ($matches as $value) {
            if (!array_key_exists($value[1], $this->blocks)) {
                $this->blocks[$value[1]] = '';
            }

            if (strpos($value[2], '@parent') === false) {
                $this->blocks[$value[1]] = $value[2];
            } else {
                $this->blocks[$value[1]] = str_replace('@parent', $this->blocks[$value[1]], $value[2]);
            }
            $content = str_replace($value[0], '', $content);
        }

        return $this->parserYield($content);
    }

    protected function parserYield($content)
    {
        foreach ($this->blocks as $block => $value) {
            $content = preg_replace('/{% ?yield ?' . $block . ' ?%}/', $value, $content);
        }

        $content = preg_replace('/{% ?yield ?(.*?) ?%}/i', '', $content);
        return $content;
    }
}
