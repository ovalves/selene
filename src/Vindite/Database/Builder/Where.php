<?php
/**
 * @copyright   2019 - Vindite
 * @author      Vinicius Oliveira <vinicius_o.a@live.com>
 * @category    Micro Framework
 * @since       2019-02-23
 */

namespace Vindite\Database\Builder;

use Vindite\Database\Grammar\GrammarException;

/**
 * Responsavel por criar as clausulas where
 */
final class Where
{
    /**
     * Guarda os dados da clausula
     *
     * @var array
     */
    protected $whereString = '';

    /**
     * Guarda os dados que seerão usados no bind
     *
     * @var array
     */
    protected $dataPayload = [];

    /**
     * Define a gramatica do uso da clausula where
     *
     * @var array
     */
    protected $grammar = ['in', 'not in', '=', '<>', '!=', '?'];

    /**
     * Construtor
     *
     * @param array $where
     */
    public function __construct(array $where)
    {
        if (empty($where)) {
            return $this->whereString;
        }
        $this->make($where);
    }

    /**
     * Cria a clausula where
     *
     * @return void
     */
    private function make(array $where) : void
    {
        $this->whereString = 'WHERE ';
        foreach ($where as $clauses) {
            foreach ($clauses as $clause => $data) {
                $evaluate = $this->checkGrammarEvaluate($clause);
                $demand   = $this->checkGrammarDemand($clause);
                $this->checkGrammarBind($clause);
 
                if (is_string($demand)) {
                    $parsedDemand = str_pad($demand, strlen($demand) + 1, ':', STR_PAD_LEFT);
                }

                $this->whereString .= "{$demand}{$evaluate}{$parsedDemand}";
                $this->dataPayload[$demand] = $data;
            }
            
            if (next($where)) {
                $this->whereString .= " AND "; 
            }
        }
    }

    /**
     * Verifica se foi passado um tipo valido de avaliação na clausula where
     * 
     * @param string $clause
     * @return string
     */
    protected function checkGrammarEvaluate($clause) : string
    {
        preg_match('/(=|!=|in|not in|<>|>|<|\?){1}/', $clause, $grammarClause);
        if (!$grammarClause[0]) {
            throw new GrammarException("Error Processing Request");
        }

        return $grammarClause[0];
    }

    /**
     * Verifica se o tipo requisitado é valido
     *
     * @param string $clause
     * @return string
     */
    protected function checkGrammarDemand($clause) : string
    {
        preg_match('/^([\w\-]+)/', $clause, $grammarDemand);
        if (!$grammarDemand[0]) {
            throw new GrammarException("Error Processing Request");
        }

        return $grammarDemand[0];
    }

    /**
     * Verifica se o tipo de bind é valido na clausula where
     *
     * @param string $clause
     * @return string
     */
    protected function checkGrammarBind($clause) : string
    {
        preg_match('/(\?{1})$/', $clause, $grammarBind);
        if (!$grammarBind[0]) {
            throw new GrammarException("Error Processing Request");
        }

        return $grammarBind[0];
    }

    /**
     * Retorna a string contendo a clausula where
     *
     * @return string
     */
    public function getParsedString() : string
    {
        return $this->whereString;
    }

    /**
     * Retorna os dados de payload que serão usados no bind no where
     *
     * @return array
     */
    public function getWherePayload() : array
    {
        return $this->dataPayload;
    }
}
