<?php

namespace __NAMESPACE__\Command;

use ConsoleSymfonyCommandsModule\ConsoleStore;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Description of __CLASSNAME__Command
 *
 * @author Ramón Serrano <ramon.calle.88@gmail.com>
 */
class __CLASSNAME__Command extends Command
{

    /**
     * Dashboard Module
     */
    protected $moduleName = '__NAMESPACE__';
    
    public function __construct()
    {
        parent::__construct('__SHORTCUT__');
    }
    
    protected function configure()
    {
        $this
            ->addArgument('module_name', InputArgument::OPTIONAL, 'Module Name', '__NAMESPACE__')
            ->setDescription('Description Command');
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        # MVC object
        $app = ConsoleStore::retrieve('app');

        // Set Module name
        $moduleName = $input->getArgument('module_name');

        if ($moduleName != '') $this->moduleName = $moduleName;
        

        // Message to the user
        $output->writeln('Command finished.');
    }
    
}
