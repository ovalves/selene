<?php
/**
 * @copyright   2019 - Selene
 * @author      Vinicius Oliveira <vinicius_o.a@live.com>
 * @category    Micro Framework
 * @since       2019-10-12
 */

namespace Selene\Middleware\Handler;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Selene\Container\ServiceContainer;
use Selene\Response\Response;

/**
 * Middleware de autenticação.
 */
final class Auth implements MiddlewareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * Enable middleware to get an instance of service Container.
     *
     * @return void
     */
    public function serviceContainer(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Processa o middleware e chama o próximo da fila.
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $auth = $this->container->get(ServiceContainer::AUTH);

        $auth->setRequest($request);

        if (!$auth->isAuthenticated()) {
            return (new Response())
                        ->withStatus(401, 'unauthorized')
                        ->withRedirectTo($auth->redirectToLoginPage())
                        ->setUnauthorized();
        }

        return $handler->process($request, $handler);
    }
}
