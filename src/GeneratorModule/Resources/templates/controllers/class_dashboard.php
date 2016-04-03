<?php

namespace __MODULE__\Controller;

use App;

/**
 * Description of DashboardController
 *
 * @author Ramón Serrano <ramon.calle.88@gmail.com>
 */
class DashboardController 
{
    
    /**
     * Dashboard action page
     * 
     * @param App $app
     * @return string
     */
    function dashboard(App $app)
    {
        return $app['twig']->render('dashboard/dashboard.twig', array());
    }
    
}
