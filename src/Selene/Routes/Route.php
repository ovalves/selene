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
use Selene\Response\Response;

/**
 * Responsável por executar as ações de roteamento da aplicação.
 */
class Route
{
    use RouteAwareTrait;
    use RouteDispatcherAwareTrait;

    protected ContainerInterface $container;
    protected MiddlewareInterface $middleware;

    /**
     * Identificador do grupo de rotas.
     */
    private string $groupIdentifier;

    /**
     * Agrupamento nomeado de middlewares.
     */
    private array $groupMiddleware = [];

    /**
     * Quantidade Rotas adicionadas.
     */
    private int $countRoutes = 0;

    /**
     * Route Controller.
     */
    private mixed $controller;

    /**
     * Route Action.
     */
    private string $action;

    /**
     * Framework Request Object.
     */
    private Request $request;

    /**
     * Fila de rotas.
     */
    private array $queue = [];

    /**
     * Define o verbo http get.
     */
    private Http\Get $get;

    /**
     * Define o verbo http post.
     */
    private Http\Post $post;

    /**
     * Define o verbo http put.
     */
    private Http\Put $put;

    /**
     * Define o verbo http patch.
     */
    private Http\Patch $patch;

    /**
     * Define o verbo http delete.
     */
    private Http\Delete $delete;

    /**
     * Constructor.
     *
     * @param MiddlewareInterface $middleware
     */
    public function __construct(ContainerInterface $container, Request $request, MiddlewareInterface $middleware = null)
    {
        $this->get = new Http\Get();
        $this->put = new Http\Put();
        $this->post = new Http\Post();
        $this->patch = new Http\Patch();
        $this->delete = new Http\Delete();
        $this->queue = [];
        $this->container = $container;
        $this->request = $request;
        $this->middleware = $middleware;
    }

    /**
     * Adiciona uma rota a fila do dispatcher.
     *
     * @param mixed $callback
     */
    public function get(string $resource, $callback): self
    {
        $this->queue = ($this->get)($this->groupIdentifier, $this->queue, $resource, $callback);
        $this->countRoutes++;

        return $this;
    }

    /**
     * Adiciona uma rota a fila do dispatcher.
     *
     * @param mixed $callback
     */
    public function post(string $resource, $callback): self
    {
        $this->queue = ($this->post)($this->groupIdentifier, $this->queue, $resource, $callback);
        $this->countRoutes++;

        return $this;
    }

    /**
     * Adiciona uma rota a fila do dispatcher.
     *
     * @param mixed $callback
     */
    public function put(string $resource, $callback): self
    {
        $this->queue = ($this->put)($this->groupIdentifier, $this->queue, $resource, $callback);
        $this->countRoutes++;

        return $this;
    }

    /**
     * Adiciona uma rota a fila do dispatcher.
     *
     * @param mixed $callback
     */
    public function patch(string $resource, $callback): self
    {
        $this->queue = ($this->patch)($this->groupIdentifier, $this->queue, $resource, $callback);
        $this->countRoutes++;

        return $this;
    }

    /**
     * Adiciona uma rota a fila do dispatcher.
     *
     * @param mixed $callback
     */
    public function delete(string $resource, $callback): self
    {
        $this->queue = ($this->delete)($this->groupIdentifier, $this->queue, $resource, $callback);
        $this->countRoutes++;

        return $this;
    }

    /**
     * Adiciona os middlewares de agrupamento de rotas.
     */
    public function middleware(array $middlewares): self
    {
        if (empty($middlewares)) {
            return $this;
        }

        foreach ($middlewares as $middleware) {
            if (is_object($middleware)) {
                $this->groupMiddleware[$this->groupIdentifier][] = $middleware::class;
                $this->middleware->add($middleware);
            }
        }

        return $this;
    }

    /**
     * Agrupa as rotas executa os middleware e executa a função anonima de agrupamento.
     */
    public function group(string $groupIdentifier, callable $callback): self
    {
        if (!\is_callable($callback)) {
            throw new RouteException('Argumento de agrupamento de rotas deve ser do tipo callable');
        }

        $this->groupIdentifier = $groupIdentifier;
        \call_user_func($callback);

        return $this;
    }

    /**
     * Parseia os dados da request e busca a uri.
     *
     * @return void
     */
    public function run()
    {
        $uri = $this->request->getUri();
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
     * Busca uma uri para o recurso executa a fila de recursos registros no roteador.
     */
    private function parse(string $requestedUri, string $requestedHttp): void
    {
        if (empty($this->queue)) {
            throw new RouteException('A fila de rotas está vazia');
        }

        $routesChecked = 0;
        foreach ($this->queue as $identifier => $resource) {
            foreach ($resource as $data) {
                $routesChecked++;
                if (empty($data)) {
                    continue;
                }

                $http = key($data);
                $data = reset($data);

                if (!$this->resolveResource($data[RouteConstant::ROUTE_RESOURCE], $requestedUri)) {
                    if ($routesChecked >= $this->countRoutes) {
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
                $this->action = $data[RouteConstant::ROUTE_ACTION];

                $this->dispatch($identifier);
                break 2;
            }
        }
    }

    /**
     * Faz o dispatch dos dados da request para a controller.
     */
    private function dispatch(string $identifier): void
    {
        $this->injectOnBaseController();

        if (!method_exists($this->controller, $this->action)) {
            throw new RouteException($this->controllerActionNotFound($this->controller, $this->action));
        }

        if (!empty($this->matchParam)) {
            $this->matchParam = (is_array($this->matchParam))
                ? $this->matchParam
                : [$this->matchParam];
            $this->request->withQueryParams($this->matchParam);
        }

        if (isset($this->groupMiddleware[$identifier])) {
            if ($this->middleware instanceof MiddlewareInterface) {
                $response = $this->middleware->handle($this->request);
            }
        }

        $reflectionMethod = new \ReflectionMethod($this->controller, $this->action);
        $reflectionMethod->invoke($this->controller, $this->request, $response ?? new Response());
    }
}
