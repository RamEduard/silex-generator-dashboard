<?php

namespace GeneratorModule\Command;

use ConsoleSymfonyCommandsModule\ConsoleStore;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Description of AssetInstallCommand
 *
 * @author Ramón Serrano <ramon.calle.88@gmail.com>
 */
class DashboardInstallCommand extends Command
{

    /**
     * Dashboard Module
     */
    protected $moduleName = 'DashboardModule';
    
    public function __construct()
    {
        parent::__construct('generate:dashboard:install');
    }
    
    protected function configure()
    {
        $this
            ->addArgument('module_name', InputArgument::OPTIONAL, 'Module Name for Dashboard', 'DashboardModule')
            ->setDescription('Generate dashboard administrator');
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        # MVC object
        $app = ConsoleStore::retrieve('app');

        // Set Module name
        $moduleName = $input->getArgument('module_name');

        if ($moduleName != '') $this->moduleName = $moduleName;
        
        // Array tables
        $tables = $this->getArrayTables($app);

        // Create Dashboard Module
        $DASHBOARD_PATH      = dirname($app->getAppDir()) . '/src/' . $this->moduleName;
        $DASHBOARD_BASE_NAME = str_replace('Module', '', $this->moduleName);
        $TEMPLATES_PATH      = dirname(__DIR__) . '/Resources/templates';
        $MENU_OPTIONS        = "";

        // Message to the user
        $output->writeln('Creating Module and Models, Controllers && Views on <comment>' . $DASHBOARD_PATH . '</comment>.');

        // Module required templates
        $_module = @file_get_contents($TEMPLATES_PATH . '/class_module.php');
        $_module = str_replace("__MODULE__", $this->moduleName, $_module);

        // Injection
        $_extension = @file_get_contents($TEMPLATES_PATH . '/class_extension.php');
        $_extension = str_replace("__MODULE__", $this->moduleName, $_extension);

        // UploadImage
        $_uploadImage = @file_get_contents($TEMPLATES_PATH . '/class_upload_image.php');
        $_uploadImage = str_replace("__MODULE__", $this->moduleName, $_uploadImage);

        // QueryData
        $_queryData = @file_get_contents($TEMPLATES_PATH . '/class_query_data.php');
        $_queryData = str_replace("__MODULE__", $this->moduleName, $_queryData);

        // auth controller
        $_authController = @file_get_contents($TEMPLATES_PATH . '/controllers/class_auth.php');
        $_authController = str_replace("__MODULE__", $this->moduleName, $_authController);

        // dashboard controller
        $_dashboardController = @file_get_contents($TEMPLATES_PATH . '/controllers/class_dashboard.php');
        $_dashboardController = str_replace("__MODULE__", $this->moduleName, $_dashboardController);

        // image controller
        $_imageController = @file_get_contents($TEMPLATES_PATH . '/controllers/class_image.php');
        $_imageController = str_replace("__MODULE__", $this->moduleName, $_imageController);

        // Base namespace controller
        $CONTROLLER_NAMESPACE = $this->moduleName . '\\\\Controller\\\\';

        // auth route
        $_authRouter = @file_get_contents($TEMPLATES_PATH . '/routes/auth.php');
        $_authRouter = str_replace("__CONTROLLER__", $CONTROLLER_NAMESPACE . 'AuthController', $_authRouter);

        // dashboard route
        $_dashboardRouter = @file_get_contents($TEMPLATES_PATH . '/routes/dashboard.php');
        $_dashboardRouter = str_replace("__CONTROLLER__", $CONTROLLER_NAMESPACE . 'DashboardController', $_dashboardRouter);

        // image route
        $_imageRouter = @file_get_contents($TEMPLATES_PATH . '/routes/image.php');
        $_imageRouter = str_replace("__CONTROLLER__", $CONTROLLER_NAMESPACE . 'ImageController', $_imageRouter);

        if (!empty($tables)) {
            // Create Dir DashboardModule
            @mkdir($DASHBOARD_PATH, 0755);
            // Menu view
            $fp = @fopen($DASHBOARD_PATH . '/' . $this->moduleName . '.php', "w+");
            @fwrite($fp, $_module);
            @fclose($fp);
            // Create Dir Controller
            @mkdir($DASHBOARD_PATH . '/Controller', 0755);
            // Auth Controller
            $fp = @fopen($DASHBOARD_PATH . '/Controller/AuthController.php', "w+");
            @fwrite($fp, $_authController);
            @fclose($fp);
            // Dashboard Controller
            $fp = @fopen($DASHBOARD_PATH . '/Controller/DashboardController.php', "w+");
            @fwrite($fp, $_dashboardController);
            @fclose($fp);
            // Image Controller
            $fp = @fopen($DASHBOARD_PATH . '/Controller/ImageController.php', "w+");
            @fwrite($fp, $_imageController);
            @fclose($fp);
            // Create Dir Controller
            @mkdir($DASHBOARD_PATH . '/Injection', 0755);
            // Extension Injection
            $fp = @fopen($DASHBOARD_PATH . '/Injection/' . $DASHBOARD_BASE_NAME . 'Extension.php', "w+");
            @fwrite($fp, $_extension);
            @fclose($fp);
            // Upload Image
            $fp = @fopen($DASHBOARD_PATH . '/UploadImage.php', "w+");
            @fwrite($fp, $_uploadImage);
            @fclose($fp);
            // QueryData
            $fp = @fopen($DASHBOARD_PATH . '/QueryData.php', "w+");
            @fwrite($fp, $_queryData);
            @fclose($fp);
            // Create Dir Model
            @mkdir($DASHBOARD_PATH . '/Model', 0755);
            // Create Dir Resources
            @mkdir($DASHBOARD_PATH . '/Resources', 0755);
            // Create Dir routes
            @mkdir($DASHBOARD_PATH . '/Resources/config', 0755);
            @mkdir($DASHBOARD_PATH . '/Resources/config/routes', 0755);
            // Auth router
            $fp = @fopen($DASHBOARD_PATH . '/Resources/config/routes/auth.php', "w+");
            @fwrite($fp, $_authRouter);
            @fclose($fp);
            // Dashboard router
            $fp = @fopen($DASHBOARD_PATH . '/Resources/config/routes/dashboard.php', "w+");
            @fwrite($fp, $_dashboardRouter);
            @fclose($fp);
            // Image router
            $fp = @fopen($DASHBOARD_PATH . '/Resources/config/routes/image.php', "w+");
            @fwrite($fp, $_imageRouter);
            @fclose($fp);
            // Create Dir views
            @mkdir($DASHBOARD_PATH . '/Resources/views', 0755);
            // Copy views auth and dashboard
            $this->resourceCopy($TEMPLATES_PATH . '/views', $DASHBOARD_PATH . '/Resources/views');
        }

        foreach($tables as $table_name => $table) {

            // Message to the user
            $output->writeln('Creating CRUD for table <comment>' . $table_name . '</comment>.');

            if ($table_name === 'image') continue;

            $table_columns = $table['columns'];

            $TABLENAME = $table_name;
            $CLASSNAME = $this->camelize($TABLENAME);
            $CONTROLLER = $CONTROLLER_NAMESPACE . $CLASSNAME . 'Controller';
            $TABLE_PRIMARYKEY = $table['primary_key'];

            $TABLECOLUMNS_ARRAY = "";
            $TABLECOLUMNS_TYPE_ARRAY = "";          
            $TABLECOLUMNS_INITIALDATA_EMPTY_ARRAY = "";
            $TABLECOLUMNS_INITIALDATA_ARRAY = "";

            $EXTERNALS_FOR_LIST = "";
            $EXTERNALSFIELDS_FOR_FORM = "";
            $FIELDS_FOR_FORM = "";

            $INSERT_QUERY_FIELDS = array();
            $INSERT_EXECUTE_FIELDS = array();
            $UPDATE_QUERY_FIELDS = array();
            $UPDATE_EXECUTE_FIELDS = array();

            $EDIT_FORM_TEMPLATE = "";

            $MENU_OPTIONS .= "" .
            "<li class=\"treeview {% if option is defined and (option == '" . $TABLENAME . "_list' or option == '" . $TABLENAME . "_create' or option == '" . $TABLENAME . "_edit') %}active{% endif %}\">" . "\n" .
            "    <a href=\"#\">" . "\n" .
            "        <i class=\"fa fa-folder-o\"></i>" . "\n" .
            "        <span>" . $TABLENAME . "</span>" . "\n" .
            "        <i class=\"fa pull-right fa-angle-right\"></i>" . "\n" .
            "    </a>" . "\n" .
            "    <ul class=\"treeview-menu\" style=\"display: none;\">" . "\n" .
            "        <li {% if option is defined and option == '" . $TABLENAME . "_list' %}class=\"active\"{% endif %}><a href=\"{{ path('" . $TABLENAME . "_list') }}\" style=\"margin-left: 10px;\"><i class=\"fa fa-angle-double-right\"></i> List</a></li>" . "\n" .
            "        <li {% if option is defined and option == '" . $TABLENAME . "_create' %}class=\"active\"{% endif %}><a href=\"{{ path('" . $TABLENAME . "_create') }}\" style=\"margin-left: 10px;\"><i class=\"fa fa-angle-double-right\"></i> Create</a></li>" . "\n" .
            "    </ul>" . "\n" .
            "</li>" . "\n\n";

            $count_externals = 0;
            foreach($table_columns as $table_column){
                $TABLECOLUMNS_ARRAY .= "\t\t" . "'". $table_column['name'] . "', \n";
                $TABLECOLUMNS_TYPE_ARRAY .= "\t\t" . "'". $table_column['type'] . "', \n";              
                if(!$table_column['primary'] || ($table_column['primary'] && !$table_column['auto'])){
                    $TABLECOLUMNS_INITIALDATA_EMPTY_ARRAY .= "\t\t" . "'". $table_column['name'] . "' => '', \n";
                    $TABLECOLUMNS_INITIALDATA_ARRAY .= "\t\t" . "'". $table_column['name'] . "' => \$row_sql['".$table_column['name']."'], \n";

                    $INSERT_QUERY_FIELDS[] = "`" . $table_column['name'] . "`";
                    $INSERT_EXECUTE_FIELDS[] = "\$data['" . $table_column['name'] . "']";
                    $UPDATE_QUERY_FIELDS[] = "`" . $table_column['name'] . "` = ?";
                    $UPDATE_EXECUTE_FIELDS[] = "\$data['" . $table_column['name'] . "']";

                    if(strpos($table_column['type'], 'text') !== false){
                        $EDIT_FORM_TEMPLATE .= "" .
                        "\t\t\t\t\t\t\t\t\t" . "<div class='form-group'>" . "\n" .
                        "\t\t\t\t\t\t\t\t\t" . "    {{ form_label(form." . $table_column['name'] . ") }}" . "\n" .
                        "\t\t\t\t\t\t\t\t\t" . "    {{ form_widget(form." . $table_column['name'] . ", { attr: { 'class': 'form-control textarea', 'style': 'width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;' }}) }}" . "\n" .
                        "\t\t\t\t\t\t\t\t\t" . "</div>" . "\n\n";
                    }
                    else {
                        $EDIT_FORM_TEMPLATE .= "" .
                        "\t\t\t\t\t\t\t\t\t" . "<div class='form-group'>" . "\n" .
                        "\t\t\t\t\t\t\t\t\t" . "    {{ form_label(form." . $table_column['name'] . ") }}" . "\n" .
                        "\t\t\t\t\t\t\t\t\t" . "    {{ form_widget(form." . $table_column['name'] . ", { attr: { 'class': 'form-control' }}) }}" . "\n" .
                        "\t\t\t\t\t\t\t\t\t" . "</div>" . "\n\n";
                    }
                }

                $field_nullable = $table_column['nullable'] ? "true" : "false";

                if($table_column['external']){
                    $external_table = $tables[$table_column['external']];

                    $external_primary_key = $external_table['primary_key'];
                    $external_select_field = false;
                    $search_names_foreigner_key = array('name','title','e?mail','username');

                    if(!empty($app['usr_search_names_foreigner_key'])){
                        $search_names_foreigner_key = array_merge(
                            $app['usr_search_names_foreigner_key'],
                            $search_names_foreigner_key);
                    }

                        // pattern to match a name column, with or whitout a 3 to 4 Char prefix
                    $search_names_foreigner_key = '#^(.{3,4}_)?('.implode('|',$search_names_foreigner_key).')$#i';

                    foreach($external_table['columns'] as $external_column){
                        if( preg_match($search_names_foreigner_key, $external_column['name'])){
                            $external_select_field = $external_column['name'];
                        }
                    }

                    if(!$external_select_field){
                        $external_select_field = $external_primary_key;
                    }

                    $external_cond = $count_externals > 0 ? "else if" : "if";

                    $EXTERNALS_FOR_LIST .= "" .
                    "\t\t\t" . $external_cond . "(\$table_columns[\$i] == '" . $table_column['name'] . "'){" . "\n" .
                    "\t\t\t" . "    \$findexternal_sql = 'SELECT `" . $external_select_field . "` FROM `" . $table_column['external'] . "` WHERE `" . $external_primary_key . "` = ?';" . "\n" .
                    "\t\t\t" . "    \$findexternal_row = \$app['db']->fetchAssoc(\$findexternal_sql, array(\$row_sql[\$table_columns[\$i]]));" . "\n" .
                    "\t\t\t" . "    \$rows[\$row_key][\$table_columns[\$i]] = \$findexternal_row['" . $external_select_field . "'];" . "\n" .
                    "\t\t\t" . "}" . "\n";


                    $EXTERNALSFIELDS_FOR_FORM .= "" .
                    "\t" . "\$options = array();" . "\n" .
                    "\t" . "\$findexternal_sql = 'SELECT `" . $external_primary_key . "`, `" . $external_select_field . "` FROM `" . $table_column['external'] . "`';" . "\n" .
                    "\t" . "\$findexternal_rows = \$app['db']->fetchAll(\$findexternal_sql, array());" . "\n" .
                    "\t" . "foreach(\$findexternal_rows as \$findexternal_row){" . "\n" .
                    "\t" . "    \$options[\$findexternal_row['" . $external_primary_key . "']] = \$findexternal_row['" . $external_select_field . "'];" . "\n" .
                    "\t" . "}" . "\n" .
                    "\t" . "if(count(\$options) > 0){" . "\n" .
                    "\t" . "    \$form = \$form->add('" . $table_column['name'] . "', 'choice', array(" . "\n" .
                    "\t" . "        'required' => " . $field_nullable . "," . "\n" .
                    "\t" . "        'choices' => \$options," . "\n" .
                    "\t" . "        'expanded' => false," . "\n" .
                    "\t" . "        'constraints' => new Assert\Choice(array_keys(\$options))" . "\n" .
                    "\t" . "    ));" . "\n" .
                    "\t" . "}" . "\n" .
                    "\t" . "else{" . "\n" .
                    "\t" . "    \$form = \$form->add('" . $table_column['name'] . "', 'text', array('required' => " . $field_nullable . "));" . "\n" .
                    "\t" . "}" . "\n\n";

                    $count_externals++;
                }
                else{
                    if(!$table_column['primary']){

                        if(strpos($table_column['type'], 'text') !== false){
                            $FIELDS_FOR_FORM .= "" .
                            "\t" . "\$form = \$form->add('" . $table_column['name'] . "', 'textarea', array('required' => " . $field_nullable . "));" . "\n";
                        }
                        else{
                            $FIELDS_FOR_FORM .= "" .
                            "\t" . "\$form = \$form->add('" . $table_column['name'] . "', 'text', array('required' => " . $field_nullable . "));" . "\n";
                        }
                    }
                    else if($table_column['primary'] && !$table_column['auto']){
                            $FIELDS_FOR_FORM .= "" .
                            "\t" . "\$form = \$form->add('" . $table_column['name'] . "', 'text', array('required' => " . $field_nullable . "));" . "\n";
                    }
                }
            }

            if($count_externals > 0){
                $EXTERNALS_FOR_LIST .= "" .
                "\t\t\t" . "else{" . "\n" .
                "\t\t\t" . "    \$rows[\$row_key][\$table_columns[\$i]] = \$row_sql[\$table_columns[\$i]];" . "\n" .
                "\t\t\t" . "}" . "\n";
            }

            if($EXTERNALS_FOR_LIST == ""){
                $EXTERNALS_FOR_LIST .= "" . 
                "\t\t" . "if( \$table_columns_type[\$i] != \"blob\") {" . "\n" .
                "\t\t\t\t" . "\$rows[\$row_key][\$table_columns[\$i]] = \$row_sql[\$table_columns[\$i]];" . "\n" . 
                "\t\t" . "} else {" .
                
                "\t\t\t\t" . "if( !\$row_sql[\$table_columns[\$i]] ) {" . "\n" .
                "\t\t\t\t\t\t" . "\$rows[\$row_key][\$table_columns[\$i]] = \"0 Kb.\";" . "\n" .
                "\t\t\t\t" . "} else {" . "\n" .
                   
                "\t\t\t\t\t\t" . "\$rows[\$row_key][\$table_columns[\$i]] = \" <a target='__blank' href='menu/download?id=\" . \$row_sql[\$table_columns[0]];" . "\n" .
                "\t\t\t\t\t\t" . "\$rows[\$row_key][\$table_columns[\$i]] .= \"&fldname=\" . \$table_columns[\$i];" . "\n" . 
                "\t\t\t\t\t\t" . "\$rows[\$row_key][\$table_columns[\$i]] .= \"&idfld=\" . \$table_columns[0];" . "\n" .
                "\t\t\t\t\t\t" . "\$rows[\$row_key][\$table_columns[\$i]] .= \"'>\";" . "\n" .
                "\t\t\t\t\t\t" . "\$rows[\$row_key][\$table_columns[\$i]] .= number_format(strlen(\$row_sql[\$table_columns[\$i]]) / 1024, 2) . \" Kb.\";" . "\n" .
                "\t\t\t\t\t\t" . "\$rows[\$row_key][\$table_columns[\$i]] .= \"</a>\";" . "\n" .
                    
                "\t\t\t\t" . "}" . "\n" .
                
                "\t\t" . "}";
            }


            $INSERT_QUERY_VALUES = array();
            foreach($INSERT_QUERY_FIELDS as $INSERT_QUERY_FIELD){
                $INSERT_QUERY_VALUES[] = "?";
            }
            $INSERT_QUERY_VALUES = implode(", ", $INSERT_QUERY_VALUES);
            $INSERT_QUERY_FIELDS = implode(", ", $INSERT_QUERY_FIELDS);
            $INSERT_EXECUTE_FIELDS = implode(", ", $INSERT_EXECUTE_FIELDS);

            $UPDATE_QUERY_FIELDS = implode(", ", $UPDATE_QUERY_FIELDS);
            $UPDATE_EXECUTE_FIELDS = implode(", ", $UPDATE_EXECUTE_FIELDS);

            // Router file
            $_router = @file_get_contents($TEMPLATES_PATH . '/router.php');
            $_router = str_replace("__TABLENAME__", $TABLENAME, $_router);
            $_router = str_replace("__CONTROLLER__", $CONTROLLER, $_router);

            // Model class
            $_model = @file_get_contents($TEMPLATES_PATH . '/class_model.php');
            $_model = str_replace("__MODULE__", $this->moduleName, $_model);
            $_model = str_replace("__TABLENAME__", $TABLENAME, $_model);
            $_model = str_replace("__CLASSNAME__", $CLASSNAME, $_model);
            $_model = str_replace("__TABLE_PRIMARYKEY__", $TABLE_PRIMARYKEY, $_model);

            // Class Controller
            $_controller = @file_get_contents($TEMPLATES_PATH . '/class_controller.php');
            $_controller = str_replace("__MODULE__", $this->moduleName, $_controller);
            $_controller = str_replace("__TABLENAME__", $TABLENAME, $_controller);
            $_controller = str_replace("__CLASSNAME__", $CLASSNAME, $_controller);
            $_controller = str_replace("__TABLE_PRIMARYKEY__", $TABLE_PRIMARYKEY, $_controller);
            $_controller = str_replace("__TABLECOLUMNS_ARRAY__", $TABLECOLUMNS_ARRAY, $_controller);
            $_controller = str_replace("__TABLECOLUMNS_TYPE_ARRAY__", $TABLECOLUMNS_TYPE_ARRAY, $_controller);          
            $_controller = str_replace("__TABLECOLUMNS_INITIALDATA_EMPTY_ARRAY__", $TABLECOLUMNS_INITIALDATA_EMPTY_ARRAY, $_controller);
            $_controller = str_replace("__TABLECOLUMNS_INITIALDATA_ARRAY__", $TABLECOLUMNS_INITIALDATA_ARRAY, $_controller);
            $_controller = str_replace("__EXTERNALS_FOR_LIST__", $EXTERNALS_FOR_LIST, $_controller);
            $_controller = str_replace("__EXTERNALSFIELDS_FOR_FORM__", $EXTERNALSFIELDS_FOR_FORM, $_controller);
            $_controller = str_replace("__FIELDS_FOR_FORM__", $FIELDS_FOR_FORM, $_controller);

            $_controller = str_replace("__INSERT_QUERY_FIELDS__", $INSERT_QUERY_FIELDS, $_controller);
            $_controller = str_replace("__INSERT_QUERY_VALUES__", $INSERT_QUERY_VALUES, $_controller);
            $_controller = str_replace("__INSERT_EXECUTE_FIELDS__", $INSERT_EXECUTE_FIELDS, $_controller);

            $_controller = str_replace("__UPDATE_QUERY_FIELDS__", $UPDATE_QUERY_FIELDS, $_controller);
            $_controller = str_replace("__UPDATE_EXECUTE_FIELDS__", $UPDATE_EXECUTE_FIELDS, $_controller);


            $_list_template = @file_get_contents($TEMPLATES_PATH . '/list.html.twig');
            $_list_template = str_replace("__TABLENAME__", $TABLENAME, $_list_template);
            $_list_template = str_replace("__TABLENAMEUP__", ucfirst(strtolower($TABLENAME)), $_list_template);

            $_create_template = @file_get_contents($TEMPLATES_PATH . '/create.html.twig');
            $_create_template = str_replace("__TABLENAME__", $TABLENAME, $_create_template);
            $_create_template = str_replace("__TABLENAMEUP__", ucfirst(strtolower($TABLENAME)), $_create_template);
            $_create_template = str_replace("__EDIT_FORM_TEMPLATE__", $EDIT_FORM_TEMPLATE, $_create_template);

            $_edit_template = @file_get_contents($TEMPLATES_PATH . '/edit.html.twig');
            $_edit_template = str_replace("__TABLENAME__", $TABLENAME, $_edit_template);
            $_edit_template = str_replace("__TABLENAMEUP__", ucfirst(strtolower($TABLENAME)), $_edit_template);
            $_edit_template = str_replace("__EDIT_FORM_TEMPLATE__", $EDIT_FORM_TEMPLATE, $_edit_template);

            $_menu_template = @file_get_contents($TEMPLATES_PATH . '/menu.html.twig');
            $_menu_template = str_replace("__MENU_OPTIONS__", $MENU_OPTIONS, $_menu_template);

            // Create router file
            $fp = @fopen($DASHBOARD_PATH . '/Resources/config/routes/' . $TABLENAME . '.php', "w+");
            @fwrite($fp, $_router);
            @fclose($fp);

            // Create controller class
            $fp = @fopen($DASHBOARD_PATH . '/Controller/' . $CLASSNAME . 'Controller.php', "w+");
            @fwrite($fp, $_controller);
            @fclose($fp);

            // Create model class
            $fp = @fopen($DASHBOARD_PATH . '/Model/' . $CLASSNAME . '.php', "w+");
            @fwrite($fp, $_model);
            @fclose($fp);

            // Controller views folder
            @mkdir($DASHBOARD_PATH . '/Resources/views/' . $TABLENAME, 0755);

            // Create view
            $fp = @fopen($DASHBOARD_PATH . '/Resources/views/' . $TABLENAME . "/create.html.twig", "w+");
            @fwrite($fp, $_create_template);
            @fclose($fp);

            // Edit view
            $fp = @fopen($DASHBOARD_PATH . '/Resources/views/' . $TABLENAME . "/edit.html.twig", "w+");
            @fwrite($fp, $_edit_template);
            @fclose($fp);

            // List view
            $fp = @fopen($DASHBOARD_PATH . '/Resources/views/' . $TABLENAME . "/list.html.twig", "w+");
            @fwrite($fp, $_list_template);
            @fclose($fp);

            // Menu view
            $fp = @fopen($app->getAppDir() . "/views/menu.html.twig", "w+");
            @fwrite($fp, $_menu_template);
            @fclose($fp);

            // Message to the user
            $output->writeln('Router, Controller, Model and Views for ' . $table_name . ' created.');
        }

        // Message to the user
        $output->writeln('Installed DashboardModule as <comment>' . $this->moduleName . '</comment>.');
    }
    
    /**
     * Get tables from database
     * 
     * @param string $sourceDir
     * @param string $destinyDir
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

    /**
     * Copy directory to destiny directory
     * 
     * @param string $sourceDir
     * @param string $destinyDir
     */
    protected function resourceCopy($sourceDir, $destinyDir)
    {
        $dir = opendir($sourceDir);
        @mkdir($destinyDir);
        while (false !== ( $file = readdir($dir))) {
            if (( $file != '.' ) && ( $file != '..' )) {
                if (is_dir($sourceDir . '/' . $file)) {
                    $this->resourceCopy($sourceDir . '/' . $file, $destinyDir . '/' . $file);
                } else {
                    copy($sourceDir . '/' . $file, $destinyDir . '/' . $file);
                }
            }
        }
        closedir($dir);
    }
}
