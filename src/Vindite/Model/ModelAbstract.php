<?php
/**
 * @copyright   2018 - Vindite
 * @author      Vinicius Oliveira <vinicius_o.a@live.com>
 * @category    Micro Framework
 * @since       2018-04-21
 */

namespace Vindite\Model;

use Vindite\Model\ModelInterface;
use Vindite\Model\ModelMagicAwareTrait;

/**
 * Molde para as classes do tipo model
 */
abstract class ModelAbstract
{
    use ModelMagicAwareTrait;

    /**
     * Retorna o nome da entidade (tabela)
     *
     * @return string
     */
    public function getEntity() : string
    {
        $class = get_class($this);
        return constant("{$class}::TABLENAME");
    }
}
