<?php
/**
 * @copyright   2019 - Vindite
 * @author      Vinicius Oliveira <vinicius_o.a@live.com>
 * @category    Micro Framework
 * @since       2019-02-09
 */

namespace Vindite\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Vindite\Middleware\Handler\EmptyPipelineHandler;
use SplQueue;

/**
 * Responsável por adicionar os handlers a fila de pipeline de middlewares
 */
final class Middleware implements MiddlewareInterface
{
    /**
     * @var SplQueue
     */
    private $pipeline;

    /**
     * Initializes the queue.
     */
    public function __construct()
    {
        $this->pipeline = new SplQueue();
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
        $this->pipeline->enqueue($middleware);
        return $this;
    }
}