<?php
/**
 * @copyright   2019 - Vindite
 * @author      Vinicius Oliveira <vinicius_o.a@live.com>
 * @category    Micro Framework
 * @since       2019-02-23
 */

namespace Vindite\Database\Grammar;

use Vindite\Database\Transaction;
use Vindite\Database\Builder\Where;
use Vindite\Database\Builder\Join;
use Vindite\Database\Builder\Group;
use PDOStatement;

/**
 * Responsavel por disponibilizar os métodos comuns entre as classes de builder de query
 */
abstract class GrammarAbstract
{
    /**
     * Guarda a transação ativa
     *
     * @var Transaction
     */
    protected $transaction;

    /**
     * Guarda os campos a serem usados na clausula
     *
     * @var mixed
     */
    protected $fields;

    /**
     * Guarda os valores a serem usados na clausula
     *
     * @var mixed
     */
    protected $values;

    /**
     * Guarda os valores a serem usados como bind na clausula
     *
     * @var mixed
     */
    protected $bindParam;

    /**
     * Guarda a tabela a ser usada na clausula
     *
     * @var mixed
     */
    protected $table;

    /**
     * Guarda os dados que serão usados na clausula where
     *
     * @var array
     */
    protected $where = [];

    /**
     * Guarda os dados que serão usados na clausula join
     *
     * @var string
     */
    protected $join = '';

    /**
     * Guarda os dados que serão usados na clausula group
     *
     * @var string
     */
    protected $group = '';

    /**
     * Constructor
     *
     * @param Transaction $transaction
     * @param mixed $fields
     */
    final public function __construct(Transaction $transaction, $fields = null)
    {
        $this->transaction = $transaction;
        if (!empty($fields)) {
            $this->fields = $fields;
        }
    }

    /**
     * Cria uma claususa where
     *
     * @param mixed $data
     * @return self
     */
    final public function where(array $where) : self
    {
        $this->where[] = $where;
        return $this;
    }

    /**
     * Seta a tabela corrente
     *
     * @param string $table
     * @return void
     */
    final public function table(string $table) : self
    {
        $this->table = addslashes($table);
        return $this;
    }

    /**
     * Retorna os dados da clausula where
     *
     * @return Where
     */
    final protected function getWhere() : Where
    {
        return new Where($this->where);
    }

    /**
     * Retorna os dados da clausula join
     *
     * @return Join
     */
    final protected function getJoin() : Join
    {
        return new Join($this->join);
    }

    /**
     * Retorna os dados da clausula group
     *
     * @return Group
     */
    final protected function getGroup() : Group
    {
        return new Group($this->group);
    }

    /**
     * Checa se os campos estão formatados corretamente
     *
     * @return bool
     */
    final public function checkFields() : bool
    {
        if (empty($this->fields)) {
            throw new \Exception("Erro ao processar a query os campos não podem ser vazios");
        }

        if (is_array($this->fields)) {
            $this->prepare($this->fields);
            return true;
        }

        $this->fields = $this->escape($this->fields);
        return true;
    }

    /**
     * Checa se a tabela esta preenchida corretamente
     *
     * @return void
     */
    final public function checkTable() : void
    {
        if (empty($this->table)) {
            throw new \Exception("Tabela não encontrada", 500);
        }
    }

    /**
     * Itera pelos elemantos do statement
     * Executa o escape da query
     * @param arary $data
     * @return void
     */
    protected function prepare(array $data) : void
    {
        $prepared = [];
        foreach ($data as $key => $value) {
            if (is_scalar($value)) {
                $this->bindParam[] = str_pad($key, strlen($key) + 1, ':', STR_PAD_LEFT);
                $this->values[] = $this->escape($value);
                $prepared[$key] = $this->escape($key);
            }
        }

        $this->fields = array_keys($prepared);
    }

    /**
     * Executa o escape da query
     *
     * @param mixed $value
     * @return mixed
     */
    protected function escape($value)
    {
        if (!is_scalar($value)) {
            return "null";
        }

        if (is_string($value) and (!empty($value))) {
            return addslashes($value);
        }

        if (is_bool($value)) {
            return $value ? 'true': 'false';
        }

        if ($value !== '') {
            return $value;
        }

        return "null";
    }

    /**
     * Executa a query
     *
     * @param array $whereClause
     * @return PDOStatement
     */
    abstract public function execute() : PDOStatement;
}
