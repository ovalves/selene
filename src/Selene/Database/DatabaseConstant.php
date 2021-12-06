<?php
/**
 * @copyright   2019 - Selene
 * @author      Vinicius Oliveira <vinicius_o.a@live.com>
 * @category    Micro Framework
 * @since       2019-02-09
 */

namespace  Selene\Database;

/**
 * Define as constantes usadas no router.
 */
class DatabaseConstant
{
    /**
     * Database default config.
     */
    public const DEFAULT_DB = 'default';
    public const DB_PORT = 'db_port';
    public const DB_HOST = 'db_host';
    public const DB_NAME = 'db_name';
    public const DB_USER = 'db_user';
    public const DB_PASS = 'db_pass';

    /**
     * Database default ports.
     */
    public const MYSQL_PORT = '3306';
    public const PGSQL_PORT = '5432';

    /**
     * Available connection types.
     */
    public const MYSQL = 'mysql';
    public const SQLITE = 'sqlite';
    public const MONGO = 'mongo';
    public const MSSQL = 'mssql';
    public const PGSQL = 'pgsql';

    /**
     * Expression types.
     */
    public const INSERT = 'insert';
    public const SELECT = 'select';
    public const UPDATE = 'update';
    public const DELETE = 'delete';
}
