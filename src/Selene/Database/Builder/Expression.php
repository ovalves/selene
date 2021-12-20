<?php
/**
 * @copyright   2019 - Selene
 * @author      Vinicius Oliveira <vinicius_o.a@live.com>
 * @category    Micro Framework
 * @since       2019-02-23
 */

namespace Selene\Database\Builder;

use Selene\Database\Transaction;

/**
 * Responsavel por executar os diferentes tipos de statement.
 */
abstract class Expression
{
    /**
     * Guarda a transação ativa.
     *
     * @var Transaction
     */
    protected $transaction;

    /**
     * Constructor.
     */
    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
    }

    /**
     * Guarda o nome da tabela.
     *
     * @var string
     */
    protected $table;

    /**
     * Guarda o parâmetro da query a ser executada.
     *
     * @var string
     */
    protected $param;

    /**
     * Guarda os campos da clausula.
     *
     * @var array
     */
    protected $fields;

    /**
     * Retorna uma instancia da classe select.
     *
     * @param mixed $fields
     */
    public function select($fields) : Select
    {
        return new Select($this->transaction, $fields);
    }

    /**
     * Retorna uma instancia da classe insert.
     */
    public function insert(array $fields) : Insert
    {
        return new Insert($this->transaction, $fields);
    }

    /**
     * Retorna uma instancia da classe Delete.
     */
    public function delete() : Delete
    {
        return new Delete($this->transaction);
    }

    /**
     * Retorna uma instancia da classe Update.
     */
    public function update(array $fields) : Update
    {
        return new Update($this->transaction, $fields);
    }
}
