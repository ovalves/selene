<?php
/**
 * @copyright   2019 - Selene
 * @author      Vinicius Oliveira <vinicius_o.a@live.com>
 * @category    Micro Framework
 * @since       2019-02-27
 */

namespace Selene\Render\Parser;

/**
 * Define as constantes usadas nos parsers da template engine
 */
class ParserConstant
{
    /**
     * Loops arguments
     */
    const LOOP_FIRST_ARGUMENT  = '__FIRST_ARG__';
    const LOOP_SECOND_ARGUMENT = '__SECOND_ARG__';
    const LOOP_THIRD_ARGUMENT  = '__THIRD_ARG__';
    const LOOP_EXTRA_ARGUMENT  = '__EXTRA_ARG__';

    /**
     * Loop open tags
     */
    const LOOP_FOREACH_TAG = 'foreach';
    const LOOP_FOR_TAG = 'for';

    /**
     * Loops close tags
     */
    const LOOP_ENDFOREACH_TAG = 'endforeach';
    const LOOP_ENDFOR_TAG = 'endfor';

    /**
     * If open tags
     */
    const IF_OPEN_TAG = 'if';
    const ELSEIF_OPEN_TAG = 'elseif';

    /**
     * If close tags
     */
    const IF_CLOSE_TAG = 'endif';
    const ELSE_CLOSE_TAG = 'else';

}
