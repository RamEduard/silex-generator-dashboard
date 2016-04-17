<?php

namespace __MODULE__\Controller;

use App;
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
     * List __CLASSNAME__ action
     * 
     * @param App $app
     * @return string
     */
    function index(App $app)
    {
        $rows = array();

	    return new JsonResponse($rows,200);
    }
    
    /**
     * Create action page
     * 
     * @param App $app
     * @return JsonResponse
     */
    function create(App $app)
    {
    	if("POST" == $app['request']->getMethod()){

			return new JsonResponse(array('inserted' => true), 200);	        
	    }
	    
	    return new JsonResponse(array('inserted' => false), 200);
    }
    
    /**
     * Edit action page
     * 
     * @param App $app
     * @param int $id
     * @return JsonResponse
     */
    function edit(App $app, $id)
    {
        return new JsonResponse(array('updated' => true), 200);
    }
    
    /**
     * Delete action page
     * 
     * @param App $app
     * @param int $id
     * @return JsonResponse
     */
    function delete(App $app, $id)
    {
        return new JsonResponse(array('deleted' => true), 200);
    }
}