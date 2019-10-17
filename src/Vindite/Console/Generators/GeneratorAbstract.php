<?php
/**
 * @copyright   2019 - Vindite
 * @author      Vinicius Oliveira <vinicius_o.a@live.com>
 * @category    Micro Framework
 * @since       2019-10-17
 */

namespace Vindite\Console\Generators;

use Vindite\Filesystem\Filesystem;

class GeneratorAbstract
{
    /**
     * Define application rootPath
     *
     * @var string
     */
    protected $rootPath;

    /**
     * Define generator directory
     *
     * @var string
     */
    protected $directory;

    /**
     * Define generator stub file
     *
     * @var string
     */
    protected $stub;

    /**
     * Define Filesystem Object
     *
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * Define generator class name
     *
     * @var string
     */
    protected $className;

    /**
     * Constructor
     *
     * @param string $rootPath
     */
    public function __construct(string $rootPath)
    {
        $this->rootPath = $rootPath;
        $this->filesystem = new Filesystem;
    }

    /**
     * Define generator directory
     *
     * @param string $directory
     * @return self
     */
    public function setGeneratorDirectory(string $directory) : self
    {
        $this->directory = $directory;
        return $this;
    }

    /**
     * Define generator class name
     *
     * @param string $className
     * @return self
     */
    public function setClassName(string $className) : self
    {
        $this->className = $className;
        return $this;
    }

    /**
     * Change the name of class
     *
     * @param string $search
     * @return void
     */
    public function changeClassNameByPrefix(string $search) : void
    {
        $this->stub = str_replace($search, $this->className, $this->stub);
    }

    /**
     * Determine if the generator directory exists
     *
     * @return boolean
     */
    public function directoryExists() : bool
    {
        return $this->filesystem->exists(
            $this->rootPath.DIRECTORY_SEPARATOR.$this->directory
        );
    }

    /**
     * Create the generator directory
     *
     * @return boolean
     */
    public function makeDirectory() : bool
    {
        return $this->filesystem->makeDirectory(
            $this->rootPath.DIRECTORY_SEPARATOR.$this->directory,
            0755
        );
    }

    /**
     * Determine if the file exists
     *
     * @return boolean
     */
    public function fileExists() : bool
    {
        return $this->filesystem->exists(
            $this->rootPath.DIRECTORY_SEPARATOR.$this->directory.DIRECTORY_SEPARATOR.$this->className.".php"
        );
    }

    /**
     * Open the generator file stub
     *
     * @param string $stub
     * @return void
     */
    public function openStub(string $stub) : void
    {
        $this->stub = $this->filesystem->get($stub);
    }

    /**
     * Create the generator file
     *
     * @return void
     */
    public function createFile()
    {
        if (!$this->filesystem->isWritable(
            $this->rootPath.DIRECTORY_SEPARATOR.$this->directory)) {
            throw new FilesystemException("Directory is not Writable");
        }

        $handle = $this->filesystem->put(
            $this->rootPath.DIRECTORY_SEPARATOR.$this->directory.DIRECTORY_SEPARATOR.$this->className.".php",
            $this->stub
        );
    }
}