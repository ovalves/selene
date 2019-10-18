<?php
/**
 * @copyright   2017 - Selene
 * @author      Vinicius Oliveira <vinicius_o.a@live.com>
 * @category    Micro Framework
 * @since       2017-11-11
 */

namespace Selene\Render;

use Selene\Render\ViewException;

/**
 * Renderiza as views da aplicação
 * Faz o parser das tags especiais do template
 */
class View extends ViewAbstract
{
    /**
     * Renderiza uma view
     *
     * @param string $file
     * @return void
     */
    public function render($file)
    {
        try {
            if (empty($file)) {
                throw new ViewException("Error Processing Request", 1);
            }

            /**
             * @todo verificar se o template está no diretório de cache antes de compilar
             */
            $this->make($file);
        } catch (ViewException $e) {
            error_log($e->getMessage());
        }
    }
}
