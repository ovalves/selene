<?php
/**
 * @copyright   2019 - Vindite
 * @author      Vinicius Oliveira <vinicius_o.a@live.com>
 * @category    Micro Framework
 * @since       2019-02-09
 */

namespace Vindite\Middleware\Handler;

use Fig\Http\Message\StatusCodeInterface as StatusCode;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use \Vindite\Session\Session as FrameworkSession;

/**
 * Middleware de sessão
 */
final class Session implements MiddlewareInterface
{
    /**
     * Guarda o objeto da sessão
     *
     * @var Session
     */
    protected $session;

    /**
     * Processa o middleware e chama o próximo da fila
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
    {
        $this->session = new FrameworkSession;

        if ($this->session->hasSession()) {
            return $handler->process($request, $handler);
        }
    }
}
