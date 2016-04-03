<?php

namespace Application;

use Silex\Application as BaseApplication;
use Application\MVCModule;
use Silex\ServiceProviderInterface;

/**
 * Description of Application
 *
 * @author Ramón Serrano <ramon.calle.88@gmail.com>
 */
class SilexApplication extends BaseApplication
{
    
    /**
     * Default Array Options
     * 
     * @var array
     */
    static $defaultOptions = array(
        'debug' => false
    );
    
    /**
     * Array Options
     * 
     * @var array
     */
    private $options;
    
    /**
     * Array Application Modules
     * 
     * @var MVCModule[]
     */
    private $modules;
    
    function __construct(array $options = array())
    {
        parent::__construct();
        $this->setOptions($options)
             ->initProviders()
             ->initRoutes();
    }
    
    /**
     * Set Options
     * 
     * @param array $options
     * @return \App
     */
    public function setOptions(array $options)
    {
        foreach ($options as $name => $option) {
            $this[$name] = $option;
        }
        
        $this->options = array_merge($options, self::$defaultOptions);
        
        return $this;
    }
    
    /**
     * Initialize application modules
     * 
     * @return Application
     */
    protected function initModules()
    {
        foreach ($this->setModules() as $module) {
            $this->addModule($module);
        }
        
        return $this;
    }
    
    /**
     * Set Application Modules
     * 
     * @return MVCModule[]
     */
    public function setModules()
    {
        return array();
    }
    
    /**
     * Add Module
     * 
     * @param MVCModule $module
     * @return Application
     * @throws \LogicException
     */
    protected function addModule(MVCModule $module)
    {
        $name = $module->getName();
        if (isset($this->modules[$name])) {
            throw new \LogicException(sprintf('Trying to register two modules with the same name "%s"', $name));
        }
        $this->modules[$name] = $module;
        
        return $this;
    }
    
    /**
     * Initialize application providers
     * 
     * @return Application
     */
    protected function initProviders()
    {
        foreach ($this->setProviders() as $name => $provider) {
            if (!is_array($provider)) {
                throw new \InvalidArgumentException('Provider must be Array.');
            } else if (count($provider) == 2 && (is_object($provider[0]) && is_array($provider[1]))) {
                if ('doctrine_dev' == $name && $this->options['debug']) {
                    $this->addProvider($provider[0], $provider[1]);
                } else if ('doctrine_prod' == $name && !$this->options['debug']) {
                    $this->addProvider($provider[0], $provider[1]);
                } else {
                    $this->addProvider($provider[0], $provider[1]);
                }
            } else if (count($provider) && is_object($provider[0])) {
                $this->addProvider($provider[0]);
            }
        }
        
        return $this;
    }
    
    /**
     * Set Application Silex Providers
     * 
     * @return ServiceProviderInterface[]
     */
    function setProviders()
    {
        return array();
    }
    
    /**
     * Add Service Provider
     * 
     * @param ServiceProviderInterface $provider
     * @param array $optionsValues
     * @return Application
     */
    protected function addProvider(ServiceProviderInterface $provider, array $optionsValues = array())
    {
        $this->register($provider, $optionsValues);
        
        return $this;
    }
    
    /**
     * Initialize Application Routes
     * 
     * @return Application
     */
    protected function initRoutes()
    {
        # Local var required
        $app = $this;
        
        foreach ($this->setRoutes() as $routeFile) {
            if (file_exists($routeFile))
                require_once $routeFile;
        }
        
        return $this;
    }
    
    /**
     * Set Routes files
     * 
     * @return array
     */
    protected function setRoutes()
    {
        return array();
    }
    
    /**
     * Get Application Option
     * 
     * @param string $key
     * @return mixed|null
     */
    public function getOption($key)
    {
        return (array_key_exists($key, $this->options)) ? $this->options[$key] : null;
    }
    
    /**
     * Get Application Options
     * 
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }
    
    /**
     * Get Provider 
     * 
     * @param string $key
     * @return ServiceProviderInterface
     */
    public function getProvider($key)
    {
        return $this[$key];
    }
    
    /**
     * Custom Application Run
     */
    function run() 
    {
        $app = $this;
        
        $this->before(function() use($app) {
            foreach ($app->setModules() as $module) {
                $module->registerTemplatesPathTwig($app);
            }
        });
        
        parent::run();
    }
    
}

