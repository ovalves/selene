<?php
/**
 * @copyright   2019 - Selene
 * @author      Vinicius Oliveira <vinicius_o.a@live.com>
 * @category    Micro Framework
 * @since       2019-02-23
 */

namespace Selene\Gateway;

use Psr\Container\ContainerInterface;
use Selene\Database\Builder\Expression;

abstract class GatewayAbstract extends Expression
{
    use GatewayDatabaseConnectorAwareTrait {
        GatewayDatabaseConnectorAwareTrait::__construct as private gatewayDatabaseConnector;
    }

    /**
     * Constructor
     */
    final public function __construct(ContainerInterface $container)
    {
        $this->gatewayDatabaseConnector($container);
        parent::__construct($this->getTransaction());
    }
}
