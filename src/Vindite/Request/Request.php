<?php
/**
 * @copyright   2019 - Vindite
 * @author      Vinicius Oliveira <vinicius_o.a@live.com>
 * @category    Micro Framework
 * @since       2019-02-16
 */

namespace Vindite\Request;

use Vindite\Request\RequestException;
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
}
