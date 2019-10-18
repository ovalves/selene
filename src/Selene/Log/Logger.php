<?php
/**
 * @copyright   2019 - Selene
 * @author      Vinicius Oliveira <vinicius_o.a@live.com>
 * @category    Micro Framework
 * @since       2019-02-09
 */

namespace Selene\Log;

class Logger
{
    /**
     * Define o nome do arquivo de logs
     */
    const LOGGER_FILENAME = '/log/app.log';

    /**
     * Constructor
     */
    public function __construct()
    {
        \file_put_contents(self::LOGGER_FILENAME, '');
    }

    /**
     * Escreve uma linha no arquivo de log
     *
     * @param string $message
     * @return void
     */
    public function write($message) : void
    {
        \date_default_timezone_set('America/Sao_Paulo');
        $time = date("Y-m-d H:i:s");

        $text = "$time :: $message\n";

        $handler = \fopen(self::LOGGER_FILENAME, 'a');
        \fwrite($handler, $text);
        \fclose($handler);
    }
}
