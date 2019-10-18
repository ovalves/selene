<?php
/**
 * @copyright   2019 - Selene
 * @author      Vinicius Oliveira <vinicius_o.a@live.com>
 * @category    Micro Framework
 * @since       2019-03-03
 */

namespace Selene\Render\Parser;

/**
 * Responsável por fazer o parser das variaveis do template
 */
trait VariableParser
{
    /**
     * Guarda os dados do template que serão compilados
     *
     * @var array
     */
    private $matches = [];

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
     * Compila as variaveis e executa o plugin caso a variavel necessite de um
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
            list($assing, $plugin) = explode('|', $match);
            $assing = str_replace($this->replaceVariableTag, '', $assing);

            if ($this->variableIsNested($assing)) {
                $variables[$assing] = $this->parseNestedVariable($variables);
            }

            if (!isset($variables[$assing])) {
                continue;
            }

            if (!empty($plugin)) {
                $variables[$assing] = $this->callPluginIfExists($variables[$assing], $plugin);
            }

            $content = str_replace(
                $match,
                "<?php echo @{$variables[$assing]};?>",
                $content
            );
        }

        return $content;
    }

    /**
     * Verifica se a variavel está aninhada
     *
     * @param string $assing
     * @return bool
     */
    private function variableIsNested(string $assing) : bool
    {
        if (empty($assing)) {
            return false;
        }

        list($this->contextVariable, $this->nested) = explode('.', $assing);
        return (isset($this->contextVariable) && (isset($this->nested)));
    }

    /**
     * Faz o parser de váriaveis aninhadas através das váriaveis presentes na symbol table do loop
     *
     * @return mixed
     */
    private function parseNestedVariable(array $variables)
    {
        if (empty($variables)) {
            return false;
        }

        $nestedVars = $this->symbolTable[$this->contextVariable];
        if (empty($nestedVars)) {
            return false;
        }

        foreach ($nestedVars as $vars) {
            if (array_key_exists($this->nested, $vars)) {
                $variables[$assing] = '$'.$this->contextVariable.'["'.$this->nested.'"]';
                continue;
            }
        }

        return $variables[$assing];
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
