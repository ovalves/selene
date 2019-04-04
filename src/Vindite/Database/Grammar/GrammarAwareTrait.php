<?php
/**
 * @copyright   2019 - Vindite
 * @author      Vinicius Oliveira <vinicius_o.a@live.com>
 * @category    Micro Framework
 * @since       2019-02-23
 */

namespace Vindite\Database\Grammar;

use Vindite\Database\DatabaseConstant;

/**
 * Responsavel por executar os diferentes tipos de statement
 */
trait GrammarAwareTrait
{
    /**
     * Formas de executar as clausulas
     *
     * @var array
     */
    protected $grammar = [
        DatabaseConstant::INSERT   => 'INSERT INTO __TABLENAME__ ( __FIELDS__ ) VALUES ( __VALUES__ )',
        DatabaseConstant::SELECT => 'SELECT __FIELDS__ FROM __TABLENAME__ __JOIN__ __WHERE__ __GROUP__',
        DatabaseConstant::UPDATE => 'UPDATE __TABLENAME__ __JOIN__ SET __FIELDS__ __WHERE__',
        DatabaseConstant::DELETE => 'DELETE FROM __TABLENAME__ __WHERE__',
    ];
}
