<?php
/**
 * @copyright   2019 - Selene
 * @author      Vinicius Oliveira <vinicius_o.a@live.com>
 * @category    Micro Framework
 * @since       2019-02-09
 */

namespace Selene\Log;

use Exception;

class Logger
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->configuration = include \Selene\App::rootPath() . 'src/Config/logging.php';

        if (empty($this->configuration)) {
            throw new Exception('Failed to open the framework configuration logging file');
        }
    }

    /**
     * Escreve uma linha no arquivo de log.
     */
    public function write(string $message = ''): void
    {
        \date_default_timezone_set('America/Sao_Paulo');
        $time = date('Y-m-d H:i:s');
        $text = "$time :: $message\n";
        $handler = \fopen(
            $this->configuration['path'] . $this->configuration['filename'],
            'a'
        );
        \fwrite($handler, $text);
        \fclose($handler);
    }
}
