<?php

namespace AngularJSModule\Controller;

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
class DefaultController 
{

	/**
     * Index action
     * 
     * @param App $app
     * @return string
     */
    function index(App $app)
    {
        return $app['twig']->render('default/index.twig', array());
    }
    
    /**
     * Basic auth action
     * 
     * @param App $app
     * @return JsonResponse
     */
    function basicAuth(App $app)
    {
        $valid_passwords = array (
            "admin@example.com" => "admin",
            "user@example.com"  => "user"
        );
        $valid_users = array_keys($valid_passwords);

        $email = isset($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER'] : '';
        $pass = isset($_SERVER['PHP_AUTH_PW']) ? $_SERVER['PHP_AUTH_PW'] : '';

        $validated = (in_array($email, $valid_users)) && ($pass == $valid_passwords[$email]);

        if (!$validated) {
            $response = new JsonResponse(array('error' => 'Not Authorized'), 401);
        } else {
            $response = new JsonResponse(array(
                'response' => 'Authorized',
                'user'     => array(
                    'email' => $email,
                    'pass'  => $pass
                )
            ));
        }
        
        return $response;
    }

    /**
     * Form auth action
     * 
     * @param App $app
     * @return JsonResponse
     */
    function formAuth(App $app)
    {
        $valid_passwords = array (
            "admin@example.com" => "admin",
            "user@example.com"  => "user"
        );
        $valid_users = array_keys($valid_passwords);

        $email = $app['request']->request->get('email');
        $pass = $app['request']->request->get('password');

        $validated = (in_array($email, $valid_users)) && ($pass == $valid_passwords[$email]);

        if (!$validated) {
            $response = new JsonResponse(array('error' => 'Not Authorized'), 401);
        } else {
            $response = new JsonResponse(array(
                'response' => 'Authorized',
                'user'     => array(
                    'email' => $email,
                    'pass'  => $pass
                )
            ));
        }
        
        return $response;
    }

    /**
     * Sign Up action
     * 
     * @param App $app
     * @return JsonResponse
     */
    function signUp(App $app)
    {
        $firstName   = $app['request']->request->get('firstName');
        $lastName    = $app['request']->request->get('lastName');
        $displayName = $app['request']->request->get('displayName');
        $email       = $app['request']->request->get('email');
        $password    = $app['request']->request->get('password');
        $agree       = $app['request']->request->get('agree');
        
        return new JsonResponse(array(
            'inserted' => true,
            'user'     => array(
                'firstName'   => $firstName,
                'lastName'    => $lastName,
                'displayName' => $displayName,
                'email'       => $email,
                'password'    => $password, 
                'agree'       => $agree
            )
        ));
    }
}