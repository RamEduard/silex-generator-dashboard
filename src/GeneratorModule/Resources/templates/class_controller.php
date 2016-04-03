<?php

namespace __MODULE__\Controller;

use App;
use __MODULE__\QueryData;
use __MODULE__\Model\__CLASSNAME__;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Description of __CLASSNAME__Controller
 *
 * @author Ramón Serrano <ramon.calle.88@gmail.com>
 */
class __CLASSNAME__Controller 
{

	/**
     * List JSON __CLASSNAME__ action
     * 
     * @param Request $request
     * @param App $app
     * @return Response
     */
	function listJson(Request $request, App $app)
	{
		$start = 0;
	    $vars = $request->query->all();
	    $qsStart = (int)$vars["start"];
	    $search = $vars["search"];
	    $order = $vars["order"];
	    $columns = $vars["columns"];
	    $qsLength = (int)$vars["length"];    
	    
	    if($qsStart) {
	        $start = $qsStart;
	    }    
		
	    $index = $start;   
	    $rowsPerPage = $qsLength;
	       
	    $rows = array();
	    
	    $searchValue = $search['value'];
	    $orderValue = $order[0];
	    
	    $orderClause = "";
	    if($orderValue) {
	        $orderClause = " ORDER BY ". $columns[(int)$orderValue['column']]['data'] . " " . $orderValue['dir'];
	    }
	    
	    $table_columns = array(
	__TABLECOLUMNS_ARRAY__
	    );
	    
	    $table_columns_type = array(
	__TABLECOLUMNS_TYPE_ARRAY__
	    );    
	    
	    $whereClause = "";
	    
	    $i = 0;
	    foreach($table_columns as $col){
	        
	        if ($i == 0) {
	           $whereClause = " WHERE";
	        }
	        
	        if ($i > 0) {
	            $whereClause =  $whereClause . " OR"; 
	        }
	        
	        $whereClause =  $whereClause . " " . $col . " LIKE '%". $searchValue ."%'";
	        
	        $i = $i + 1;
	    }
	    
	    $recordsTotal = $app['db']->executeQuery("SELECT * FROM `__TABLENAME__`" . $whereClause . $orderClause)->rowCount();
	    
	    $find_sql = "SELECT * FROM `__TABLENAME__`". $whereClause . $orderClause . " LIMIT ". $index . "," . $rowsPerPage;
	    $rows_sql = $app['db']->fetchAll($find_sql, array());

	    foreach($rows_sql as $row_key => $row_sql){
	        for($i = 0; $i < count($table_columns); $i++){

	__EXTERNALS_FOR_LIST__

	        }
	    }    
	    
	    $queryData = new QueryData();
	    $queryData->start = $start;
	    $queryData->recordsTotal = $recordsTotal;
	    $queryData->recordsFiltered = $recordsTotal;
	    $queryData->data = $rows;
	    
	    return new Response(json_encode($queryData), 200);
	}

	/**
     * Download __CLASSNAME__ action
     * 
     * @param Request $request
     * @param App $app
     */
	function download(Request $request, App $app)
	{
		// menu
	    $rowid = $request->get('id');
	    $idfldname = $request->get('idfld');
	    $fieldname = $request->get('fldname');
	    
	    if( !$rowid || !$fieldname ) die("Invalid data");
	    
	    $find_sql = "SELECT " . $fieldname . " FROM " . __TABLENAME__ . " WHERE ".$idfldname." = ?";
	    $row_sql = $app['db']->fetchAssoc($find_sql, array($rowid));

	    if(!$row_sql){
	        $app['session']->getFlashBag()->add(
	            'danger',
	            array(
	                'message' => 'Row not found!',
	            )
	        );        
	        return $app->redirect($app['url_generator']->generate('menu_list'));
	    }

	    header('Content-Description: File Transfer');
	    header('Content-Type: image/jpeg');
	    header("Content-length: ".strlen( $row_sql[$fieldname] ));
	    header('Expires: 0');
	    header('Cache-Control: public');
	    header('Pragma: public');
	    ob_clean();    
	    echo $row_sql[$fieldname];
	    exit();
	}

