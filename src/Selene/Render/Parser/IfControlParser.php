<?php
/**
 * @copyright   2019 - Selene
 * @author      Vinicius Oliveira <vinicius_o.a@live.com>
 * @category    Micro Framework
 * @since       2019-03-03
 */

namespace Selene\Render\Parser;

/**
 * Responsável por fazer o parser das tags if`s usada no template
 */
trait IfControlParser
{
    /**
     * Guarda os dados do template que serão compilados
     *
     * @var array
     */
    private $matches = [];

    /**
     * Define a regex de busca das tags if do template
     *
     * @var string
     */
    private $matchIfTags = '/{% ?\s*(if|endif|elseif|else)\s*(.+?)\s*\%}/';

    /**
     * Define a regex para busca das variaveis de avaliaçao
     *
     * @var string
     */
    private $matchControlVariable = '/\$([a-zA-Z0-9_\-\(\)\.]+)/';

    /**
     * Define a regex para busca do tipo de avaliacao a ser feita
     *
     * @var string
     */
    private $matchControlEvaluate = '/([==|===|!=|!==|>|<|&&|\/||\/])+/';

    /**
     * Define a regex de busca do parametro a ser avaliado
     *
     * @var string
     */
    private $matchParamToCompare = '/(.\w+(\'|\"){1}\)\s*\}{2})$/';

    /**
     * Define a string de sanitizacao do parametro de comparacao
     *
     * @var string
     */
    private $toCompareSanitize = "\'\"\)\(\}\" ";

    /**
     * Define as condições da estrutura de controle if
     *
     * @var array
     */
    private $controlTypes = [
        ParserConstant::IF_OPEN_TAG,
        ParserConstant::ELSEIF_OPEN_TAG
    ];

    /**
     * Define as condições da estrutura de controle if
     *
     * @var array
     */
    private $controlEndTypes = [
        ParserConstant::IF_CLOSE_TAG   => ';',
        ParserConstant::ELSE_CLOSE_TAG => ':'
    ];

    /**
     * Compila as tags if`s usadas no template
     *
     * @param string $content
     * @return string
     */
    protected function parserIfControl($content) : string
    {
        preg_match_all($this->matchIfTags, $content, $this->matches);

        if (empty($this->matches)) {
            return $content;
        }

        foreach ($this->matches[0] as $key => $condition) {
            if (empty($this->matches[1][$key])) {
                continue;
            }

            if (isset($this->controlEndTypes[$this->matches[1][$key]])) {
                $content = str_replace(
                    $this->matches[0][$key],
                    "<?php {$this->matches[1][$key]} {$this->controlEndTypes[$this->matches[1][$key]]} ?>",
                    $content
                );
                continue;
            }

            if (!in_array($this->matches[1][$key], $this->controlTypes)) {
                continue;
            }

            $control = $this->matches[1][$key];

            $variable = $this->getIfControlVariable($condition);
            if (empty($variable)) {
                continue;
            }

            $evaluate = $this->getIfControlEvaluate($condition);
            if (empty($evaluate)) {
                continue;
            }

            $toCompare = $this->getIfControlToCompare($condition);
            if (empty($toCompare)) {
                continue;
            }

            $content = str_replace(
                $this->matches[0][$key],
                "<?php {$control} ( {$variable} {$evaluate} \"{$toCompare}\" ) : ?>",
                $content
            );
        }

        return $content;
    }

    /**
     * Retorna a variavel que será usada para a comparacao no parser do if
     *
     * @param string $condition
     * @return string
     */
    private function getIfControlVariable(string $condition) : string
    {
        preg_match_all($this->matchControlVariable, $condition, $var);
        if (empty($var)) {
            return false;
        }

        return reset($var[0]);
    }

    /**
     * Retorna o tipo de avaliador que deve ser usado no parser do if
     *
     * @param string $condition
     * @return string
     */
    private function getIfControlEvaluate(string $condition) : string
    {
        preg_match_all($this->matchControlEvaluate, $condition, $evaluate);
        if (empty($evaluate)) {
            return false;
        }

        return reset($evaluate[0]);
    }

    /**
     * Retorna o parametro a ser comparado no parser do if
     *
     * @param string $condition
     * @return string
     */
    private function getIfControlToCompare(string $condition) : string
    {
        preg_match_all($this->matchParamToCompare, $condition, $toCompare);
        $toCompare = reset($toCompare[0]);
        if (empty($toCompare)) {
            return false;
        }

        return trim($toCompare, $this->toCompareSanitize);
    }
}
