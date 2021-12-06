<?php
/**
 * @copyright   2019 - Selene
 * @author      Vinicius Oliveira <vinicius_o.a@live.com>
 * @category    Micro Framework
 * @since       2019-10-12
 */

namespace Selene\Config;

/**
 * Interface para gerenciamento dos arquivos de configuração do framework.
 */
interface ConfigInterface
{
    public function __invoke(): array;
}
