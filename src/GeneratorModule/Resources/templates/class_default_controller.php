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
    
}