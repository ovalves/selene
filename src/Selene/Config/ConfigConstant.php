<?php
/**
 * @copyright   2019 - Selene
 * @author      Vinicius Oliveira <vinicius_o.a@live.com>
 * @category    Micro Framework
 * @since       2019-10-12
 */

namespace Selene\Config;

/**
 * Define as constantes usadas nas configuracoes do framework.
 */
class ConfigConstant
{
    /**
     * Define configurations of authentication.
     */
    public const AUTH = 'auth';
    public const AUTH_HASH = 'auth_hash';
    public const AUTH_TABLE_NAME = 'auth_table_name';
    public const AUTH_LOGIN_URL = 'auth_login_url';
    public const AUTH_REDIRECT_SUCCESS_LOGIN = 'auth_redirect_success_login';
    public const AUTH_REDIRECT_FAILED_LOGIN = 'auth_redirect_failed_login';

    /**
     * Define configurations of session.
     */
    public const SESSION = 'session';
    public const SESSION_TABLE_NAME = 'session_table_name';
    public const SESSION_EXPIRATION_TIME = 'expiration_time';
    public const SESSION_REFRESH_TIME = 'refresh_time';

    /**
     * Define configurations of database.
     */
    public const DATABASE = 'database';
    public const MYSQL = 'mysql';
    public const SQLITE = 'sqlite';
    public const MONGODB = 'mongodb';
    public const DATABASE_SERVER = 'mysql';
    public const DATABASE_NAME = 'db_name';
    public const DATABASE_USER = 'root';
    public const DATABASE_PASSWORD = '1234';

    /**
     * Define as configurações das Views.
     */
    public const ENABLE_CACHE_VIEWS = 'enable_cache_views';

    /**
     * Define as configurações dos containers de aplicação.
     */
    public const ENABLE_SESSION_CONTAINER = 'enable_session_container';
    public const ENABLE_AUTH_CONTAINER = 'enable_auth_container';
}
