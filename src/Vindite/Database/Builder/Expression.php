<?php
/**
 * @copyright   2019 - Vindite
 * @author      Vinicius Oliveira <vinicius_o.a@live.com>
 * @category    Micro Framework
 * @since       2019-02-23
 */

namespace Vindite\Database\Builder;

use Vindite\Database\Transaction;
use Vindite\Database\DatabaseConstant;
use Vindite\Database\Builder\Select;
use Vindite\Database\Builder\Insert;

/**
 * Responsavel por executar os diferentes tipos de statement
 */
abstract class Expression
{
    /**
     * Guarda a transação ativa
     *
     * @var Transaction
     */
    protected $transaction;

    /**
     * Constructor
     *
     * @param Transaction $transaction
     */
    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
    }

    /**
     * Guarda o nome da tabela
     *
     * @var string
     */
    protected $table;

    /**
     * Guarda o parâmetro da query a ser executada
     *
     * @var string
     */
    protected $param;

    /**
     * Guarda os campos da clausula
     *
     * @var array
     */
    protected $fields;

    /**
     * Retorna uma instancia da classe select
     *
     * @param mixed $fields
     * @return Select
     */
    public function select($fields) : Select
    {
        return new Select($this->transaction, $fields);
    }

    /**
     * Retorna uma instancia da classe insert
     *
     * @param array $fields
     * @return Insert
     */
    public function insert(array $fields) : Insert
    {
        return new Insert($this->transaction, $fields);
    }

    /**
     * Retorna uma instancia da classe Delete
     *
     * @return Delete
     */
    public function delete() : Delete
    {
        return new Delete($this->transaction);
    }

    /**
     * Retorna uma instancia da classe Update
     *
     * @return Update
     */
    public function update(array $fields) : Update
    {
        return new Update($this->transaction, $fields);
    }
}
