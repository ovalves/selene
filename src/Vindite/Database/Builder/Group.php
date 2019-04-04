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
final class Group
{
    /**
     * Guarda os dados da clausula
     *
     * @var array
     */
    protected $groupString = '';

    /**
     * Construtor
     *
     * @param string $join
     */
    public function __construct(string $group = null)
    {
        if (empty($group)) {
            return $this->groupString;
        }

        $this->make($group);
    }

    /**
     * Cria a clausula where
     *
     * @return void
     */
    private function make(string $group) : void
    {
        $this->groupString = $group;
    }

    /**
     * Retorna a string contendo a clausula where
     *
     * @return string
     */
    public function getParsedString() : string
    {
        return $this->groupString;
    }
}
