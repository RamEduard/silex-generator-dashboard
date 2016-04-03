<?php

namespace Application;

use MVC\Module\Module as BaseModule;
use Silex\Application;

/**
 * Description of Module
 *
 * @author Ramón Serrano <ramon.calle.88@gmail.com>
 */
abstract class MVCModule extends BaseModule
{
    
    /**
     * Register Templates Path Twig
     * 
     * @param Application $app
     */
    public function registerTemplatesPathTwig(Application $app)
    {
        $viewsPath = $this->getPath() . '/Resources/views';
        if (file_exists(dirname($viewsPath)) && file_exists($viewsPath)) {
            $app['twig.loader.filesystem']->addPath($viewsPath);
        }
    }
    
}
