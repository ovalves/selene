<?php
/**
 * @copyright   2019 - Selene
 * @author      Vinicius Oliveira <vinicius_o.a@live.com>
 * @category    Micro Framework
 * @since       2019-03-03
 */

namespace Selene\Render\Parser;

/**
 * Parse template variables
 */
trait VariableParser
{
    /**
     * Guarda os dados do template que serão compilados
     *
     * @var array
     */
    private $matches = [];
    private $assign = null;
    private $plugin = null;

    /**
     * Define a regex de busca das tags de variaveis
     *
     * @var string
     */
    private $matchVariableTag = '/\{{2}\s*\$(.+?)\s*\}{2}/';

    /**
     * Define as tags que devem ser subistituidas ao fazer o parser das variaveis
     */
    private $replaceVariableTag = ["}", "{", "\t\n\r\0\x0b", " ", "$"];

    /**
     * Parse the template variables
     * Call plugin templates if exists
     *
     * @param string $content
     * @param array $variables
     * @return string
     */
    protected function parserVariables($content, array $variables) : string
    {
        preg_match_all($this->matchVariableTag, $content, $this->matches);

        if (empty($this->matches)) {
            return $content;
        }

        foreach ($this->matches[0] as $match) {
            $this->parseMatch($match);

            $this->assign = str_replace($this->replaceVariableTag, '', $this->assign);

            if ($this->variableIsNested($this->assign)) {
                $variables[$this->assign] = $this->parseNestedVariable($variables, $this->assign);
            }

            if (!isset($variables[$this->assign])) {
                continue;
            }

            if (!empty($this->plugin)) {
                $variables[$this->assign] = $this->callPluginIfExists($variables[$this->assign], $this->plugin);
            }

            $content = str_replace(
                $match,
                $variables[$this->assign],
                $content
            );
        }

        return $content;
    }

    /**
     * Parse variable string, separate assignment and plugin`s call
     *
     * @param string $match
     * @return void
     */
    private function parseMatch(string $match) : void
    {
        $match = explode('|', $match);

        if (empty($match[0])) {
            throw new Exception("Parser variable error");
        }

        $this->assign = (isset($match[0])) ? trim($match[0]) : null;
        $this->plugin = (isset($match[1])) ? trim($match[1]) : null;
    }

    /**
     * Verifica se a variavel está aninhada
     *
     * @param string $assign
     *
     * @return bool
     */
    private function variableIsNested(string $assign) : bool
    {
        if (empty($assign)) {
            return false;
        }

        $assign = explode('.', $assign);

        $this->contextVariable = (isset($assign[0])) ? trim($assign[0]) : null;
        $this->nested          = (isset($assign[1])) ? trim($assign[1]) : null;

        return (isset($this->contextVariable) && (isset($this->nested)));
    }

    /**
     * Faz o parser de váriaveis aninhadas através das váriaveis presentes na symbol table do loop
     *
     * @param array  $variables
     * @param string $assign
     *
     * @return mixed
     */
    private function parseNestedVariable(array $variables, string $assign)
    {
        if (empty($variables)) {
            return false;
        }

        $nestedVars = $this->symbolTable[$this->contextVariable];

        if (empty($nestedVars)) {
            return false;
        }

        if (isset($nestedVars[$this->nested])) {
            $variables[$assign] = trim($nestedVars[$this->nested]);
            return $variables[$assign];
        }

        foreach ($nestedVars as $vars) {
            if (!\is_array($vars)) {
                $variables[$assign] = $vars;
                continue;
            }

            if (array_key_exists($this->nested, $vars)) {
                $variables[$assign] = $vars[$this->nested];
                continue;
            }
        }

        return $variables[$assign];
    }

    /**
     * Faz a chamada para o plugin caso o mesmo exista
     *
     * @param mixed $variable
     * @param string $plugin
     * @return mixed
     */
    private function callPluginIfExists($variable, string $plugin)
    {
        $plugin = str_replace($this->replaceVariableTag, '', $plugin);
        return $this->compiler->callPlugin($variable, $plugin);
    }
}
