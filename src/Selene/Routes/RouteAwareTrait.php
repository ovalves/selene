<?php
/**
 * @copyright   2019 - Selene
 * @author      Vinicius Oliveira <vinicius_o.a@live.com>
 * @category    Micro Framework
 * @since       2019-02-15
 */

namespace Selene\Routes;

use Selene\Routes\RouteException;

trait RouteAwareTrait
{
    protected $matchUri;
    protected $matchParam;
    /**
     * Indica o recurso não encontrado
     *
     * @return string
     */
    public function resourceNotFound($resource = null) : string
    {
        if (empty($resource)) {
            return "Recurso não encontrado";
        }

        return \sprintf("Recurso (%s) não encontrado", $resource);
    }

    /**
     * Indica que o método http não é permitido
     *
     * @return string
     */
    public function controllerActionNotFound($controller = null, $action = null) : string
    {
        if (\is_object($controller)) {
            $controller = get_class($controller);
        }

        if (empty($action)) {
            return "Action da controller não especificada";
        }

        if (empty($controller)) {
            return "Controller não especificada";
        }

        return \sprintf("Action (%s) da controller (%s) não encontrada", $action, $controller);
    }

    /**
     * Indica que o método http não é permitido
     *
     * @return string
     */
    public function methodHttpNotFound($httpMethod = null) : string
    {
        if (empty($httpMethod)) {
            return "Método http não encontrado";
        }

        return \sprintf("Método http (%s) não encontrado", $httpMethod);
    }

    /**
     * Resolve o recurso requisitado e seus argumentos
     *
     * @param string $routeResource
     * @param string $calledResource
     * @return bool
     */
    public function resolveResourceArgument(string $routeResource, string $calledResource) : bool
    {
        if (empty($routeResource)) {
            throw new RouteException("O recurso solicitado não existe", 404);
        }

        if (empty($calledResource)) {
            throw new RouteException("O recurso solicitado não existe", 404);
        }

        preg_match_all('/(\{\w+\})/', $routeResource, $matches);

        if (empty($matches[0])) {
            return $routeResource === $calledResource;
        }

        $routeResource = explode('/', $routeResource);
        $calledResource = explode('/', $calledResource);


        $this->matchUri = [];
        $this->matchParam = [];
        foreach ($routeResource as $key => $resource) {
            if (empty($resource)) {
                continue;
            }

            $resource = trim($resource);
            if (!isset($calledResource[$key])) {
                continue;
            }

            $calledResource[$key] = trim($calledResource[$key]);
            if ($resource === $calledResource[$key]) {
                $this->matchUri = $resource;
                continue;
            }

            if (in_array($resource, $matches[0])) {
                $this->matchParam = $calledResource[$key];
                continue;
            }
        }

        return (empty($this->matchUri)) ? false : true;
    }
}