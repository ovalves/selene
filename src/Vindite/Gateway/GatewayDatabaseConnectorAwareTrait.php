<?php
/**
 * @copyright   2019 - Vindite
 * @author      Vinicius Oliveira <vinicius_o.a@live.com>
 * @category    Micro Framework
 * @since       2019-02-17
 */

namespace Vindite\Gateway;

use Vindite\App\AppCreator;
use Vindite\Database\Connection;
use Vindite\Database\Transaction;
use Vindite\Database\DatabaseConstant;
use Vindite\Log\Logger;

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
        return AppCreator::container()->get(DatabaseConstant::TRANSACTION);
    }

    /**
     * Retorna o logger ativa
     *
     * @return Logger
     */
    protected function getLogger() : Logger
    {
        return AppCreator::container()->get(DatabaseConstant::LOGGER);
    }

    /**
     * Cria o container da connection
     *
     * @return void
     */
    private function makeConnectionContainer() : void
    {
        AppCreator::container(DatabaseConstant::CONNECTION)->set(
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
        AppCreator::container(DatabaseConstant::LOGGER)->set(
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
        AppCreator::container(DatabaseConstant::TRANSACTION)->set(
            Transaction::class,
            [
                DatabaseConstant::DATABASE_NAME,
                AppCreator::container()->get(DatabaseConstant::CONNECTION)
            ]
        );
    }
}
