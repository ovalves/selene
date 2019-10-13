<?php
/**
 * @copyright   2019 - Vindite
 * @author      Vinicius Oliveira <vinicius_o.a@live.com>
 * @category    Micro Framework
 * @since       2019-10-12
 */

namespace Vindite\Auth;

use Psr\Http\Message\ServerRequestInterface;
use Vindite\Config\ConfigConstant;
use Vindite\Session\Session;
/**
 * Trata as solicitaçoes de autenticacao do framework
 */
class Auth
{
    use \Vindite\Config\ConfigAwareTrait;

    protected $session;

    /**
     * Undocumented function
     *
     * @param Session $session
     */
    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    /**
     * Seta os dados da requisição de autenticacao
     *
     * @param ServerRequestInterface $request
     * @return void
     */
    public function setRequest(ServerRequestInterface $request)
    {
        $this->request = $request;
        return $this;
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function authenticate()
    {

    }

    /**
     * Undocumented function
     *
     * @return boolean
     */
    public function isAuthenticated()
    {
        if (!$this->session->hasSession()) {
            return false;
        }

        if ($this->session->shouldRegenerateSessionId()) {
            $this->session->regenerateSessionId();
        }

        return true;
    }

    public function redirectToLoginPage()
    {
        $config = $this->loadConfig(ConfigConstant::AUTH);
        return $config[ConfigConstant::AUTH_LOGIN_URL];
    }
}