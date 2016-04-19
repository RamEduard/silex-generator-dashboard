<?php

namespace __MODULE__\Controller;

use App;
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
     * Authenticate middleware
     */
    public function authenticate() 
    {
        $valid_passwords = array (
            "admin" => "admin",
        );
        $valid_users = array_keys($valid_passwords);

        $user = isset($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER'] : '';
        $pass = isset($_SERVER['PHP_AUTH_PW']) ? $_SERVER['PHP_AUTH_PW'] : '';

        return (in_array($user, $valid_users)) && ($pass == $valid_passwords[$user]);
    }

    /**
     * Create or Update usuarios
     */
    function restricted(App $app)
    {
        // Authenticate
        $validated = $this->authenticate();
        
        if (!$validated) {
            $response = new JsonResponse('Not Authorized', 401);
            $response->headers->set('WWW-Authenticate', 'Basic realm="My Realm"');
        } else {
            $response = new JsonResponse(array('response' => 'Authorized'), 401);
        }
        
        return $response;
    }

	/**
     * List __CLASSNAME__ action
     * 
     * @param App $app
     * @return string
     */
    function index(App $app)
    {
        if (isset($app['db'])) {
            $db = $app['db'];
        } else if (isset($app['orm.em'])) {
            $db = $app['orm.em']->getConnection();
        } else {
            throw new Exception("DB connection not found");
        }

        $rows = __CLASSNAME__::getInstance($db)->getAll();

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
        if (isset($app['db'])) {
            $db = $app['db'];
        } else if (isset($app['orm.em'])) {
            $db = $app['orm.em']->getConnection();
        } else {
            throw new Exception("DB connection not found");
        }

    	if("POST" == $app['request']->getMethod()) {
            // Data
            $data = $app['request']->request->all();

            // Affected rows
            $affectedRows = __CLASSNAME__::getInstance($db)->insert($data);

			return new JsonResponse(array(
                'inserted' => ($affectedRows > 0)
                ), 200);
	    }
	    
	    return new JsonResponse(array(
            'error' => 'Only method POST'
            ), 400);
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
        if (isset($app['db'])) {
            $db = $app['db'];
        } else if (isset($app['orm.em'])) {
            $db = $app['orm.em']->getConnection();
        } else {
            throw new Exception("DB connection not found");
        }

        if ("POST" == $app['request']->getMethod() || "PUT" == $app['request']->getMethod()) {
            // Data
            $data = $app['request']->request->all();

            // Affected rows
            $affectedRows = __CLASSNAME__::getInstance($db)->update($data);

            return new JsonResponse(array(
                'updated' => ($affectedRows > 0)
                ), 200);
        }

        return new JsonResponse(array(
            'error' => 'Only method POST|PUT allowed'
            ), 400);
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
        if (isset($app['db'])) {
            $db = $app['db'];
        } else if (isset($app['orm.em'])) {
            $db = $app['orm.em']->getConnection();
        } else {
            throw new Exception("DB connection not found");
        }

        if("DELETE" == $app['request']->getMethod()) {
            // Affected rows
            $affectedRows = __CLASSNAME__::getInstance($db)->delete($id);

            return new JsonResponse(array(
                'deleted' => ($affectedRows > 0)
                ), 200);
        }

        return new JsonResponse(array(
            'error' => 'Only method DELETE allowed'
            ), 400);
    }
}