<?php

namespace __MODULE__\Controller;

use App;
use Symfony\Component\HttpFoundation\Request;

/**
 * Description of AuthController
 *
 * @author Ramón Serrano <ramon.calle.88@gmail.com>
 */
class AuthController 
{

    /**
     * Login action page
     * 
     * @param App $app
     * @return string
     */
    function login(Request $request, App $app)
    {
        $error_msg = "";
        if ($app['security.last_error']($request) == "Bad credentials") {
            $error_msg = "Usuario o contraseña incorrectos.";
        }
        return $app['twig']->render('auth/login.html.twig', array(
            'error'         => $error_msg,
            'last_username' => $app['session']->get('_security.last_username'),
    	));
    }
    
}
