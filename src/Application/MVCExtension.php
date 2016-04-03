<?php

namespace Application;

use MVC\Injection\Extension as BaseExtension;

/**
 * Description of MVCExtension
 *
 * @author Ramón Serrano <ramon.calle.88@gmail.com>
 */
class MVCExtension extends BaseExtension
{

    /**
     * Load routes of the Module
     * 
     * @return array
     */
    public function loadRoutes()
    {
        $routesFiles = array();
        
        if (file_exists($routesFolder = $this->configDir . '/routes')) {
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
        
        return $routesFiles;
    }

}
