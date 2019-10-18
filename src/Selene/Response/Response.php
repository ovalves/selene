<?php
/**
 * @copyright   2019 - Selene
 * @author      Vinicius Oliveira <vinicius_o.a@live.com>
 * @category    Micro Framework
 * @since       2019-10-12
 */

namespace Selene\Response;

/**
 * Classe que lida com Server Requests
 */
class Response extends ResponseAbstract
{
    /**
     * Guarda os dados do header da response
     *
     * @var string
     */
    protected $headerName;

    /**
     * Guarda os dados do status da response
     *
     * @var int
     */
    protected $statusCode;

    /**
     * Guarda os dados do mensagem da response
     *
     * @var string
     */
    protected $reasonPhrase;

    /**
     * Guarda os dados do protocolo da response
     *
     * @var string
     */
    protected $protocolVersion;

    /**
     * Guarda os dados da página de redirecionamento da response
     *
     * @var string
     */
    protected $redirectToPage;

    /**
     * Seta a página de redirecionamento
     *
     * @param string $page
     * @return self
     */
    public function withRedirectTo($page) : self
    {
        $this->redirectToPage = $page;
        return $this;
    }

    /**
     * Seta a página de redirecionamento
     *
     * @return self
     */
    public function setUnauthorized() : self
    {
        $this->unauthorized = true;
        return $this;
    }

    /**
     * Seta a página de redirecionamento
     *
     * @return bool
     */
    public function isUnauthorized() : bool
    {
        return (bool) $this->unauthorized;
    }

    /**
     * Faz o redirecionamento
     *
     * @return void
     */
    public function redirectToLoginPage() : void
    {
        header("Location: {$this->redirectToPage}");
        die;
    }
}
