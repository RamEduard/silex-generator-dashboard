<?php

namespace GeneratorModule\Command;

use ConsoleSymfonyCommandsModule\ConsoleStore;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

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
            ->setDescription('Generate dashboard administrator');
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        # MVC object
        $app = ConsoleStore::retrieve('app');

        // Set Module name
        $moduleName = $input->getArgument('module_name');

        if ($moduleName != '') $this->moduleName = $this->camelize($moduleName);
        
        // Array tables
        $tables = $this->getArrayTables($app);

        // Create Dashboard Module
        $API_PATH             = dirname($app->getAppDir()) . '/src/' . $this->moduleName;
        $API_BASE_NAME        = str_replace('Module', '', $this->moduleName);
        // Base namespace controller
        $CONTROLLER_NAMESPACE = $this->moduleName . '\\\\Controller\\\\';
        $TEMPLATES_PATH       = dirname(__DIR__) . '/Resources/templates';

        // Message to the user
        $output->writeln('Creating Module and API on <comment>' . $API_PATH . '</comment>.');

        if (!empty($tables)) {
            // Create Dir DashboardModule
            @mkdir($API_PATH, 0755);
            // API Module
            $fp = @fopen($API_PATH . '/' . $this->moduleName . '.php', "w+");
            @fwrite($fp, $_module);
            @fclose($fp);
            // Create Dir Controller
            @mkdir($API_PATH . '/Controller', 0755);
            // Create Dir Injection
            @mkdir($API_PATH . '/Injection', 0755);
            // Extension Injection
            $fp = @fopen($API_PATH . '/Injection/' . $API_BASE_NAME . 'Extension.php', "w+");
            @fwrite($fp, $_extension);
            @fclose($fp);
            // Create Dir Model
            @mkdir($DASHBOARD_PATH . '/Model', 0755);
            // Create Dir Resources
            @mkdir($DASHBOARD_PATH . '/Resources', 0755);
            // Create Dir routes
            @mkdir($DASHBOARD_PATH . '/Resources/config', 0755);
            @mkdir($DASHBOARD_PATH . '/Resources/config/routes', 0755);
        }
        
        foreach($tables as $table_name => $table) {

            // Message to the user
            $output->writeln('Creating CRUD for table <comment>' . $table_name . '</comment>.');
            
            $TABLENAME = $table_name;
            $CLASSNAME = $this->camelize($TABLENAME);
            $CONTROLLER = $CONTROLLER_NAMESPACE . $CLASSNAME . 'Controller';
    
            // Router file
            $_router = @file_get_contents($TEMPLATES_PATH . '/api_router.php');
            $_router = str_replace("__TABLENAME__", $TABLENAME, $_router);
            $_router = str_replace("__CONTROLLER__", $CONTROLLER, $_router);
            
            // Class Controller
            $_controller = @file_get_contents($TEMPLATES_PATH . '/class_controller.php');
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
    
    /**
     * Get tables from database
     *
     * @param $app
     * @return array $tables
     */
    protected function getArrayTables($app)
    {
        $getTablesQuery = "SHOW TABLES";
        $getTablesResult = $app['db']->fetchAll($getTablesQuery, array());
        
        $_dbTables = array();
        $dbTables = array();

        foreach($getTablesResult as $getTableResult){

            $_dbTables[] = reset($getTableResult);

            $dbTables[] = array(
                "name" => reset($getTableResult),
                "columns" => array()
            );
        }

        foreach($dbTables as $dbTableKey => $dbTable){
            $getTableColumnsQuery = "SHOW COLUMNS FROM `" . $dbTable['name'] . "`";
            $getTableColumnsResult = $app['db']->fetchAll($getTableColumnsQuery, array());

            foreach($getTableColumnsResult as $getTableColumnResult){
                $dbTables[$dbTableKey]['columns'][] = $getTableColumnResult;
            }

        }

        $tables = array();
        foreach($dbTables as $dbTable){

            if(count($dbTable['columns']) <= 1){
                continue;
            }

            $table_name = $dbTable['name'];
            $table_columns = array();
            $primary_key = false;

            $primary_keys = 0;
            $primary_keys_auto = 0;
            foreach($dbTable['columns'] as $column){
                if($column['Key'] == "PRI"){
                    $primary_keys++;
                }
                if($column['Extra'] == "auto_increment"){
                    $primary_keys_auto++;
                }
            }

            if($primary_keys === 1 || ($primary_keys > 1 && $primary_keys_auto === 1)){

                foreach($dbTable['columns'] as $column){

                    $external_table = false;

                    if($primary_keys > 1 && $primary_keys_auto == 1){
                        if($column['Extra'] == "auto_increment"){
                            $primary_key = $column['Field'];
                        }
                    }
                    else if($primary_keys == 1){
                        if($column['Key'] == "PRI"){
                            $primary_key = $column['Field'];
                        }
                    }
                    else{
                        continue 2;
                    }

                    if(substr($column['Field'], -3) == "_id"){
                        $_table_name = substr($column['Field'], 0, -3);

                        if(in_array($_table_name, $_dbTables)){
                            $external_table = $_table_name;
                        }
                    }

                    $table_columns[] = array(
                        "name" => $column['Field'],
                        "primary" => $column['Field'] == $primary_key ? true : false,
                        "nullable" => $column['Null'] == "NO" ? true : false,
                        "auto" => $column['Extra'] == "auto_increment" ? true : false,
                        "external" => $column['Field'] != $primary_key ? $external_table : false,
                        "type" => $column['Type']
                    );
                }

            }
            else{
                continue;
            }


            $tables[$table_name] = array(
                "primary_key" => $primary_key,
                "columns" => $table_columns
            );

        }

        return $tables;
    }

    /**
     * Camelize
     *
     * @param string $input
     * @return string
     */
    protected function camelize($input)
    {
        $replace = array(
            '-' => '',
            '_' => '',
            '.' => '',
            ' ' => '',
        );
        return @ucwords(str_replace(array_keys($replace), array_values($replace), $input));
    }
    
}
