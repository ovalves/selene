<?php
/**
 * @copyright   2019 - Selene
 * @author      Vinicius Oliveira <vinicius_o.a@live.com>
 * @category    Micro Framework
 * @since       2019-02-15
 */

namespace Selene\Routes;

trait RouteAwareTrait
{
    protected $matchUri;
    protected $matchParam;

    /**
     * Indica o recurso não encontrado.
     */
    public function resourceNotFound($resource = null): string
    {
        if (empty($resource)) {
            return 'Recurso não encontrado';
        }

        return \sprintf('Recurso (%s) não encontrado', $resource);
    }

    /**
     * Indica que o método http não é permitido.
     */
    public function controllerActionNotFound($controller = null, $action = null): string
    {
        if (\is_object($controller)) {
            $controller = get_class($controller);
        }

        if (empty($action)) {
            return 'Action da controller não especificada';
        }

        if (empty($controller)) {
            return 'Controller não especificada';
        }

        return \sprintf('Action (%s) da controller (%s) não encontrada', $action, $controller);
    }

    /**
     * Indica que o método http não é permitido.
     */
    public function methodHttpNotFound($httpMethod = null): string
    {
        if (empty($httpMethod)) {
            return 'Método http não encontrado';
        }

        return \sprintf('Método http (%s) não encontrado', $httpMethod);
    }

    /**
     * Resolve o recurso requisitado.
     */
    public function resolveResource(string $routeResource, string $calledResource): bool
    {
        if (empty($routeResource)) {
            throw new RouteException('O recurso solicitado não existe', 404);
        }

        if (empty($calledResource)) {
            throw new RouteException('O recurso solicitado não existe', 404);
        }

        \preg_match_all('/(\{\w+\})/', $routeResource, $args);

        if (!empty($args[0])) {
            return $this->resolveResourceWithArgument($routeResource, $calledResource, $args);
        }

        return $this->resolveResourceWithQueryParams($routeResource, $calledResource);
    }

    /**
     * Resolve o recurso requisitado e seus argumentos.
     */
    protected function resolveResourceWithArgument(string $routeResource, string $calledResource, array $args): bool
    {
        $routeResource = \explode('/', $routeResource);
        $calledResource = \explode('/', $calledResource);

        $this->matchUri = [];
        $this->matchParam = [];
        foreach ($routeResource as $key => $resource) {
            if (empty($resource)) {
                continue;
            }

            $resource = \trim($resource);
            if (!isset($calledResource[$key])) {
                continue;
            }

            $calledResource[$key] = \trim($calledResource[$key]);
            if ($resource === $calledResource[$key]) {
                $this->matchUri = $resource;
                continue;
            }

            if (\in_array($resource, $args[0])) {
                $this->matchParam = $calledResource[$key];
                continue;
            }
        }

        return (empty($this->matchUri)) ? false : true;
    }

    /**
     * Resolve o recurso requisitado e seus query params.
     */
    protected function resolveResourceWithQueryParams(string $routeResource, string $calledResource): string
    {
        \preg_match('/([^\?]+)(\?.*)?/', $calledResource, $args);

        if (!empty($args[0])) {
            $calledResource = \explode('?', $calledResource);
        }

        if (!empty($calledResource[1])) {
            $params = \explode('&', $calledResource[1]);
        }

        $this->matchParam = [];
        foreach ($params as $param) {
            $params = \explode('=', $param);
            $this->matchParam[$params[0]] = $params[1];
        }

        return $routeResource === $calledResource[0];
    }
}
