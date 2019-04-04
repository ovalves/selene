<?php
/**
 * @copyright   2019 - Vindite
 * @author      Vinicius Oliveira <vinicius_o.a@live.com>
 * @category    Micro Framework
 * @since       2019-02-23
 */

namespace Vindite\Database\Builder;

/**
 * Responsavel por criar as clausulas where
 */
final class Join
{
    /**
     * Guarda os dados da clausula
     *
     * @var array
     */
    protected $joinString = '';

    /**
     * Construtor
     *
     * @param string $join
     */
    public function __construct(string $join = null)
    {
        if (empty($join)) {
            return $this->joinString;
        }
        $this->make($join);
    }

    /**
     * Cria a clausula where
     *
     * @return void
     */
    private function make(string $join) : void
    {
        $this->joinString = $join;
    }

    /**
     * Retorna a string contendo a clausula where
     *
     * @return string
     */
    public function getParsedString() : string
    {
        return $this->joinString;
    }
}
