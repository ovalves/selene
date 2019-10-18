<?php
/**
 * @copyright   2019 - Selene
 * @author      Vinicius Oliveira <vinicius_o.a@live.com>
 * @category    Micro Framework
 * @since       2019-02-16
 */

namespace Selene\Request;

use Selene\Request\RequestException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\UriInterface;
use Psr\Http\Message\StreamInterface;

/**
 * Classe que lida com Server Requests
 */
class Request extends RequestAbstract
{
    /**
     * Retorna o documento root
     *
     * @return string
     */
    public function getDocumentRoot() : string
    {
        return $this->request['DOCUMENT_ROOT'];
    }

    /**
    * Retorna o dados do http get
    *
    * @return array
    */
    public function getGetParams() : array
    {
        return $this->get;
    }

    /**
    * Retorna o dados do http post
    *
    * @return array
    */
    public function getPostParams() : array
    {
        return $this->post;
    }

    /**
    * Retorna o dados do http server
    *
    * @return array
    */
    public function getServerParams() : array
    {
        return $this->server;
    }

    /**
    * Retorna o dados do http request
    *
    * @return array
    */
    public function getRequestParams() : array
    {
        return $this->request;
    }
}
