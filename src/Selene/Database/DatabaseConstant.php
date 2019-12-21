<?php
/**
 * @copyright   2019 - Selene
 * @author      Vinicius Oliveira <vinicius_o.a@live.com>
 * @category    Micro Framework
 * @since       2019-02-09
 */

namespace  Selene\Database;

/**
 * Define as constantes usadas no router
 */
class DatabaseConstant
{
    /**
     * Database default config
     */
    const DEFAULT_DB  = 'default';
    const DB_PORT     = 'db_port';
    const DB_HOST     = 'db_host';
    const DB_NAME     = 'db_name';
    const DB_USER     = 'db_user';
    const DB_PASS     = 'db_pass';

    /**
     * Database default ports
     */
    const MYSQL_PORT = '3306';
    const PGSQL_PORT = '5432';

    /**
     * Available connection types
     */
    const MYSQL  = 'mysql';
    const SQLITE = 'sqlite';
    const MONGO  = 'mongo';
    const MSSQL  = 'mssql';
    const PGSQL  = 'pgsql';

    /**
     * Expression types
     */
    const INSERT = 'insert';
    const SELECT = 'select';
    const UPDATE = 'update';
    const DELETE = 'delete';
}
