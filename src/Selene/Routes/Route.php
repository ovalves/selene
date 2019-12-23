<?php
/**
 * @copyright   2019 - Selene
 * @author      Vinicius Oliveira <vinicius_o.a@live.com>
 * @category    Micro Framework
 * @since       2019-02-09
 */

namespace Selene\Routes;

use Psr\Container\ContainerInterface;
use Psr\Http\Server\MiddlewareInterface;
use Selene\Request\Request;
use Selene\Routes\Http;
use Selene\Routes\RouteException;

/**
 * Responsável por executar as ações de roteamento da aplicação
 */
class Route
{
    use RouteAwareTrait;
    use RouteDispatcherAwareTrait;

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * Guarda os middlewares usados no roteamento
     *
     * @var MiddlewareInterface
     */
    private $middleware;

    /**
     * Guarda o nome da classe da controller que será executada
     *
     * @var string
     */
    private $controller;

    /**
     * Guarda a action que deve ser executa na controller
     *
     * @var string
     */
    private $action;

    /**
     * Guarda o objeto da request
     *
     * @var Request
     */
    private $request;

    /**
     * Guarda a fila de recursos registrados no roteador
     *
     * @var array
     */
    private $queue;

    /**
     * Define o verbo http get
     *
     * @var Get
     */
    private $get;

    /**
     * Define o verbo http post
     *
     * @var Post
     */
    private $post;

    /**
     * Define o verbo http put
     *
     * @var Put
     */
    private $put;

    /**
     * Define o verbo http patch
     *
     * @var Patch
     */
    private $patch;

    /**
     * Define o verbo http delete
     *
     * @var Delete
     */
    private $delete;

    /**
     * Constructor
     *
     * @param ContainerInterface $container
     * @param Request $request
     * @param MiddlewareInterface $middleware
     */
    public function __construct(ContainerInterface $container, Request $request, MiddlewareInterface $middleware = null)
    {
        $this->get        = new Http\Get;
        $this->put        = new Http\Put;
        $this->post       = new Http\Post;
        $this->patch      = new Http\Patch;
        $this->delete     = new Http\Delete;
        $this->queue      = [];
        $this->container  = $container;
        $this->request    = $request;
        $this->middleware = $middleware;
    }

    /**
     * Adiciona uma rota a fila do dispatcher
     *
     * @param string $resource
     * @param mixed $callback
     * @return self
     */
    public function get(string $resource, $callback) : self
    {
        $this->queue = ($this->get)($this->queue, $resource, $callback);
        return $this;
    }

    /**
     * Adiciona uma rota a fila do dispatcher
     *
     * @param string $resource
     * @param mixed $callback
     * @return self
     */
    public function post(string $resource, $callback) : self
    {
        $this->queue = ($this->post)($this->queue, $resource, $callback);
        return $this;
    }

    /**
     * Adiciona uma rota a fila do dispatcher
     *
     * @param string $resource
     * @param mixed $callback
     * @return self
     */
    public function put(string $resource, $callback) : self
    {
        $this->queue = ($this->put)($this->queue, $resource, $callback);
        return $this;
    }

    /**
     * Adiciona uma rota a fila do dispatcher
     *
     * @param string $resource
     * @param mixed $callback
     * @return self
     */
    public function patch(string $resource, $callback) : self
    {
        $this->queue = ($this->patch)($this->queue, $resource, $callback);
        return $this;
    }

    /**
     * Adiciona uma rota a fila do dispatcher
     *
     * @param string $resource
     * @param mixed $callback
     * @return self
     */
    public function delete(string $resource, $callback) : self
    {
        $this->queue = ($this->delete)($this->queue, $resource, $callback);
        return $this;
    }

    /**
     * Adiciona os middlewares de agrupamento de rotas
     *
     * @param array $middlewares
     * @return self
     */
    public function middleware(array $middlewares) : self
    {
        if (empty($middlewares)) {
            return $this;
        }

        foreach ($middlewares as $middleware) {
            if (is_object($middleware)) {
                $this->middleware->add($middleware);
            }
        }

        return $this;
    }

    /**
     * Agrupa as rotas executa os middleware e executa a função anonima de agrupamento
     *
     * @param Callable $callback
     * @return self
     */
    public function group(callable $callback) : self
    {
        if (!\is_callable($callback)) {
            throw new RouteException(
                "Argumento de agrupamento de rotas deve ser do tipo callable"
            );
        }

        \call_user_func($callback);
        return $this;
    }

    /**
     * Parseia os dados da request e busca a uri
     *
     * @return void
     */
    public function run()
    {
        $uri    = $this->request->getUri();
        $method = $this->request->getMethod();

        if (empty($uri)) {
            throw new RouteException('A URI requisitada não existe');
        }

        if (empty($method)) {
            throw new RouteException('O método requisitado não existe');
        }

        $this->parse($uri, $method);
    }

    /**
     * Busca uma uri para o recurso executa a fila de recursos registros no roteador
     *
     * @param string $requestedUri
     * @param string $requestedHttp
     * @return void
     */
    private function parse(string $requestedUri, string $requestedHttp) : void
    {
        if (empty($this->queue)) {
            throw new RouteException('A fila de rotas está vazia');
        }

        foreach ($this->queue as $resource) {
            foreach ($resource as $http => $data) {
                if (empty($data)) {
                    continue;
                }

                if (!$this->resolveResourceArgument($data[RouteConstant::ROUTE_RESOURCE], $requestedUri)) {
                    if (!next($this->queue)) {
                        throw new RouteException($this->resourceNotFound());
                    }
                    continue;
                }

                if (strtoupper($http) !== strtoupper($requestedHttp)) {
                    continue;
                }

                if (isset($data[RouteConstant::ROUTE_CALLBACK])) {
                    if (is_callable($data[RouteConstant::ROUTE_CALLBACK])) {

                        if ($this->middleware instanceof MiddlewareInterface) {
                            $response = $this->middleware->handle($this->request);
                        }

                        if ($response->isUnauthorized()) {
                            throw new RouteException($response->getReasonPhrase(), $response->getStatusCode());
                        }

                        \call_user_func($data[RouteConstant::ROUTE_CALLBACK]);

                        break 2;
                    }
                }

                $this->controller = new $data[RouteConstant::ROUTE_CLASS]($this->container);
                $this->action     = $data[RouteConstant::ROUTE_ACTION];

                $this->dispatch();
                break 2;
            }
        }
    }

    /**
     * Faz o dispatch dos dados da request para a controller
     *
     * @return void
     */
    private function dispatch() : void
    {
        $this->injectOnBaseController();

        if (!method_exists($this->controller, $this->action)) {
            throw new RouteException(
                $this->controllerActionNotFound($this->controller, $this->action)
            );
        }

        if (!empty($this->matchParam)) {
            $this->matchParam = (is_array($this->matchParam))
                ? $this->matchParam
                : [$this->matchParam];
            $this->request->withQueryParams($this->matchParam);
        }

        if ($this->middleware instanceof MiddlewareInterface) {
            $response = $this->middleware->handle($this->request);
        }

        $reflectionMethod = new \ReflectionMethod($this->controller, $this->action);
        $reflectionMethod->invoke($this->controller, $this->request, $response);
    }
}
