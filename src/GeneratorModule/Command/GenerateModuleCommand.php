<?php

namespace GeneratorModule\Command;

use ConsoleSymfonyCommandsModule\ConsoleStore;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use GeneratorModule\SharedFunctions;

/**
 * Description of GenerateModuleCommand
 *
 * @author Ramón Serrano <ramon.calle.88@gmail.com>
 */
class GenerateModuleCommand extends Command
{

    protected $moduleName;
    
    public function __construct()
    {
        parent::__construct('generate:module');
    }
    
    protected function configure()
    {
        $this
            ->addArgument('module_name', InputArgument::REQUIRED, 'Module Name')
            ->setDescription('Generate module');
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        # MVC object
        $app = ConsoleStore::retrieve('app');

        // Set Module name
        $moduleName = $input->getArgument('module_name');

        if ($moduleName != '') $this->moduleName = SharedFunctions::getInstance()->camelize($moduleName);

        // Create Dashboard Module
        $MODULE_PATH          = dirname($app->getAppDir()) . '/src/' . $this->moduleName;
        $MODULE_BASE_NAME     = str_replace('Module', '', $this->moduleName);
        $CONTROLLER_NAMESPACE = $this->moduleName . '\\\\Controller\\\\';
        $TEMPLATES_PATH       = dirname(__DIR__) . '/Resources/templates';

        // Message to the user
        $output->writeln('Creating Module on <info>' . $MODULE_PATH . '</info>.');
        
        // Module required templates
        $_module = @file_get_contents($TEMPLATES_PATH . '/class_module.php');
        $_module = str_replace("__MODULE__", $this->moduleName, $_module);

        // Injection
        $_extension = @file_get_contents($TEMPLATES_PATH . '/class_extension.php');
        $_extension = str_replace("__MODULE__", $this->moduleName, $_extension);
        $_extension = str_replace("__BASENAME__", $MODULE_BASE_NAME, $_extension);

        // Class Controller
        $_controller = @file_get_contents($TEMPLATES_PATH . '/class_default_controller.php');
        $_controller = str_replace("__MODULE__", $this->moduleName, $_controller);

        // Router default
        $_router = '<?php

/**
 * Default route
 *
 * @author Ramon Serrano <ramon.calle.88@gmail.com>
 */
$app->match("/' . strtolower(str_replace('Module', '', $moduleName)) . '", "' . $CONTROLLER_NAMESPACE . 'DefaultController::index")
    ->bind("generate_default_index");
';

        $_view = "{% extends 'layout/base.twig' %}
{% block body %}
\tDefault response on HTML
{% endblock %}";

        // Create Dir DashboardModule
        @mkdir($MODULE_PATH, 0755);
        // API Module
        $fp = @fopen($MODULE_PATH . '/' . $this->moduleName . '.php', "w+");
        @fwrite($fp, $_module);
        @fclose($fp);
        // Create Dir Controller
        @mkdir($MODULE_PATH . '/Controller', 0755);
        // Create Dir Entity
        @mkdir($MODULE_PATH . '/Entity', 0755);
        // Create Dir Injection
        @mkdir($MODULE_PATH . '/Injection', 0755);
        // Extension Injection
        $fp = @fopen($MODULE_PATH . '/Injection/' . $MODULE_BASE_NAME . 'Extension.php', "w+");
        @fwrite($fp, $_extension);
        @fclose($fp);
        // Create Dir Model
        @mkdir($MODULE_PATH . '/Model', 0755);
        // Create Dir Resources
        @mkdir($MODULE_PATH . '/Resources', 0755);
        // Create Dir routes
        @mkdir($MODULE_PATH . '/Resources/config', 0755);
        @mkdir($MODULE_PATH . '/Resources/config/routes', 0755);
        // Create Dir views
        @mkdir($MODULE_PATH . '/Resources/views', 0755);
        @mkdir($MODULE_PATH . '/Resources/views/default', 0755);

        // Create controller class
        $fp = @fopen($MODULE_PATH . '/Controller/DefaultController.php', "w+");
        @fwrite($fp, $_controller);
        @fclose($fp);

        // Create router file
        $fp = @fopen($MODULE_PATH . '/Resources/config/routes/default.php', "w+");
        @fwrite($fp, $_router);
        @fclose($fp);

        // List view
        $fp = @fopen($MODULE_PATH . '/Resources/views/default/index.twig', "w+");
        @fwrite($fp, $_view);
        @fclose($fp);

        // Message to the user
        $output->writeln('Generated Module on <info>' . $MODULE_PATH . '</info>.');
    }
    
}
