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
     * Constructor
     *
     * @param SplQueue $queue
     * @param RequestHandlerInterface $fallbackHandler
     */
    public function __construct(SplQueue $queue, RequestHandlerInterface $fallbackHandler)
    {
        $this->queue           = clone $queue;
        $this->fallbackHandler = $fallbackHandler;
    }

    /**
     * Cria uma nova instancia do delegator e executa o middleware
     *
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function process($request, RequestHandlerInterface $handler) : ResponseInterface
    {
        return (new self($this->queue, $handler))->handle($request);
    }

    /**
     * Executa os middlewares do pipeline
     *
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function handle($request) : ResponseInterface
    {
        if ($this->queue->isEmpty()) {
            return $this->fallbackHandler->handle($request);
        }

        $middleware = $this->queue->dequeue();
        return $middleware->process($request, $this);
    }
}