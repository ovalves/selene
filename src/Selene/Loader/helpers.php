<?php
/**
 * @copyright   2019 - Selene
 * @author      Vinicius Oliveira <vinicius_o.a@live.com>
 * @category    Micro Framework
 * @since       2019-07-01
 */

use Selene\Log\Logger;

if (!function_exists('env')) {
    /**
     * Busca valores em variáveis de ambientes.
     */
    function env(string $name): string
    {
        return (!empty($_ENV[$name])) ? $_ENV[$name] : '';
    }
}

/*
 * Define um item do array para um determinado valor usando a notação "DOT".
 */
if (!function_exists('arr_set')) {
    function arr_set(array &$array, ?string $key, mixed $value): array
    {
        if (is_null($key)) {
            return $array = $value;
        }

        $keys = explode('.', $key);

        foreach ($keys as $i => $key) {
            if (1 === count($keys)) {
                break;
            }

            unset($keys[$i]);

            if (!isset($array[$key]) || !is_array($array[$key])) {
                $array[$key] = [];
            }

            $array = &$array[$key];
        }

        $array[array_shift($keys)] = $value;

        return $array;
    }
}

/*
 * Adiciona um elemento a um array usando a notação "dot".
 */
if (!function_exists('arr_add')) {
    function arr_add(array $array, string $key, mixed $value): array
    {
        if (is_null(arr_get($array, $key))) {
            arr_set($array, $key, $value);
        }

        return $array;
    }
}

/*
 * Retorna um item de um array usando a notação "DOT".
 */
if (!function_exists('arr_get')) {
    function arr_get(array $array, mixed $key, mixed $default = null): mixed
    {
        if (!is_array($array)) {
            return value($default);
        }

        if (is_null($key)) {
            return $array;
        }

        if (exists($array, $key)) {
            return $array[$key];
        }

        if (false === strpos($key, '.')) {
            return $array[$key] ?? value($default);
        }

        foreach (explode('.', $key) as $segment) {
            if (is_array($array) && exists($array, $segment)) {
                $array = $array[$segment];
            } else {
                return value($default);
            }
        }

        return $array;
    }
}

/*
 * Determina se uma chave existe em um array.
 */
if (!function_exists('exists')) {
    function exists(array $array, mixed $key): bool
    {
        if ($array instanceof ArrayAccess) {
            return $array->offsetExists($key);
        }

        return array_key_exists($key, $array);
    }
}

if (!function_exists('value')) {
    function value(mixed $value, mixed ...$args)
    {
        return $value instanceof Closure ? $value(...$args) : $value;
    }
}

if (!function_exists('log_error')) {
    function log_error(Exception $exception): void
    {
        $message = sprintf(
            'Error: Message: %s | Code: %s | File: %s | Line: %s',
            $exception->getMessage(),
            $exception->getCode(),
            $exception->getFile(),
            $exception->getLine()
        );

        log_audit($message);
    }
}

if (!function_exists('log_audit')) {
    function log_audit(?string $message = ''): void
    {
        (new Logger())->write($message);
    }
}

/*
 * Converte a codificação de caracteres de um array ou string
*/
if (!function_exists('str_sanitize_encoding_from_input')) {
    function str_sanitize_encoding_from_input(array | string $data): array | string
    {
        if (is_string($data)) {
            return mb_convert_encoding($data, 'HTML-ENTITIES', 'UTF-8');
        }

        array_walk_recursive(
            $data,
            function (&$input) {
                $input = mb_convert_encoding($input, 'HTML-ENTITIES', 'UTF-8');
            }
        );

        return $data;
    }
}

/*
 * Remove os acentos de um texto retornando o em letras minúsculas
 * Exemplo de uso:
 *      echo str_transliterator('Fóø Bår') // foo bar
*/
if (!function_exists('str_transliterator')) {
    function str_transliterator(string $text): string | bool
    {
        if (empty($text)) {
            return false;
        }

        $transliterator = Transliterator::createFromRules(
            ':: Any-Latin; :: Latin-ASCII; :: NFD; :: [:Nonspacing Mark:] Remove; :: Lower(); :: NFC;',
            Transliterator::FORWARD
        );

        return $transliterator->transliterate($text);
    }
}

if (!function_exists('str_uncamelize')) {
    function str_uncamelize(array | string $word, string $splitter = ' ', bool $uppercase = true): mixed
    {
        $word = preg_replace(
            '/(?!^)[[:upper:]][[:lower:]]/',
            '$0',
            preg_replace('/(?!^)[[:upper:]]+/', $splitter.'$0', $word)
        );

        return $uppercase ? ucwords($word) : $word;
    }
}

/*
 * Procura e altera o valor de uma uma string no array
 * Exemplo de uso:
 *      $pessoas = [
 *          1 => [
 *              ':name' => 'José',
 *              ':idade' => 23,
 *          ],
 *          2 => [
 *              ':name' => 'Maria',
 *              ':idade' => 45,
 *          ],
 *          3 => [
 *              ':name' => 'Pedro',
 *              ':idade' => 17,
 *          ],
 *      ];
 *
 *      foreach ($pessoas as $pessoa) {
 *          echo str_find_replace($pessoa, ':name completou :idade anos de idade.');
 *
 *          // "José completou 23 anos de idade."
 *          // "Maria completou 45 anos de idade."
 *          // "Pedro completou 17 anos de idade."
 *      }
 */
