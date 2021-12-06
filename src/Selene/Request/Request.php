<?php
/**
 * @copyright   2019 - Selene
 * @author      Vinicius Oliveira <vinicius_o.a@live.com>
 * @category    Micro Framework
 * @since       2019-02-16
 */

namespace Selene\Request;

/**
 * Classe que lida com Server Requests.
 */
class Request extends RequestAbstract
{
    /**
     * Retorna o documento root.
     */
    public function getDocumentRoot(): string
    {
        return $this->request['DOCUMENT_ROOT'];
    }

    /**
     * Retorna o dados do http get.
     */
    public function getGetParams(): array
    {
        return $this->get;
    }

    /**
     * Retorna o dados do http post.
     */
    public function getPostParams(): array
    {
        return $this->post;
    }

    /**
     * Retorna o dados do http server.
     */
    public function getServerParams(): array
    {
        return $this->server;
    }

    /**
     * Retorna o dados do http request.
     */
    public function getRequestParams(): array
    {
        return $this->request;
    }

    /**
     * Retorna o dados do corpo da request.
     */
    public function getContentBody(): array
    {
        return json_decode(file_get_contents('php://input'), true, 512, \JSON_BIGINT_AS_STRING);
    }

    /**
     * Retorna o dados do da request.
     */
    public function all(): array
    {
        return array_merge($this->getPostParams(), $this->getGetParams(), $this->getContentBody());
    }
}
