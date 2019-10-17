<?php
/**
 * @copyright   2019 - Vindite
 * @author      Vinicius Oliveira <vinicius_o.a@live.com>
 * @category    Micro Framework
 * @since       2019-10-12
 */

namespace Vindite\Config;

/**
 * Define as constantes usadas nas configuracoes do framework
 */
class ConfigConstant
{
    /**
     * Define configurations of authentication
     */
    const AUTH                        = 'auth';
    const AUTH_HASH                   = 'auth_hash';
    const AUTH_TABLE_NAME             = "auth_table_name";
    const AUTH_LOGIN_URL              = 'auth_login_url';
    const AUTH_REDIRECT_SUCCESS_LOGIN = 'auth_redirect_success_login';
    const AUTH_REDIRECT_FAILED_LOGIN  = 'auth_redirect_failed_login';

    /**
     * Define configurations of session
     */
    const SESSION                 = 'session';
    const SESSION_TABLE_NAME      = "session_table_name";
    const SESSION_EXPIRATION_TIME = "expiration_time";
    const SESSION_REFRESH_TIME    = "refresh_time";

    /**
     * Define configurations of database
     */
    const MYSQL             = 'mysql';
    const SQLITE            = 'sqlite';
    const MONGODB           = 'mongodb';
    const DATABASE_SERVER   = 'mysql';
    const DATABASE_NAME     = "db_name";
    const DATABASE_USER     = "root";
    const DATABASE_PASSWORD = "1234";
}
