<?php
/**
 * @copyright   2019 - Selene
 * @author      Vinicius Oliveira <vinicius_o.a@live.com>
 * @category    Micro Framework
 * @since       2019-10-12
 */

namespace Selene\Middleware\Handler;

use Selene\App\AppCreator;
use Selene\Response\Response;
use Fig\Http\Message\StatusCodeInterface as StatusCode;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Middleware de autenticação
 */
final class Auth implements MiddlewareInterface
{
    /**
     * Processa o middleware e chama o próximo da fila
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
    {
        $auth = AppCreator::container()->get(AppCreator::AUTH);

        $auth->setRequest($request);

        if (!$auth->isAuthenticated()) {
            return (new Response)
                        ->withStatus(401, 'unauthorized')
                        ->withRedirectTo($auth->redirectToLoginPage())
                        ->setUnauthorized();
        }

        return $handler->process($request, $handler);
    }
}