if (!function_exists('str_find_replace')) {
    function str_find_replace(array $array, string $text): string | bool
    {
        if (empty($array) || empty($text)) {
            return false;
        }

        if (arr_is_associative($array)) {
            foreach ($array as $search => $replace) {
                $text = str_replace($search, $replace, $text);
            }
        }

        if (isset($array[0]) && isset($array[1])) {
            $text = str_replace($array[0], $array[1], $text);
        }

        return $text;
    }
}

/*
 * Procura uma string entre tags HTML
 * Exemplo de uso:
 *      $html = '
 *          <!DOCTYPE html>
 *          <html>
 *              <head>
 *                  <title>Page Title</title>
 *              </head>
 *              <body>
 *                  <h1>My First Heading</h1>
 *                  <p>My first paragraph.</p>
 *                  <p>My second paragraph.</p>
 *              </body>
 *          </html>';
 *
 *      echo str_find_between_tags('<h3>', '</h3>', $html);
 *
 *      // O uso da função acima resulta em um array com as strings correspondentes
 *          => [
 *              'My first paragraph',
 *              'My second paragraph'
 *          ];
 */
if (!function_exists('str_find_between_tags')) {
    function str_find_between_tags($left, $right, $string): array
    {
        if (empty($left) || empty($right) || empty($string)) {
            return [];
        }

        preg_match_all('/' . preg_quote($left, '/') . '(.*?)' . preg_quote($right, '/') . '/s', $string, $matches);

        return array_map('trim', $matches[1]);
    }
}

/*
 * Retorna o texto subsequente à string procurada
 *
 * Exemplo de uso:
 *    echo str_find_after_text('completou', 'José completou 23 anos de idade'); // 23 anos de idade
 */
if (!function_exists('str_find_after_text')) {
    function str_find_after_text(string $search, string $string): string
    {
        if (empty($search) || empty($string)) {
            return '';
        }

        return '' === $search ? $string : ltrim(array_reverse(explode($search, $string, 2))[0]);
    }
}

/*
 * Retorna o texto antecedente à string procurada
 *
 * Exemplo de uso:
 *    echo str_find_before_text('anos', 'José completou 23 anos de idade'); // José completou 23
 */
if (!function_exists('str_find_before_text')) {
    function str_find_before_text(string $search, string $string): string
    {
        if (empty($search) || empty($string)) {
            return '';
        }

        return '' === $search ? $string : rtrim(explode($search, $string)[0]);
    }
}

/*
 * Retorna o texto limitado ao número de palavras definido na função
 *
 * Exemplo de uso:
 *    echo str_limit_printed_words('José completou 23 anos de idade', 3); // José completou 23...
 */
if (!function_exists('str_limit_printed_words')) {
    function str_limit_printed_words(string $string, int $limit = 10, string $end = '...'): string | bool
    {
        if (empty($string)) {
            return false;
        }

        $words = explode(' ', $string);

        if (sizeof($words) <= $limit) {
            return $string;
        }

        return implode(' ', array_slice($words, 0, $limit)) . $end;
    }
}

/*
 * Retorna o texto truncado ao número de letras definido na função
 *
 * Exemplo de uso:
 *    echo str_truncate_string('José completou 23 anos de idade', 10); // José compl...
 */
if (!function_exists('str_truncate_string')) {
    function str_truncate_string(string $string, int $limit = 100, string $end = '...'): string | bool
    {
        if (empty($string)) {
            return false;
        }

        if (mb_strwidth($string, 'UTF-8') <= $limit) {
            return $string;
        }

        return rtrim(mb_strimwidth($string, 0, $limit, '', 'UTF-8')) . $end;
    }
}

/*
 * Determina se um conjunto de caracteres pode ser encontrado dentro de outra string, retornando true ou false.
 *
 * Exemplo de uso:
 *    echo str_includes('idade', 'José completou 23 anos de idade'); // true
 */
if (!function_exists('str_includes')) {
    function str_includes(string | array $search, string $string, bool $ignore_case = false): bool
    {
        if (empty($search) || empty($string)) {
            return false;
        }

        $find = ($ignore_case) ? 'stripos' : 'strpos';

        foreach ((array) $search as $needle) {
            if (false !== $find($string, $needle)) {
                return true;
            }
        }

        return false;
    }
}

/*
 * Determina se o inicio de uma string possui um conjunto de caracteres, retornando true ou false.
 *
 * Exemplo de uso:
 *    echo str_starts_with('José', 'José completou 23 anos de idade'); // true
 */
