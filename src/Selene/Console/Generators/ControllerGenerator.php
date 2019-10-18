<?php
/**
 * @copyright   2019 - Selene
 * @author      Vinicius Oliveira <vinicius_o.a@live.com>
 * @category    Micro Framework
 * @since       2019-10-17
 */

namespace Selene\Console\Generators;

use Selene\Filesystem\FilesystemException;

class ControllerGenerator extends GeneratorAbstract
{
    /**
     * Define the controller directory
     */
    const GENERATOR_DIRECTORY = 'Controllers';

    /**
     * Define the prefix of controller name
     */
    const CONTROLLER_CLASS_NAME_PREFIX = '__class_name__';

    /**
     * Define controller default stub
     */
    const CONTROLLER_STUB = __DIR__.'/Stubs/Controllers/controller.stub';

    /**
     * Define controller resources stub
     */
    const CONTROLLER_STUB_RESOURCES = __DIR__.'/Stubs/Controllers/controller_resources.stub';

    /**
     * Generate default controller
     *
     * @return void
     */
    public function makeController() : void
    {
        if (!$this->directoryExists()) {
            $this->makeDirectory();
        }

        if ($this->fileExists()) {
            throw new FilesystemException("Controller {$this->className} already exists");
        }

        $this->openStub(self::CONTROLLER_STUB);
        $this->changeClassNameByPrefix(self::CONTROLLER_CLASS_NAME_PREFIX);
        $this->createFile();
    }

    /**
     * Generate resources controller
     *
     * @return void
     */
    public function makeControllerWithResources() : void
    {
        if (!$this->directoryExists()) {
            $this->makeDirectory();
        }

        if ($this->fileExists()) {
            throw new FilesystemException("Controller {$this->className} already exists");
        }

        $this->openStub(self::CONTROLLER_STUB_RESOURCES);
        $this->changeClassNameByPrefix(self::CONTROLLER_CLASS_NAME_PREFIX);
        $this->createFile();
    }
}