<?php
/**
 * @copyright   2019 - Selene
 * @author      Vinicius Oliveira <vinicius_o.a@live.com>
 * @category    Micro Framework
 * @since       2019-10-17
 */

namespace Selene\Filesystem;

class Filesystem
{
    /**
     * Determine if a file or directory exists.
     *
     * @param string $path
     */
    public function exists($path): bool
    {
        return file_exists($path);
    }

    /**
     * Get contents of a file.
     */
    public function get(string $path): string
    {
        if ($this->isFile($path)) {
            return file_get_contents($path);
        }

        throw new FileNotFoundException("File does not exist at path {$path}");
    }

    /**
     * Open | Write and close a file | create a file if not exists.
     */
    public function put(string $path, string $contents): int
    {
        return file_put_contents($path, $contents);
    }

    /**
     * Prepend content to a file.
     */
    public function prepend(string $path, string $content): int
    {
        if ($this->exists($path)) {
            return $this->put($path, $content.$this->get($path));
        }

        return $this->put($path, $content);
    }

    /**
     * Append content to a file.
     */
    public function append(string $path, string $content): int
    {
        return file_put_contents($path, $content, FILE_APPEND);
    }

    /**
     * Determine if the given path is a directory.
     */
    public function isDirectory(string $directory): bool
    {
        return is_dir($directory);
    }

    /**
     * Determine if the given path is readable.
     */
    public function isReadable(string $path): bool
    {
        return is_readable($path);
    }

    /**
     * Determine if the given path is writable.
     */
    public function isWritable(string $path): bool
    {
        return is_writable($path);
    }

    /**
     * Determine if the given path is a file.
     */
    public function isFile(string $file): bool
    {
        return is_file($file);
    }

    /**
     * Create a directory.
     */
    public function makeDirectory(string $path, int $mode = 0755, bool $recursive = false, bool $force = false): bool
    {
        if ($force) {
            return @mkdir($path, $mode, $recursive);
        }

        return mkdir($path, $mode, $recursive);
    }
}
