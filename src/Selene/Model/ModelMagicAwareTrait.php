<?php
/**
 * @copyright   2019 - Selene
 * @author      Vinicius Oliveira <vinicius_o.a@live.com>
 * @category    Micro Framework
 * @since       2019-02-17
 */

namespace Selene\Model;

trait ModelMagicAwareTrait
{
    /**
     * array contendo os dados do objeto.
     *
     * @var array
     */
    protected $data;

    /**
     * Seta um valor para um propriedade da model.
     *
     * @return mixed
     */
    protected function __set($prop, $value)
    {
        $calledMethod = $this->parseMagicMethodName('set', $prop);
        if (\method_exists($this, $calledMethod)) {
            return \call_user_func([$this, $calledMethod], $value);
        }

        $this->changeProperty($prop, $value);
    }

    /**
     * Retorna uma propriedade da objeto da model.
     *
     * @return mixed
     */
    protected function __get($prop)
    {
        $calledMethod = $this->parseMagicMethodName('get', $prop);
        if (\method_exists($this, $calledMethod)) {
            return \call_user_func([$this, $calledMethod]);
        }

        if (isset($this->data[$prop])) {
            return $this->data[$prop];
        }
    }

    /**
     * Verifica se a propriedade existe no objeto.
     */
    protected function __isset($prop): bool
    {
        return isset($this->data[$prop]);
    }

    /**
     * Parseia o método mágico requerido pela model.
     *
     * @param string $value
     */
    protected function parseMagicMethodName(string $type, string $prop): string
    {
        return \lcfirst($type).\ucfirst($prop);
    }

    /**
     * Altera uma propriedade do objeto.
     *
     * @param mixed $prop
     * @param mixed $value
     */
    protected function changeProperty($prop, $value): bool
    {
        if (\is_null($value)) {
            unset($this->data[$prop]);

            return false;
        }

        $this->data[$prop] = $value;
        $this->$prop = $value;

        return true;
    }
}
