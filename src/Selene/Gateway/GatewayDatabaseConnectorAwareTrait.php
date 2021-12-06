<?php
/**
 * @copyright   2019 - Selene
 * @author      Vinicius Oliveira <vinicius_o.a@live.com>
 * @category    Micro Framework
 * @since       2019-02-17
 */

namespace Selene\Gateway;

use Selene\Container\ServiceContainer;
use Selene\Database\Connection;
use Selene\Database\Transaction;
use Selene\Log\Logger;

/**
 * Responsável por conectar a model e a base de dados.
 */
trait GatewayDatabaseConnectorAwareTrait
{
    /**
     * Constructor.
     */
    protected function __construct()
    {
        $this->makeConnectionContainer();
        $this->makeLoggerContainer();
        $this->makeTransactionContainer();
    }

    /**
     * Retorna a transaction ativa.
     */
    protected function getTransaction(): Transaction
    {
        return $this->container->get(ServiceContainer::TRANSACTION);
    }

    /**
     * Retorna o logger ativa.
     */
    protected function getLogger(): Logger
    {
        return $this->container->get(ServiceContainer::LOGGER);
    }

    /**
     * Cria o container da connection.
     */
    private function makeConnectionContainer(): void
    {
        $this->container->setPrefix(ServiceContainer::CONNECTION)->set(
            Connection::class
        );
    }

    /**
     * Cria o container de logger.
     */
    private function makeLoggerContainer(): void
    {
        $this->container->setPrefix(ServiceContainer::LOGGER)->set(
            Logger::class
        );
    }

    /**
     * Cria o container da transaction.
     */
    private function makeTransactionContainer(): void
    {
        $this->container->setPrefix(ServiceContainer::TRANSACTION)->set(
            Transaction::class,
            [
                $this->container->get(ServiceContainer::APPLICATION_CONFIG),
                $this->container->get(ServiceContainer::CONNECTION),
            ]
        );
    }
}
