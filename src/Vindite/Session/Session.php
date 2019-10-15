<?php
/**
 * @copyright   2019 - Vindite
 * @author      Vinicius Oliveira <vinicius_o.a@live.com>
 * @category    Micro Framework
 * @since       2019-10-12
 */

namespace Vindite\Session;

use Vindite\App\AppCreator;
use Vindite\Config\ConfigConstant;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Gerencia o registro da seção
 */
class Session
{
    use \Vindite\Config\ConfigAwareTrait;

    /**
     * Guarda oo objeto de conexao com a tabela de sessao
     *
     * @var SessionGateway
     */
    protected $gateway;

    /**
     * Verifica se existe secao registrada
     *
     * @return bool
     */
    public function hasSession() : bool
    {
        return isset($_SESSION[SessionConstant::USER_ID]) ? true : false;
    }

    /**
     * Verifica se deve regerar o id de sessao
     *
     * @return boolean
     */
    public function shouldRegenerateSessionId() : bool
    {
        if ($_SESSION[SessionConstant::REFRESH_TIME] <= strtotime("now")) {
            return true;
        }

        return false;
    }

    /**
     * Regera o id de sessao
     *
     * @return void
     */
    public function regenerateSessionId() : void
    {
        session_regenerate_id();
    }

    /**
     * Armazena um array de valores na seção
     *
     * @param array $data
     * @return bool
     */
    public function setValue(array $data) : bool
    {
        if (empty($data)) {
            return false;
        }

        foreach ($data as $key => $value) {
            $_SESSION[$key] = $value;
        }

        return true;
    }

    /**
     * Retorna uma variável da seção
     *
     * @param string $var
     * @return string
     */
    public function getValue(string $var) : string
    {
        if (isset($_SESSION[$var])) {
            return $_SESSION[$var];
        }
    }

    /**
     * Destrói os dados da seção
     */
    public function freeSession() : void
    {
        unset($_SESSION);
        session_destroy();
    }

    /**
     * Retorna o gateway de conexao com a tabela de sessao
     *
     * @return SessionGateway
     */
    protected function getGateway() : SessionGateway
    {
        if (!isset($this->gateway[SessionConstant::SESSION_TABLE])) {
            AppCreator::container(SessionConstant::SESSION_TABLE)->set(
                SessionGateway::class
            );

            $this->gateway = AppCreator::container()->get(SessionConstant::SESSION_TABLE);
        }

        return $this->gateway;
    }
}
