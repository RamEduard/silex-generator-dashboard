<?php

use Application\MVCExtension;
use Application\MVCModule;
use Application\SilexApplication;
use Silex\ServiceProviderInterface;

/**
 * Description of App
 *
 * @author Ramón Serrano <ramon.calle.88@gmail.com>
 */
class App extends SilexApplication
{

    /**
     * Get Application Dir
     * 
     * @return string Application Dir
     */
    public function getAppDir()
    {
        $r = new \ReflectionObject($this);
        return str_replace('\\', '/', dirname($r->getFileName()));
    }
    
    /**
     * Set Application Modules
     * 
     * @return MVCModule[]
     */
    public function setModules() 
    {
        return array(
            new ConsoleSymfonyCommandsModule\ConsoleSymfonyCommandsModule(),
            //new DashboardModule\DashboardModule(),
            new GeneratorModule\GeneratorModule(),
            new FrontendModule\FrontendModule(),
            new AngularJSModule\AngularJSModule(),
            // new APIModule\APIModule()
        );
    }
    
    /**
     * Register the providers
     * 
     * @return ServiceProviderInterface[]
     */
    public function setProviders()
    {
        # Local var required
        $app = $this;
        
        if (file_exists($providersFile = $this->getAppDir() . '/config/providers.php')) {
            $providers = require_once $providersFile;
        } else {
            $providers = array();
        }
        
        return $providers;
    }
    
    /**
     * Register the routes
     * 
     * @return \App
     */
    public function setRoutes()
    {
        $routesFiles = array();
        
        if (file_exists($routesFolder = $this->getAppDir() . '/config/routes')) {
            $fsi = new \FilesystemIterator($routesFolder);
            
            while ($fsi->valid()) {
                if ($fsi->isDir()) {
                    $ffsi = new \FilesystemIterator($routesFolder . '/' . $fsi->getFilename());
                    while ($ffsi->valid()) {
                        if (preg_match('/[a-zA-Z0-9_]+\.php/i', $ffsi->getFilename())) {
                            $routesFiles[] = $routesFolder . '/' . $fsi->getFilename() . '/' . $ffsi->getFilename();
                        }
                        $ffsi->next();
                    }
                } else {
                    if (preg_match('/[a-zA-Z0-9_]+\.php/i', $fsi->getFilename())) {
                        $routesFiles[] = $routesFolder . '/' . $fsi->getFilename();
                    }
                }
                $fsi->next();
            }
        }
        
        foreach ($this->setModules() as $module) {
            $extension = $module->getModuleExtension();
            if (is_object($extension) && $extension instanceof MVCExtension) {
                foreach ($extension->loadRoutes() as $routeModule) {
                    $routesFiles[] = $routeModule;
                }
            }
        }
        
        return $routesFiles;
    }

}
