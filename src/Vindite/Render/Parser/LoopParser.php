<?php
/**
 * @copyright   2019 - Vindite
 * @author      Vinicius Oliveira <vinicius_o.a@live.com>
 * @category    Micro Framework
 * @since       2019-03-03
 */

namespace Vindite\Render\Parser;

/**
 * Responsável por fazer o parser das variaveis do template
 */
trait LoopParser
{
    /**
     * Guarda os dados do template que serão compilados
     *
     * @var array
     */
    private $matches = [];

    /**
     * Define a regex de busca da tag de loop
     *
     * @var string
     */
    private $matchLoop = '/\{{2}\s*(foreach|endforeach|for|endfor)\s*(.+?)\s*\}{2}/';

    /**
     * Define as condições da estrutura de controle if
     *
     * @var array
     */
    private $loopEndTypes = [
        ParserConstant::LOOP_ENDFOREACH_TAG,
        ParserConstant::LOOP_ENDFOR_TAG
    ];

    /**
     * @todo implementar o loop for
     *
     * Define as condições da estrutura de controle if
     *
     * @var array
     */
    private $loopControlTypes = [
        ParserConstant::LOOP_FOREACH_TAG => [
            ParserConstant::LOOP_FIRST_ARGUMENT  => '/\$([a-zA-Z0-9_\-\(\)\.]+)/',
            ParserConstant::LOOP_SECOND_ARGUMENT => '/\b(as|in)\b/',
            ParserConstant::LOOP_THIRD_ARGUMENT  => '/(.\w+)$/',
            ParserConstant::LOOP_EXTRA_ARGUMENT  => '/(\s{1}?\$.*\=>\s*)/'
        ],
        ParserConstant::LOOP_FOR_TAG => [
            ParserConstant::LOOP_FIRST_ARGUMENT  => '',
            ParserConstant::LOOP_SECOND_ARGUMENT => '',
            ParserConstant::LOOP_THIRD_ARGUMENT  => ''
        ],
        ParserConstant::LOOP_ENDFOREACH_TAG => 'endforeach',
        ParserConstant::LOOP_ENDFOR_TAG => 'endfor'
    ];

    /**
     * Compila as variaveis e executa o plugin caso a variavel necessite de um
     *
     * @param string $content
     * @param array $variables
     * @return string
     */
    protected function parserLoop($content, array $variables) : string
    {
        preg_match_all($this->matchLoop, $content, $this->matches);
        if (empty($this->matches)) {
            return $content;
        }

        foreach ($this->matches[2] as $key => $condition) {
            if (in_array($this->matches[1][$key], $this->loopEndTypes)) {
                $content = str_replace(
                    $this->matches[0][$key],
                    "<?php {$this->matches[1][$key]} ; ?>",
                    $content
                );
                continue;
            }

            $type = $this->loopControlTypes[$this->matches[1][0]];
            if (empty($type)) {
                continue;
            }

            $firstArg  = $this->getLoopArgument($type[ParserConstant::LOOP_FIRST_ARGUMENT], $condition);
            $secondArg = $this->getLoopArgument($type[ParserConstant::LOOP_SECOND_ARGUMENT], $condition);
            $thirdArg  = $this->getLoopArgument($type[ParserConstant::LOOP_THIRD_ARGUMENT], $condition);
            $extraArg  = $this->getLoopArgument($type[ParserConstant::LOOP_EXTRA_ARGUMENT], $condition);

            if (empty($firstArg || empty($secondArg || empty($thirdArg)))) {
                continue;
            }

            $contextVariable = (empty($extraArg)) ? $thirdArg : $extraArg . $thirdArg;

            if (empty($contextVariable)) {
                continue;
            }

            $symbolTableReference = $this->resolveAssignPrefix($thirdArg);
            $symbolTableArgument  = $this->resolveAssignPrefix($firstArg);
            $this->frameworkAssign($symbolTableReference, $variables[$symbolTableArgument]);

            $content = str_replace(
                $this->matches[0][$key],
                "<?php foreach( {$firstArg} {$secondArg} {$contextVariable} ) : ?>",
                $content
            );
        }

        return $content;
    }

    /**
     * Retorna o argumento usado no loop
     *
     * @param string $type
     * @param string $condition
     * @return string
     */
    private function getLoopArgument(string $type, string $condition) : string
    {
        preg_match($type, $condition, $argument);
        if (empty($argument)) {
            return false;
        }

        return trim(reset($argument));
    }
}
