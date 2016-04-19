<?php

namespace GeneratorModule\Command;

use ConsoleSymfonyCommandsModule\ConsoleStore;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use GeneratorModule\SharedFunctions;

/**
 * Description of GenerateAPICommand
 *
 * @author Ramón Serrano <ramon.calle.88@gmail.com>
 */
class GenerateAPICommand extends Command
{

    /**
     * Dashboard Module
     */
    protected $moduleName = 'APIModule';
    
    public function __construct()
    {
        parent::__construct('generate:api');
    }
    
    protected function configure()
    {
        $this
            ->addArgument('module_name', InputArgument::OPTIONAL, 'Module Name for Dashboard', 'APIModule')
            ->setDescription('Generate API Module');
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        # MVC object
        $app = ConsoleStore::retrieve('app');

        // Set Module name
        $moduleName = $input->getArgument('module_name');

        if ($moduleName != '') $this->moduleName = SharedFunctions::getInstance()->camelize($moduleName);
        
        // Array tables
        $tables = SharedFunctions::getInstance()->getArrayTables($app);

        // Create API Module
        $API_PATH             = dirname($app->getAppDir()) . '/src/' . $this->moduleName;
        $API_BASE_NAME        = str_replace('Module', '', $this->moduleName);
        // Base namespace controller
        $CONTROLLER_NAMESPACE = $this->moduleName . '\\\\Controller\\\\';
        $TEMPLATES_PATH       = dirname(__DIR__) . '/Resources/templates';

        // Message to the user
        $output->writeln('Creating Module and API on <comment>' . $API_PATH . '</comment>.');

        // Module required templates
        $_module = @file_get_contents($TEMPLATES_PATH . '/class_module.php');
        $_module = str_replace("__MODULE__", $this->moduleName, $_module);

        // Injection
        $_extension = @file_get_contents($TEMPLATES_PATH . '/class_extension.php');
        $_extension = str_replace("__MODULE__", $this->moduleName, $_extension);
        $_extension = str_replace("__BASENAME__", $API_BASE_NAME, $_extension);

        if (!empty($tables)) {
            // Create Dir DashboardModule
            @mkdir($API_PATH, 0755);
            // API Module
            $fp = @fopen($API_PATH . '/' . $this->moduleName . '.php', "w+");
            @fwrite($fp, $_module);
            @fclose($fp);
            // Create Dir Controller
            @mkdir($API_PATH . '/Controller', 0755);
            // Create Dir Entity
            @mkdir($API_PATH . '/Entity', 0755);
            // Create Dir Injection
            @mkdir($API_PATH . '/Injection', 0755);
            // Extension Injection
            $fp = @fopen($API_PATH . '/Injection/' . $API_BASE_NAME . 'Extension.php', "w+");
            @fwrite($fp, $_extension);
            @fclose($fp);
            // Create Dir Model
            @mkdir($API_PATH . '/Model', 0755);
            // Create Dir Resources
            @mkdir($API_PATH . '/Resources', 0755);
            // Create Dir routes
            @mkdir($API_PATH . '/Resources/config', 0755);
            @mkdir($API_PATH . '/Resources/config/routes', 0755);
        }
        
        foreach($tables as $table_name => $table) {

            // Message to the user
            $output->writeln('Creating CRUD for table <comment>' . $table_name . '</comment>.');
            
            $TABLENAME = $table_name;
            $CLASSNAME = SharedFunctions::getInstance()->camelize($TABLENAME);
            $CONTROLLER = $CONTROLLER_NAMESPACE . $CLASSNAME . 'Controller';
    
            // Router file
            $_router = @file_get_contents($TEMPLATES_PATH . '/api_router.php');
            $_router = str_replace("__TABLENAME__", $TABLENAME, $_router);
            $_router = str_replace("__CONTROLLER__", $CONTROLLER, $_router);
            
            // Class Controller
            $_controller = @file_get_contents($TEMPLATES_PATH . '/api_class_controller.php');
            $_controller = str_replace("__MODULE__", $this->moduleName, $_controller);
            $_controller = str_replace("__CLASSNAME__", $CLASSNAME, $_controller);
            
            // Create router file
            $fp = @fopen($API_PATH . '/Resources/config/routes/' . $TABLENAME . '.php', "w+");
            @fwrite($fp, $_router);
            @fclose($fp);

            // Create controller class
            $fp = @fopen($API_PATH . '/Controller/' . $CLASSNAME . 'Controller.php', "w+");
            @fwrite($fp, $_controller);
            @fclose($fp);
        }

        // Message to the user
        $output->writeln('Generated API on <comment>src/' . $this->moduleName . '</comment>.');
    }
    
}
