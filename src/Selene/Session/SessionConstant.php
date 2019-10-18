<?php
/**
 * @copyright   2019 - Selene
 * @author      Vinicius Oliveira <vinicius_o.a@live.com>
 * @category    Micro Framework
 * @since       2019-10-12
 */

namespace  Selene\Session;

/**
 * Define as constantes usadas na sessãorouter
 */
class SessionConstant
{
    /**
     * Define o nome da tabela de sessão
     */
    const SESSION_TABLE   = 'session';
    const USER_ID         = 'user_id';
    const UPDATED_AT      = 'update_at';
    const CREATED_AT      = 'created_at';
    const EXPIRATION_TIME = 'expiration_time';
    const REFRESH_TIME    = 'refresh_time';
}
