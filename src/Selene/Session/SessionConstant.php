<?php
/**
 * @copyright   2019 - Selene
 * @author      Vinicius Oliveira <vinicius_o.a@live.com>
 * @category    Micro Framework
 * @since       2019-10-12
 */

namespace  Selene\Session;

/**
 * Define as constantes usadas na sessãorouter.
 */
class SessionConstant
{
    /**
     * Define o nome da tabela de sessão.
     */
    public const SESSION_TABLE = 'session';
    public const USER_ID = 'user_id';
    public const UPDATED_AT = 'update_at';
    public const CREATED_AT = 'created_at';
    public const EXPIRATION_TIME = 'expiration_time';
    public const REFRESH_TIME = 'refresh_time';
}
