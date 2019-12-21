<?php
/**
 * @copyright   2019 - Selene
 * @author      Vinicius Oliveira <vinicius_o.a@live.com>
 * @category    Micro Framework
 * @since       2019-02-09
 */

namespace Selene\Middleware;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Selene\Middleware\Handler\EmptyPipelineHandler;
use SplQueue;

/**
 * Responsável por adicionar os handlers a fila de pipeline de middlewares
 */
final class Middleware implements MiddlewareInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var SplQueue
     */
    protected $pipeline;

    /**
     * Initializes the queue.
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->pipeline  = new SplQueue();
    }

    /**
     * Executa os middlewares do pipeline
     *
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        return $this->process($request, new EmptyPipelineHandler(__CLASS__));
    }

    /**
     * Cria uma nova instância do delegator e executa o middleware
     *
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
    {
        return (new Delegator($this->pipeline, $handler))->handle($request);
    }

    /**
     * Adiciona um handler ao pipeline de middleware
     *
     * @param MiddlewareInterface $middleware
     * @return self
     */
    public function add(MiddlewareInterface $middleware) : self
    {
        $class = new \ReflectionClass($middleware);

        if ($class->hasMethod('serviceContainer')) {
            $method = new \ReflectionMethod($middleware, 'serviceContainer');
            $method->invoke($middleware, $this->container);
        }

        $this->pipeline->enqueue($middleware);
        return $this;
    }
}
