<?php
/**
 * @copyright   2019 - Selene
 * @author      Vinicius Oliveira <vinicius_o.a@live.com>
 * @category    Micro Framework
 * @since       2019-02-27
 */

namespace Selene\Render\Compiler;

use Selene\Render\Plugins\PluginConstant;
use Selene\Render\Parser;

/**
 * Responsável por compilar o template
 */
final class TemplateCompiler
{
    use Parser\VariableParser;
    use Parser\IfControlParser;
    use Parser\LoopParser;
    use Parser\IncludeParser;

    /**
     * Guarda o objeto compilador de plugin da template engine
     *
     * @var PluginCompiler
     */
    protected $compiler;

    /**
     * Guarda os dados na tabela de simbolos da template engine
     *
     * @var array
     */
    protected $symbolTable = [];

    /**
     * Executa os compiladores de construção da template engine
     *
     * @param string $content
     * @param array $variables
     * @return void
     */
    public function compilerTemplate(PluginCompiler $compiler, $content, array &$variables)
    {
        $this->compiler = $compiler;
        $content = $this->parserIncludes($content);
        $content = $this->parserLoop($content, $variables);
        $content = $this->parserIfControl($content);
        $content = $this->parserVariables($content, $variables);

        return $content;
    }

    /**
     * Atribui uma variavel ao array da tabela de simbolos
     *
     * @param string $prefix
     * @param mixed $data
     * @return void
     */
    protected function frameworkAssign(string $prefix, $data) : void
    {
        if (!empty($prefix) && !empty($data)) {
            $this->symbolTable[$prefix] = $data;
        }
    }

    /**
     * Remove o profixo de variavel para formar a tabela de simbolos corretamente
     *
     * @param string $prefix
     * @return string
     */
    protected function resolveAssignPrefix(string $prefix) : string
    {
        if (empty($prefix)) {
            return false;
        }

        return str_replace('$', '', $prefix);
    }
}
