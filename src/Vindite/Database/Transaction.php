<?php
/**
 * @copyright   2019 - Vindite
 * @author      Vinicius Oliveira <vinicius_o.a@live.com>
 * @category    Micro Framework
 * @since       2019-02-17
 */

namespace Vindite\Database;

use PDO;
use Vindite\Database\Connection;

/**
 * Responsável por executar as transações com a base de dados
 */
final class Transaction
{
    /**
     * Guarda a conexão ativa
     *
     * @var Connection
     */
    private $connection;

    /**
     * Constructor
     *
     * @param string $database
     * @param Connection $connection
     */
    public function __construct(string $database, Connection $connection)
    {
        $this->database   = $database;
        $this->connection = $connection;
    }

    /**
     * Abre uma conexão com a base de dados
     */
    public function open() : PDO
    {
        if (empty($this->connection)) {
            throw new Exception("Error Processing Request", 1);
        }

        if ($this->connection instanceof PDO) {
            return $this->connection;
        }

        $this->connection = $this->connection->open($this->database);
        return $this->connection;
    }

    /**
     * Retorna a conexão ativa da transação
     */
    public function get() : self
    {
        return $this;
    }

    /**
     * Desfaz todas operações realizadas na transação
     */
    public function rollback() : void
    {
        if (!$this->connection) {
            throw new Exception("Error Processing Request", 1);
        }

        $this->connection->rollback();
        $this->connection = null;
    }

    /**
     * Aplica todas operações realizadas e fecha a transação
     */
    public function close() : void
    {
        if (!$this->connection) {
            throw new Exception("Error Processing Request", 1);
        }

        $this->connection->commit();
        $this->connection = null;
    }
}
