<?php
/**
 * @copyright   2019 - Selene
 * @author      Vinicius Oliveira <vinicius_o.a@live.com>
 * @category    Micro Framework
 * @since       2019-02-16
 */

namespace Selene\Request;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Classe que lida com Server Requests.
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
    use RequestSanitizerAwareTrait;

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * Guarda os dados GET da requisição.
     *
     * @var array
     */
    protected $get;

    /**
     * Guarda os dados POST da requisição.
     *
     * @var array
     */
    protected $post;

    /**
     * Guarda os dados do SERVER da requisição.
     *
     * @var array
     */
    protected $server;

    /**
     * Guarda os dados REQUEST da requisição.
     *
     * @var array
     */
    protected $request;

    /**
     * Constructor.
     */
    public function __construct(ContainerInterface $container, array $get, array $post, array $server, array $request)
    {
        $this->container = $container;
        $this->get = $get;
        $this->post = $post;
        $this->server = $server;
        $this->request = $request;
    }
}
