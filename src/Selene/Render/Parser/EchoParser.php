<?php
/**
 * @copyright   2019 - Selene
 * @author      Vinicius Oliveira <vinicius_o.a@live.com>
 * @category    Micro Framework
 * @since       2019-03-05
 */

namespace Selene\Render\Parser;

/**
 * Responsável por fazer o parser dos echos das váriaveis dos templates.
 */
trait EchoParser
{
    protected function parserEcho(string $content): string
    {
        return preg_replace('~\{{\s*(.+?)\s*\}}~is', '<?php echo $1 ?>', $content);
    }

    protected function parserEscapedEcho(string $content): string
    {
        return preg_replace('~\{{{\s*(.+?)\s*\}}}~is', '<?php echo htmlentities($1, ENT_QUOTES, \'UTF-8\') ?>', $content);
    }
}
