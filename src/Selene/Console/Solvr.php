<?php
/**
 * @copyright   2019 - Selene
 * @author      Vinicius Oliveira <vinicius_o.a@live.com>
 * @category    Micro Framework
 * @since       2019-10-17
 */

namespace Selene\Console;

use Selene\Console\Commands;
use Selene\Console\Generators;

class Solvr
{
    /**
     * Constructor
     *
     * @param string $rootPath
     */
    public function __construct(string $rootPath)
    {
        $this->rootPath = $rootPath;
    }

    /**
     * Define console commands
     *
     * @var array
     */
    protected $commands = [
        'controllerCommand' => [
            Commands\controllerCommand::class => Generators\ControllerGenerator::class
        ]
    ];

    /**
     * Define instantiaded console commands
     *
     * @var array
     */
    protected $instantiateCommands = [];

    /**
     * instantiate console comands and generator commands
     *
     * @return array
     */
    public function getSolvrCommands() : array
    {
        if (!empty($this->instantiateCommands)) {
            return $this->instantiateCommands;
        }

        foreach ($this->commands as $command => $generators) {
            foreach ($generators as $key => $generator) {
                $this->instantiateCommands[$command] = new $key(new $generators[$key]($this->rootPath));
            }
        }

        return $this->instantiateCommands;
    }
}