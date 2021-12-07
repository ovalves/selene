<?php
/**
 * @copyright   2019 - Selene
 * @author      Vinicius Oliveira <vinicius_o.a@live.com>
 * @category    Micro Framework
 * @since       2019-10-12
 */

namespace Selene\Session;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Selene\Config\ConfigConstant;

/**
 * Gerencia o registro da seção.
 */
class Session
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * Guarda oo objeto de conexao com a tabela de sessao.
     *
     * @var SessionGateway
     */
    protected $gateway;

    /**
     * Constructor.
     */
    public function __construct(ContainerInterface $container)
    {
        if (!session_id()) {
            session_start();
        }

        $this->container = $container;
    }

    /**
     * Verifica se existe secao registrada.
     */
    public function hasSession(): bool
    {
        return isset($_SESSION[SessionConstant::USER_ID]) ? true : false;
    }

    /**
     * Verifica se deve regerar o id de sessao.
     */
    public function shouldRegenerateSessionId(): bool
    {
        if ($_SESSION[SessionConstant::REFRESH_TIME] <= strtotime('now')) {
            return true;
        }

        return false;
    }

    /**
     * Regera o id de sessao.
     */
    public function regenerateSessionId(): void
    {
        session_regenerate_id();
    }

    /**
     * Armazena um array de valores na seção.
     */
    public function setValue(array $data): bool
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
     * Retorna uma variável da seção.
     */
    public function getValue(string $var = null) : string|array
    {
        if (isset($_SESSION[$var])) {
            return $_SESSION[$var];
        }

        return $_SESSION;
    }

    /**
     * Retorna uma variável da sessão do usuário
     */
    public function getUserData(string $var = null) : string|array
    {
        if (isset($_SESSION[SessionConstant::USER_DATA][$var])) {
            return $_SESSION[SessionConstant::USER_DATA][$var];
        }

        return $_SESSION;
    }

    /**
     * Destrói os dados da seção
     */
    public function freeSession(): void
    {
        unset($_SESSION);
        session_destroy();
    }

    /**
     * Retorna o gateway de conexao com a tabela de sessao.
     */
    protected function getGateway(): SessionGateway
    {
        if (!isset($this->gateway[SessionConstant::SESSION_TABLE])) {
            $this->container->setPrefix(SessionConstant::SESSION_TABLE)->set(
                SessionGateway::class
            );

            $this->gateway = $this->container->get(SessionConstant::SESSION_TABLE);
        }

        return $this->gateway;
    }
}
