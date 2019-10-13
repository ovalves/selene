<?php
/**
 * @copyright   2019 - Vindite
 * @author      Vinicius Oliveira <vinicius_o.a@live.com>
 * @category    Micro Framework
 * @since       2019-10-12
 */

namespace Vindite\Session;

use Psr\Http\Message\ServerRequestInterface;
use Vindite\App\AppCreator;

/**
 * Gerencia o registro da seção
 */
class Session
{
    /**
     * Guarda os dados da sessão
     *
     * @var array
     */
    protected $session;

    /**
     * inicializa uma seção
     */
    public function __construct()
    {
        // if (!session_id()) {
        //     session_start();
        // }

        $this->session = $_SESSION;

        AppCreator::container(SessionConstant::SESSION_TABLE)->set(
            SessionGateway::class
        );
    }

    /**
     * Verifica se existe secao registrada
     *
     * @return bool
     */
    public function hasSession() : bool
    {
        return (bool) (!empty(session_id()));
    }

    public function shouldRegenerateSessionId()
    {
        $sessionGateway = AppCreator::container()->get(SessionConstant::SESSION_TABLE);
        $data = $sessionGateway->getSessionById(session_id());
    }

    public function regenerateSessionId()
    {

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
            $this->session[$key] = $value;
        }

        return true;
    }

    /**
     * Retorna uma variável da seção
     *
     * @param string $var
     * @return string
     */
    public function getValue($var) : string
    {
        if (isset($this->session[$var])) {
            return $this->session[$var];
        }
    }

    /**
     * Destrói os dados de uma seção
     */
    public function freeSession()
    {
        $this->session = [];
        session_destroy();
    }
}