	/**
     * List __CLASSNAME__ action
     * 
     * @param App $app
     * @return string
     */
    function index(App $app)
    {
        $table_columns = array(
	__TABLECOLUMNS_ARRAY__
	    );

	    $primary_key = "__TABLE_PRIMARYKEY__";	

	    return $app['twig']->render('__TABLENAME__/list.html.twig', array(
	    	"table_columns" => $table_columns,
	        "primary_key" => $primary_key
	    ));
    }
    
    /**
     * Create action page
     * 
     * @param App $app
     * @return RedirectResponse|string
     */
    function create(App $app)
    {
        $initial_data = array(
	__TABLECOLUMNS_INITIALDATA_EMPTY_ARRAY__
	    );

	    $form = $app['form.factory']->createBuilder('form', $initial_data);

	__EXTERNALSFIELDS_FOR_FORM__

	__FIELDS_FOR_FORM__

	    $form = $form->getForm();

	    if("POST" == $app['request']->getMethod()){

	        $form->handleRequest($app["request"]);

	        if ($form->isValid()) {
	            $data = $form->getData();

	            $update_query = "INSERT INTO `__TABLENAME__` (__INSERT_QUERY_FIELDS__) VALUES (__INSERT_QUERY_VALUES__)";
	            $app['db']->executeUpdate($update_query, array(__INSERT_EXECUTE_FIELDS__));            


	            $app['session']->getFlashBag()->add(
	                'success',
	                array(
	                    'message' => '__TABLENAME__ created!',
	                )
	            );
	            return $app->redirect($app['url_generator']->generate('__TABLENAME___list'));

	        }
	    }

	    return $app['twig']->render('__TABLENAME__/create.html.twig', array(
	        "form" => $form->createView()
	    ));
    }
    
    /**
     * Edit action page
     * 
     * @param App $app
     * @param int $id
     * @return RedirectResponse|string
     */
    function edit(App $app, $id)
    {
        $find_sql = "SELECT * FROM `__TABLENAME__` WHERE `__TABLE_PRIMARYKEY__` = ?";
	    $row_sql = $app['db']->fetchAssoc($find_sql, array($id));

	    if(!$row_sql){
	        $app['session']->getFlashBag()->add(
	            'danger',
	            array(
	                'message' => 'Row not found!',
	            )
	        );        
	        return $app->redirect($app['url_generator']->generate('__TABLENAME___list'));
	    }

	    
	    $initial_data = array(
	__TABLECOLUMNS_INITIALDATA_ARRAY__
	    );


	    $form = $app['form.factory']->createBuilder('form', $initial_data);

	__EXTERNALSFIELDS_FOR_FORM__
	__FIELDS_FOR_FORM__

	    $form = $form->getForm();

	    if("POST" == $app['request']->getMethod()){

	        $form->handleRequest($app["request"]);

	        if ($form->isValid()) {
	            $data = $form->getData();

	            $update_query = "UPDATE `__TABLENAME__` SET __UPDATE_QUERY_FIELDS__ WHERE `__TABLE_PRIMARYKEY__` = ?";
	            $app['db']->executeUpdate($update_query, array(__UPDATE_EXECUTE_FIELDS__, $id));            


	            $app['session']->getFlashBag()->add(
	                'success',
	                array(
	                    'message' => '__TABLENAME__ edited!',
	                )
	            );
	            return $app->redirect($app['url_generator']->generate('__TABLENAME___edit', array("id" => $id)));

	        }
	    }

	    return $app['twig']->render('__TABLENAME__/edit.html.twig', array(
	        "form" => $form->createView(),
	        "id" => $id
	    ));
    }
    
    /**
     * Delete action page
     * 
     * @param App $app
     * @param int $id
     * @return RedirectResponse
     */
    function delete(App $app, $id)
    {
        $row_sql = __CLASSNAME__::getInstance($app['db'])->getById($id);

        if ($row_sql) {
            $result = __CLASSNAME__::getInstance($app['db'])
                        ->delete($id);
            
            if ($result) {
                $app['session']->getFlashBag()->add(
		            'success',
		            array(
		                'message' => '__TABLENAME__ deleted!',
		            )
		        );
            }
        } else {
            $app['session']->getFlashBag()->add(
	            'danger',
	            array(
	                'message' => 'Row not found!',
	            )
	        );
        }
		
		return $app->redirect($app['url_generator']->generate('__TABLENAME___list'));
    }
}