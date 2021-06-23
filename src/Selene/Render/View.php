<?php
/**
 * @copyright   2017 - Selene
 * @author      Vinicius Oliveira <vinicius_o.a@live.com>
 * @category    Micro Framework
 * @since       2017-11-11
 */

namespace Selene\Render;

/**
 * Renderiza as views da aplicação
 * Faz o parser das tags especiais do template.
 */
class View extends ViewAbstract
{
    /**
     * Renderiza uma view.
     */
    public function render(string $file): self
    {
        try {
            if (empty($file)) {
                throw new ViewException('Error - View not found');
            }

            /*
             * @todo verificar se o template está no diretório de cache antes de compilar
             */
            $this->make($file);

            return $this;
        } catch (ViewException $e) {
            error_log($e->getMessage());
        }
    }
}
