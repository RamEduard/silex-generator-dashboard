<?php

namespace APIModule\Controller;

use App;
use MVC\Controller\Controller;
use Symfony\Component\HttpFoundation\JSONResponse;

/**
 * Description of APIController
 *
 * @author Ramón Serrano <ramon.calle.88@gmail.com>
 */
class APIController extends Controller
{

    /**
	 * Authenticate middleware
	 */
	public function authenticate() {
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
	    	$response['headers'][] = 'WWW-Authenticate: Basic realm="My Realm"';
	        return $app->json(
	        	array('error' => 'Not Authorized'),
	        	401,
	        	array('WWW-Authenticate' => 'Basic realm="My Realm"')
        	);
	    } else {
	    	return $app->json(array('response' => 'Authorized'));
	    }
    }
}