<?php
/**
 * @copyright   2019 - Selene
 * @author      Vinicius Oliveira <vinicius_o.a@live.com>
 * @category    Micro Framework
 * @since       2019-02-23
 */

namespace Selene\Database\Builder;

/**
 * Responsavel por criar as clausulas where.
 */
final class Join
{
    /**
     * Guarda os dados da clausula.
     *
     * @var array
     */
    private string $joinString = '';

    /**
     * Construtor.
     *
     * @param string $join
     */
    public function __construct(array $join = [])
    {
        if (empty($join)) {
            return $this->joinString;
        }
        $this->make($join);
    }

    /**
     * Cria a clausula where.
     */
    private function make(array $join): void
    {
        foreach ($join as $clause) {
            $this->joinString .= ' ' . $clause;
        }
    }

    /**
     * Retorna a string contendo a clausula where.
     */
    public function getParsedString(): string
    {
        return $this->joinString;
    }
}
