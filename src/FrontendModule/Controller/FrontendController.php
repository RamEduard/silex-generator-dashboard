<?php

namespace FrontendModule\Controller;

use App;

/**
 * Description of FrontendController
 *
 * @author Ramón Serrano <ramon.calle.88@gmail.com>
 */
class FrontendController 
{

    /**
     * About action page
     * 
     * @param App $app
     * @return string
     */
    function about(App $app)
    {
        return $app['twig']->render('frontend/about.html.twig');
    }
    
    /**
     * Home action page
     * 
     * @param App $app
     * @return string
     */
    function home(App $app)
    {
        return $app['twig']->render('frontend/home.html.twig');
    }
    
}
