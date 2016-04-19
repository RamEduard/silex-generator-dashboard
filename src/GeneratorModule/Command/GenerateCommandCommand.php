<?php

namespace GeneratorModule\Command;

use ConsoleSymfonyCommandsModule\ConsoleStore;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use GeneratorModule\SharedFunctions;

/**
 * Description of GenerateCommandCommand
 *
 * @author Ramón Serrano <ramon.calle.88@gmail.com>
 */
class GenerateCommandCommand extends Command
{

    protected $command;
    protected $namespace = 'Folder';
    protected $shortcut;
    
    public function __construct()
    {
        parent::__construct('generate:command');
    }
    
    protected function configure()
    {
        $this
            ->addArgument('name', InputArgument::REQUIRED, 'Command name')
            ->addArgument('shortcut', InputArgument::REQUIRED, 'Shortcut for command. Ex. generate:custom:command')
            ->addArgument('namespace', InputArgument::REQUIRED, 'Name folder')
            ->setDescription('Generate Command');
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        # MVC object
        $app = ConsoleStore::retrieve('app');

        // Namespace
        $namespace = $input->getArgument('namespace');

        if ($namespace != '') $this->namespace = SharedFunctions::getInstance()->camelize($namespace);

        // Command
        $command = $input->getArgument('name');

        if ($command != '') $this->command = SharedFunctions::getInstance()->camelize($command);

        // Shortcut
        $this->shortcut = $input->getArgument('shortcut');

        // Create API Module
        $PATH           = dirname($app->getAppDir()) . '/src/' . $this->namespace;
        $BASE_NAME      = $this->namespace;
        // Templates path
        $TEMPLATES_PATH = dirname(__DIR__) . '/Resources/templates';
        
        // Message to the user
        $output->writeln('Creating Command on <info>' . $PATH . '</info>.');

        // Create Dir 
        @mkdir($PATH, 0755);

        // Create Command Dir 
        @mkdir($PATH . '/Command', 0755);

        // Command
        $_command = @file_get_contents($TEMPLATES_PATH . '/class_command.php');
        $_command = str_replace("__NAMESPACE__", $this->namespace, $_command);
        $_command = str_replace("__CLASSNAME__", $this->command, $_command);
        $_command = str_replace("__SHORTCUT__", $this->shortcut, $_command);

        // Create command class
        $fp = @fopen($PATH . '/Command/' . $this->command . 'Command.php', "w+");
        @fwrite($fp, $_command);
        @fclose($fp);

        // Message to the user
        $output->writeln('Generated Command on <comment>' . $PATH . '/Command/' . $this->command . 'Command.php</comment>.');
    }
    
}
