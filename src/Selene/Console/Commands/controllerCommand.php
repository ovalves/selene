<?php
/**
 * @copyright   2019 - Selene
 * @author      Vinicius Oliveira <vinicius_o.a@live.com>
 * @category    Micro Framework
 * @since       2019-10-17
 */

namespace Selene\Console\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Selene\Console\Generators\ControllerGenerator;

class controllerCommand extends Command
{
    /**
     * Name of the command
     *
     * @var string
     */
    const COMMAND_NAME = 'generate:controller';
    const COMMAND_DESCRIPTION = "Solvr Generate controller";

    /**
     * Argument of command | Name of the Controller
     *
     * @var string
     */
    const CONTROLLER_NAME_ARGUMENT = "Controler Name";
    const CONTROLLER_NAME_ARGUMENT_DESCRIPTION = "The name of the Controller";

    /**
     * Options of command
     * If set generate:controller --resource it will create all methods for CRUD
     *
     * @var string
     */
    const CONTROLLER_RESOURCE_OPTION = "resource";
    const CONTROLLER_RESOURCE_OPTION_DESCRIPTION = 'If set, it will create all methods to handle the CRUD';

    /**
     * Define Controller Generator Command
     *
     * @var ControllerGenerator
     */
    protected $generator;

    /**
     * Constructor
     *
     * @param ControllerGenerator $generator
     */
    public function __construct(ControllerGenerator $generator)
    {
        $this->generator = $generator;
        parent::__construct();
    }

    /**
     * Configuration of the Controller Command
     *
     * @return void
     */
    protected function configure() : void
    {
        $this
            ->setName(self::COMMAND_NAME)
            ->setDescription(self::COMMAND_DESCRIPTION)
            ->addArgument(
                self::CONTROLLER_NAME_ARGUMENT,
                InputArgument::REQUIRED,
                self::CONTROLLER_NAME_ARGUMENT_DESCRIPTION
            )
            ->addOption(
                self::CONTROLLER_RESOURCE_OPTION,
                null,
                InputOption::VALUE_NONE,
                self::CONTROLLER_RESOURCE_OPTION_DESCRIPTION
            )
            ->addUsage('indexController --actions={index,home}');
    }

    /**
     * Execute the command
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return bool
     */
    protected function execute(InputInterface $input, OutputInterface $output) : bool
    {
        $controllerName = trim($input->getArgument(self::CONTROLLER_NAME_ARGUMENT));
        $resourceOption = $input->getOption(self::CONTROLLER_RESOURCE_OPTION);

        if (empty($controllerName)) {
            $output->writeln('<error>Name of Controller is required</error>');
            $output->writeln("<error>Usage: php solvr {$this->getUsages()[0]}</error>");
            return false;
        }

        $this->generator
            ->setGeneratorDirectory(ControllerGenerator::GENERATOR_DIRECTORY)
            ->setClassName($controllerName);

        if ($input->getOption(self::CONTROLLER_RESOURCE_OPTION)) {
            $this->generator->makeControllerWithResources();
            if ($this->generator->fileExists()) {
                $output->writeln('<info>Controller was create on Controllers/HomeController</info>');
            }

            return true;
        }

        $this->generator->makeController();
        if ($this->generator->fileExists()) {
            $output->writeln('<info>Controller was create on Controllers/HomeController</info>');
        }

        return true;
    }
}
