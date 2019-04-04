<?php
/**
 * @copyright   2019 - Vindite
 * @author      Vinicius Oliveira <vinicius_o.a@live.com>
 * @category    Micro Framework
 * @since       2019-02-09
 */

namespace Vindite\Container;

use Exception;
use Psr\Container\ContainerExceptionInterface;

/**
 * Exception dos containers
 */
class ContainerException extends Exception implements ContainerExceptionInterface
{
}
