<?php
/**
 * @copyright   2019 - Selene
 * @author      Vinicius Oliveira <vinicius_o.a@live.com>
 * @category    Micro Framework
 * @since       2019-02-17
 */

namespace Selene\Database;

use PDO;
use Psr\Container\ContainerInterface;
use Selene\Config\ApplicationConfig;

/**
 * Responsável por executar as transações com a base de dados.
 */
final class Transaction
{
    /**
     * Guarda a conexão ativa.
     *
     * @var Connection
     */
    private $connection;

    /**
     * Undocumented variable.
     *
     * @var [type]
     */
    private $appConfig;

    /**
     * Constructor.
     */
    public function __construct(ContainerInterface $container, ApplicationConfig $appConfig, Connection $connection)
    {
        $this->container = $container;
        $this->appConfig = $appConfig;
        $this->connection = $connection;
    }

    /**
     * Abre uma conexão com a base de dados.
     */
    public function open(string $dbType = DatabaseConstant::DEFAULT_DB): PDO
    {
        if (empty($this->connection)) {
            throw new DatabaseException('Não há conexão ativa');
        }

        if ($this->connection instanceof PDO) {
            return $this->connection;
        }

        $this->connection = $this->connection->open($this->appConfig, $dbType);

        return $this->connection;
    }

    /**
     * Retorna a conexão ativa da transação.
     */
    public function get(): self
    {
        return $this;
    }

    /**
     * Desfaz todas operações realizadas na transação.
     */
    public function rollback(): void
    {
        if (!$this->connection) {
            throw new DatabaseException('Não há conexão ativa');
        }

        $this->connection->rollback();
        $this->connection = null;
    }

    /**
     * Aplica todas operações realizadas e fecha a transação.
     */
    public function close(): void
    {
        if (!$this->connection) {
            throw new DatabaseException('Não há conexão ativa');
        }

        $this->connection->commit();
        $this->connection = null;
    }
}
