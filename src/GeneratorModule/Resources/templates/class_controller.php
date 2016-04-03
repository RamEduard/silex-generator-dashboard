<?php

namespace __MODULE__\Controller;

use App;
use __MODULE__\Model\__CLASSNAME__;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Description of __CLASSNAME__Controller
 *
 * @author Ramón Serrano <ramon.calle.88@gmail.com>
 */
class __CLASSNAME__Controller 
{

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
		$rows = array();

	    $find_sql = "SELECT * FROM `__TABLENAME__`";
	    $rows_sql = $app['db']->fetchAll($find_sql, array());

	    foreach ($rows_sql as $row_key => $row_sql) {
            for ($i = 0; $i < count($table_columns); $i++) {
                $rows[$row_key][$table_columns[$i]] = $row_sql[$table_columns[$i]];
            }
        }

	    return $app['twig']->render('__TABLENAME__/list.html.twig', array(
	    	"table_columns" => $table_columns,
	        "primary_key" => $primary_key,
	    	"rows" => $rows
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