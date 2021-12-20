<?php
/**
 * @copyright   2019 - Selene
 * @author      Vinicius Oliveira <vinicius_o.a@live.com>
 * @category    Micro Framework
 * @since       2019-02-27
 */

namespace Selene\Render\Parser;

/**
 * Define as constantes usadas nos parsers da template engine.
 */
class ParserConstant
{
    /**
     * Loops arguments.
     */
    public const LOOP_FIRST_ARGUMENT = '__FIRST_ARG__';
    public const LOOP_SECOND_ARGUMENT = '__SECOND_ARG__';
    public const LOOP_THIRD_ARGUMENT = '__THIRD_ARG__';
    public const LOOP_EXTRA_ARGUMENT = '__EXTRA_ARG__';

    /**
     * Loop open tags.
     */
    public const LOOP_FOREACH_TAG = 'foreach';
    public const LOOP_FOR_TAG = 'for';

    /**
     * Loops close tags.
     */
    public const LOOP_ENDFOREACH_TAG = 'endforeach';
    public const LOOP_ENDFOR_TAG = 'endfor';

    /**
     * If open tags.
     */
    public const IF_OPEN_TAG = 'if';
    public const ELSEIF_OPEN_TAG = 'elseif';

    /**
     * If close tags.
     */
    public const IF_CLOSE_TAG = 'endif';
    public const ELSE_CLOSE_TAG = 'else';
}
