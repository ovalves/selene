<?php
/**
 * @copyright   2019 - Selene
 * @author      Vinicius Oliveira <vinicius_o.a@live.com>
 * @category    Micro Framework
 * @since       2019-03-05
 */

namespace Selene\Render\Parser;

trait SanitizeParser
{
    protected function sanitize(string $content): string
    {
        return preg_replace('~\{%\s*(.+?)\s*\%}~is', '<?php $1 ?>', $content);
    }
}
