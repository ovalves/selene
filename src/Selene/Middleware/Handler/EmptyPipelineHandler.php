<?php
/**
 * @copyright   2019 - Selene
 * @author      Vinicius Oliveira <vinicius_o.a@live.com>
 * @category    Micro Framework
 * @since       2019-02-09
 */

namespace Selene\Middleware\Handler;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Selene\Response\Response;

final class EmptyPipelineHandler implements RequestHandlerInterface
{
    /**
     * Cria uma nova instancia do delegator e executa o middleware.
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        return new Response();
    }

    /**
     * Undocumented function.
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return $this->process($request, $this);
    }
}
