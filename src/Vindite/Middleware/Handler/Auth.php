<?php
/**
 * @copyright   2019 - Vindite
 * @author      Vinicius Oliveira <vinicius_o.a@live.com>
 * @category    Micro Framework
 * @since       2019-10-12
 */

namespace Vindite\Middleware\Handler;

use Vindite\App\AppCreator;
use Vindite\Response\Response;
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

        $auth->authenticate();

        return $handler->process($request, $handler);
    }
}