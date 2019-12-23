<?php
/**
 * @copyright   2019 - Selene
 * @author      Vinicius Oliveira <vinicius_o.a@live.com>
 * @category    Micro Framework
 * @since       2019-02-17
 */

namespace Selene\Gateway;

use Psr\Container\ContainerInterface;
use Selene\Database\Connection;
use Selene\Database\Transaction;
use Selene\Database\DatabaseConstant;
use Selene\Log\Logger;
use Selene\Container\ServiceContainer;

/**
 * Responsável por conectar a model e a base de dados
 */
trait GatewayDatabaseConnectorAwareTrait
{
    /**
     * Constructor
     */
    protected function __construct()
    {
        $this->makeConnectionContainer();
        $this->makeLoggerContainer();
        $this->makeTransactionContainer();
    }

    /**
     * Retorna a transaction ativa
     *
     * @return Transaction
     */
    protected function getTransaction() : Transaction
    {
        return $this->container->get(ServiceContainer::TRANSACTION);
    }

    /**
     * Retorna o logger ativa
     *
     * @return Logger
     */
    protected function getLogger() : Logger
    {
        return $this->container->get(ServiceContainer::LOGGER);
    }

    /**
     * Cria o container da connection
     *
     * @return void
     */
    private function makeConnectionContainer() : void
    {
        $this->container->setPrefix(ServiceContainer::CONNECTION)->set(
            Connection::class
        );
    }

    /**
     * Cria o container de logger
     *
     * @return void
     */
    private function makeLoggerContainer() : void
    {
        $this->container->setPrefix(ServiceContainer::LOGGER)->set(
            Logger::class
        );
    }

    /**
     * Cria o container da transaction
     *
     * @return void
     */
    private function makeTransactionContainer() : void
    {
        $this->container->setPrefix(ServiceContainer::TRANSACTION)->set(
            Transaction::class,
            [
                $this->container->get(ServiceContainer::APPLICATION_CONFIG),
                $this->container->get(ServiceContainer::CONNECTION)
            ]
        );
    }
}
