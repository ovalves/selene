<?php
/**
 * @copyright   2019 - Selene
 * @author      Vinicius Oliveira <vinicius_o.a@live.com>
 * @category    Micro Framework
 * @since       2019-02-09
 */

namespace Selene\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use SplQueue;

/**
 * Iterate a queue of middlewares and execute them.
 */
final class Delegator implements RequestHandlerInterface
{
    /**
     * @var RequestHandlerInterface
     */
    private $fallbackHandler;

    /**
     * @var SplQueue
     */
    private $queue;

    /**
     * Constructor.
     */
    public function __construct(SplQueue $queue, RequestHandlerInterface $fallbackHandler)
    {
        $this->queue = clone $queue;
        $this->fallbackHandler = $fallbackHandler;
    }

    /**
     * Cria uma nova instancia do delegator e executa o middleware.
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        return (new self($this->queue, $handler))->handle($request);
    }

    /**
     * Executa os middlewares do pipeline.
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        if ($this->queue->isEmpty()) {
            return $this->fallbackHandler->handle($request);
        }

        $middleware = $this->queue->dequeue();

        return $middleware->process($request, $this);
    }
}