if (!function_exists('str_starts_with')) {
    function str_starts_with(string | array $search, string $string, bool $ignore_case = false): bool
    {
        if (empty($search) || empty($string)) {
            return false;
        }

        if ($ignore_case) {
            $string = strtolower($string);
        }

        foreach ((array) $search as $needle) {
            if ($ignore_case) {
                $needle = strtolower($needle);
            }

            if ('' !== $needle && substr($string, 0, strlen($needle)) === (string) $needle) {
                return true;
            }
        }

        return false;
    }
}

/*
 * Determina se o final de uma string possui um conjunto de caracteres, retornando true ou false.
 *
 * Exemplo de uso:
 *    echo str_ends_with('idade', 'José completou 23 anos de idade'); // true
 */
if (!function_exists('str_ends_with')) {
    function str_ends_with(string | array $search, string $string, bool $ignore_case = false): bool
    {
        if (empty($search) || empty($string)) {
            return false;
        }

        if ($ignore_case) {
            $string = strtolower($string);
        }

        foreach ((array) $search as $needle) {
            if ($ignore_case) {
                $needle = strtolower($needle);
            }

            $length = strlen($needle);
            if (0 === $length || (substr($string, -$length) === (string) $needle)) {
                return true;
            }
        }

        return false;
    }
}

/*
 * Verifica se o array é do tipo associativo.
 */
if (!function_exists('arr_is_associative')) {
    function arr_is_associative(array $array = []): bool
    {
        if (!is_array($array) || [] === $array) {
            return false;
        }

        return array_keys($array) !== range(0, count($array) - 1);
    }
}

/*
 * Converte um array para objeto.
 */
if (!function_exists('arr_to_object')) {
    function arr_to_object(array $array = []): stdClass | null
    {
        $result = json_decode(json_encode($array), false);

        return is_object($result) ? $result : null;
    }
}

/*
 * Converte uma string para um array.
 */
if (!function_exists('arr_string_to_array')) {
    function arr_string_to_array(string $string = ''): array | null
    {
        if (is_string($string)) {
            return str_split($string);
        }

        return null;
    }
}

/*
 * Converte um objeto para um array.
 */
if (!function_exists('arr_object_to_array')) {
    function arr_object_to_array($object): array | null
    {
        if (is_object($object)) {
            return json_decode(json_encode($object), true);
        }

        return null;
    }
}

if (!function_exists('file_size')) {
    /**
     * Converte o tamanho de um arquivo em um formato legível por humanos como `100mb`.
     *
     * @see https://stackoverflow.com/a/5501447/9443583
     */
    function file_size(int $bytes): string
    {
        switch ($bytes) {
            case $bytes >= 1073741824:
                return number_format($bytes / 1073741824, 2).' GB';

            case $bytes >= 1048576:
                return number_format($bytes / 1048576, 2).' MB';

            case $bytes >= 1024:
                return number_format($bytes / 1024, 2).' KB';

            case $bytes > 1:
                return $bytes.' bytes';

            case 1 == $bytes:
                return $bytes.' byte';

            default:
                return '0 bytes';
        }
    }
}

if (!function_exists('ftr_is_email')) {
    function ftr_is_email(string $email): bool
    {
        if (empty($email)) {
            return false;
        }

        return (false !== filter_var($email, FILTER_VALIDATE_EMAIL)) ? true : false;
    }
}

if (!function_exists('json_prepare_options')) {
    function json_prepare_options(array $options): array
    {
        $original = [];
        $toReplace = [];

        foreach ($options as $key => &$value) {
            if (is_array($value)) {
                $subArray = json_prepare_options($value);
                $value = $subArray['options'];
                $original = array_merge($original, $subArray['original']);
                $toReplace = array_merge($toReplace, $subArray['toReplace']);
            } elseif (0 === strpos($value, 'function(')) {
                $original[] = $value;
                $value = "%{$key}%";
                $toReplace[] = "\"{$value}\"";
            }
        }

        return compact('original', 'toReplace', 'options');
    }
}

if (!function_exists('json_encode_options')) {
    function json_encode_options(array $options): string | array
    {
        $data = json_prepare_options($options);

        $json = json_encode($data['options']);

        return str_replace($data['toReplace'], $data['original'], $json);
    }
}

/*
 * Retorna o tipo de uma variável.
 */
if (!function_exists('var_get_type')) {
    function var_get_type(mixed $var): mixed
    {
        return match (gettype($var)) {
            'boolean' => 'bool',
            'integer' => 'int',
            'double' => 'float',
            'string' => 'string',
            'array' => 'array',
            'object' => 'object',
            'resource' => 'resource',
            'NULL' => 'NULL',
            default => 'mixed',
        };
    }
}

/*
 * Faz o cast de uma variavel para o tipo encontrado no teste de tipo de variável.
 */
if (!function_exists('var_cast_to_type')) {
    function var_cast_to_type(mixed $var): mixed
    {
        return match (gettype($var)) {
            'boolean' => (bool) $var,
            'integer' => (int) $var,
            'double' => (float) $var,
            'string' => (string) "'$var'",
            'array' => (array) $var,
            'object' => $var,
            'resource' => $var,
            'NULL' => 'NULL',
            default => (string) "'$var'",
        };
    }
}
