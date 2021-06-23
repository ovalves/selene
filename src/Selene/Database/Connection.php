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
use Selene\Config\ConfigConstant;

/**
 * Responsável por criar uma conexão com a base de dados.
 */
final class Connection
{
    /**
     * Recebe o nome do conector de BD e instância o objeto PDO.
     */
    public function open(ApplicationConfig $appConfig, string $dbType = DatabaseConstant::DEFAULT_DB): PDO
    {
        $appConfig = $appConfig->getConfig(ConfigConstant::DATABASE);
        if (empty($appConfig)) {
            throw new DatabaseException('Error - Configuration of database connection not found');
        }

        $driverType = $appConfig[$dbType];

        if (empty($driverType)) {
            throw new DatabaseException('Error - Driver of database connection not found');
        }

        $user = $this->getUser($appConfig, $driverType);
        $pass = $this->getPassword($appConfig, $driverType);
        $dbname = $this->getDatabase($appConfig, $driverType);
        $host = $this->getHostname($appConfig, $driverType);
        $port = $this->getPort($appConfig, $driverType);

        switch ($driverType) {
            case DatabaseConstant::PGSQL:
                $port = $port ? $port : DatabaseConstant::PGSQL_PORT;
                $conn = new PDO("pgsql:dbname={$dbname}; user={$user}; password={$pass};
                        host=$host;port={$port}");
                break;

            case DatabaseConstant::MYSQL:
                $port = $port ? $port : DatabaseConstant::MYSQL_PORT;
                $conn = new PDO("mysql:host={$host};port={$port};dbname={$dbname}", $user, $pass);
                break;

            case DatabaseConstant::SQLITE:
                $conn = new PDO("sqlite:{$dbname}");
                break;

            case DatabaseConstant::MSSQL:
                $conn = new PDO("mssql:host={$host},1433;dbname={$dbname}", $user, $pass);
                break;
        }

        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return $conn;
    }

    private function getUser(array $appConfig, string $driverType): string | null
    {
        return isset($appConfig[$driverType][DatabaseConstant::DB_USER])
                ? $appConfig[$driverType][DatabaseConstant::DB_USER]
                : null;
    }

    private function getPassword(array $appConfig, string $driverType): string | null
    {
        return isset($appConfig[$driverType][DatabaseConstant::DB_PASS])
                ? $appConfig[$driverType][DatabaseConstant::DB_PASS]
                : null;
    }

    private function getDatabase(array $appConfig, string $driverType): string | null
    {
        return isset($appConfig[$driverType][DatabaseConstant::DB_NAME])
                ? $appConfig[$driverType][DatabaseConstant::DB_NAME]
                : null;
    }

    private function getHostname(array $appConfig, string $driverType): string | null
    {
        return isset($appConfig[$driverType][DatabaseConstant::DB_HOST])
                ? $appConfig[$driverType][DatabaseConstant::DB_HOST]
                : null;
    }

    private function getPort(array $appConfig, string $driverType): string | null
    {
        return isset($appConfig[$driverType][DatabaseConstant::DB_PORT])
                ? $appConfig[$driverType][DatabaseConstant::DB_PORT]
                : null;
    }
}
