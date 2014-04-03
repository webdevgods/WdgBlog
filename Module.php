<?php
namespace WdgBlog;

use Zend\Mvc\ModuleRouteListener,
    Zend\Mvc\MvcEvent,
    Zend\ModuleManager\Feature\ServiceProviderInterface;

class Module implements ServiceProviderInterface
{
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        
        $moduleRouteListener->attach($eventManager);
    }

    public function getConfig()
    {
        $config         = array();
        $configFiles    = array(
            'module.config.php',
            'routes.config.php',
        );
        
        foreach ($configFiles as $configFile) 
        {
            $config = \Zend\Stdlib\ArrayUtils::merge($config, include __DIR__ . '/config/' . $configFile);
        }

        return $config;
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
    
    /**
     * {@InheritDoc}
     */
    public function getControllerConfig() 
    {
        return include __DIR__ . '/config/controller.config.php';
    }
    
    public function getServiceConfig() 
    {
        return include __DIR__ . '/config/services.config.php';
    }
    
    /**
    * {@InheritDoc}
    */
    public function getFormElementConfig()
    {
        return include __DIR__ . '/config/form-elements.config.php';
    }
}

