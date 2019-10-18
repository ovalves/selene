<?php
/**
 * @copyright   2019 - Selene
 * @author      Vinicius Oliveira <vinicius_o.a@live.com>
 * @category    Micro Framework
 * @since       2019-02-17
 */

namespace Selene\Database;

use PDO;
use Selene\Database\DatabaseException;

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
    public function open(string $configFile) : PDO
    {
        if (!\file_exists("App/Config/{$configFile}.ini")) {
            throw new DatabaseException(
                \sprintf(
                    "O arquivo de configuração (%s) de base de dados não foi encontrado",
                    $configFile
                )
            );
        }

        $configFile = \parse_ini_file("App/Config/{$configFile}.ini");

        // lê as informações contidas no arquivo
        $user = isset($configFile['user']) ? $configFile['user'] : null;
        $pass = isset($configFile['pass']) ? $configFile['pass'] : null;
        $name = isset($configFile['name']) ? $configFile['name'] : null;
        $host = isset($configFile['host']) ? $configFile['host'] : null;
        $port = isset($configFile['port']) ? $configFile['port'] : null;

        return new PDO("mysql:host={$host};port={$port};dbname={$name}", $user, $pass);
    }
}
