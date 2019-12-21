<?php
/**
 * @copyright   2019 - Selene
 * @author      Vinicius Oliveira <vinicius_o.a@live.com>
 * @category    Micro Framework
 * @since       2019-02-17
 */

namespace Selene\Database;

use PDO;
use Selene\Config\ApplicationConfig;
use Selene\Database\DatabaseException;
use Selene\Config\ConfigConstant;

/**
 * Responsável por criar uma conexão com a base de dados
 */
final class Connection
{
    /**
     * Recebe o nome do conector de BD e instancia o objeto PDO
     *
     * @return PDO
     */
    public function open(ApplicationConfig $appConfig, string $dbType = DatabaseConstant::DEFAULT_DB) : PDO
    {
        $appConfig = $appConfig->getConfig(ConfigConstant::DATABASE);
        if (empty($appConfig)) {
            throw new DatabaseException('Error - Configuration of database connection not found');
        }

        $driverType = $appConfig[$dbType];

        if (empty($driverType)) {
            throw new DatabaseException('Error - Driver of database connection not found');
        }

        $user = isset($appConfig[$driverType][DatabaseConstant::DB_USER])
            ? $appConfig[$driverType][DatabaseConstant::DB_USER]
            : null;

        $pass = isset($appConfig[$driverType][DatabaseConstant::DB_PASS])
                        ? $appConfig[$driverType][DatabaseConstant::DB_PASS]
                        : null;

        $name = isset($appConfig[$driverType][DatabaseConstant::DB_NAME])
                        ? $appConfig[$driverType][DatabaseConstant::DB_NAME]
                        : null;

        $host = isset($appConfig[$driverType][DatabaseConstant::DB_HOST])
                        ? $appConfig[$driverType][DatabaseConstant::DB_HOST]
                        : null;

        $port = isset($appConfig[$driverType][DatabaseConstant::DB_PORT])
                        ? $appConfig[$driverType][DatabaseConstant::DB_PORT]
                        : null;

        switch ($driverType)
        {
            case DatabaseConstant::PGSQL:
                $port = $port ? $port : DatabaseConstant::PGSQL_PORT;
                $conn = new PDO("pgsql:dbname={$name}; user={$user}; password={$pass};
                        host=$host;port={$port}");
                break;

            case DatabaseConstant::MYSQL:
                $port = $port ? $port : DatabaseConstant::MYSQL_PORT;
                $conn = new PDO("mysql:host={$host};port={$port};dbname={$name}", $user, $pass);
                break;

            case DatabaseConstant::SQLITE:
                $conn = new PDO("sqlite:{$name}");
                break;

            case DatabaseConstant::MSSQL:
                $conn = new PDO("mssql:host={$host},1433;dbname={$name}", $user, $pass);
                break;
        }

        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    }
}
