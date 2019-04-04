<?php
/**
 * @copyright   2019 - Vindite
 * @author      Vinicius Oliveira <vinicius_o.a@live.com>
 * @category    Micro Framework
 * @since       2019-02-16
 */

namespace Vindite\Routes;

use Vindite\Routes\RouteException;

trait RouteAwareResolveCallbackTrait
{
    /**
     * Guarda o nome da controller
     *
     * @var string
     */
    protected $controller = null;

    /**
     * Guarda o nome do método
     *
     * @var string
     */
    protected $method = null;

    /**
     * Guarda o callable
     *
     * @var callable
     */
    protected $callable = null;

    /**
     * Resolve o nome do callback do método http
     *
     * @param mixed $callback
     * @return void
     */
    public function resolveCallback($callback)
    {
        $this->flushResolverTrait();

        if (is_callable($callback)) {
            $this->setCallable($callback);
            return true;
        }

        if (is_string($callback)) {
            $parsedCallback = $this->parseCallback($callback);
        }

        if (empty($parsedCallback)) {
            throw new RouteException("Sintaxe de uso da controller e action incorreta");
        }

        $this->setCallback($parsedCallback);
    }

    /**
     * Seta o callback da rota como um callable
     *
     * @param mxed $callback
     * @return void
     */
    private function setCallable(callable $callback)
    {
        $this->callable = $callback;
    }

    /**
     * Parseia o callback
     *
     * @param mixed $callback
     * @return array
     */
    private function parseCallback($callback) : array
    {
        return explode('@', $callback);
    }

    /**
     * Seta o valor do callback como um nome de controller e método
     *
     * @param array $parsedCallback
     * @return bool
     */
    private function setCallback(array $parsedCallback) : bool
    {
        if (!empty($parsedCallback[0])) {
            $this->controller = $parsedCallback[0];
        }

        if (!empty($parsedCallback[1])) {
            $this->method = $parsedCallback[1];
        }

        return true;
    }

    /**
     * Verifica se o  callback é do tipo callable
     *
     * @return bool
     */
    public function isCallable() : bool
    {
        return is_callable($this->callable);
    }

    /**
     * Retorna o nome da controller
     *
     * @return string
     */
    public function getController() : string
    {
        return $this->controller;
    }

    /**
     * Retorna o nome da método
     *
     * @return string
     */
    public function getMethod() : string
    {
        return $this->method;
    }

    /**
     * Retorna todas as variaveis da trait para seu estado inicial
     *
     * @return void
     */
    private function flushResolverTrait() : void
    {
        $this->callable   = null;
        $this->method     = null;
        $this->controller = null;
    }
}