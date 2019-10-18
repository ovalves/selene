<?php
/**
 * @copyright   2019 - Selene
 * @author      Vinicius Oliveira <vinicius_o.a@live.com>
 * @category    Micro Framework
 * @since       2019-02-16
 */

namespace Selene\Request;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\UriInterface;
use Psr\Http\Message\StreamInterface;

/**
 * Classe que lida com Server Requests
 */
abstract class RequestAbstract implements ServerRequestInterface
{
    use RequestParamsAwareTrait;
    use RequestFilesAwareTrait;
    use RequestBodyAwareTrait;
    use RequestAttributeAwareTrait;
    use RequestTargetAwareTrait;
    use RequestMethodAwareTrait;
    use RequestUriAwareTrait;
    use RequestProtocolAwareTrait;
    use RequestHeaderAwareTrait;

    /**
     * Guarda os dados GET da requisição
     *
     * @var array
     */
    protected $get;

    /**
     * Guarda os dados POST da requisição
     *
     * @var array
     */
    protected $post;

    /**
     * Guarda os dados do SERVER da requisição
     *
     * @var array
     */
    protected $server;

    /**
     * Guarda os dados REQUEST da requisição
     *
     * @var array
     */
    protected $request;

    /**
     * Constructor
     *
     * @param array $get
     * @param array $post
     * @param array $server
     * @param array $request
     */
    public function __construct(array $get, array $post, array $server, array $request)
    {
        $this->get     = $get;
        $this->post    = $post;
        $this->server  = $server;
        $this->request = $request;
    }
}
