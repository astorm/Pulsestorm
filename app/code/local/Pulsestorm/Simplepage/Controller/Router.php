<?php
class Pulsestorm_Simplepage_Controller_Router extends Mage_Core_Controller_Varien_Router_Standard
{

    /**
    * Event Observer, adds self as a router, allowing all this magic to take place
    */
    public function initControllerRouters($observer)
    {
        /* @var $front Mage_Core_Controller_Varien_Front */
        $front = $observer->getEvent()->getFront();

        $front->addRouter('pulsestorm_simplepage', $this);
    }

    /**
    * Gets us to Pulsestorm_Simplepage::IndexController->indexAction()
    *
    * Original designs were going to route to a standard Magento controller
    * until the django style pattern matching was decided.  The serves mainly
    * to ensure that the module has been installed correctly, and the jigger
    * the request object the same as default Magento routing would, while still
    * bypassing that routing system.
    */
    protected function _matchStandardController($request)
    {
        $path = trim($request->getPathInfo(), '/');
        if(!$path)
        {
            return false;
        }
        
        $parts = explode('/',$path);
        
        //         $controller = array_shift($parts);
        //         $action     = array_shift($parts);
        //         $action     = $action ? $action : 'index';
        //         $params     = $parts;

        $controller      = 'index';
        $action          = 'index';
        
        $controller_name = $this->_validateControllerClassName('Pulsestorm_Simplepage',$controller);
        if(!$controller_name)
        {
            return false;
        }
        
        $controller_instance = Mage::getControllerInstance($controller_name, $request, $this->getFront()->getResponse());
        if(!$controller_instance)
        {
            return false;
        }
        
        $request->setModuleName('pulsestorm_simplepage');
        $request->setRouteName('pulsestorm_simplepage');
        $request->setControllerName($controller);
        $request->setActionName($action);
        $request->setControllerModule('Pulsestorm_Simplepage');
        
        for ($i = 3, $l = sizeof($parts); $i < $l; $i += 2) {
            $request->setParam($parts[$i], isset($parts[$i+1]) ? urldecode($parts[$i+1]) : '');
        }
        
        return array($controller_instance,$action);
    }
        
    public function match(Zend_Controller_Request_Http $request)
    {   
        //old school routing to get to our index controller
        list($controller_instance, $action)    = $this->_matchStandardController($request);
        if(!$controller_instance)
        {
            return false;
        }
        
        //actual routing logic that checks our URLs
        $view_name = Mage::getSingleton('pulsestorm_simplepage/handler')->init($request)
        ->getViewName();

        if($view_name)
        {
            $request->setDispatched(true);
            $controller_instance->dispatch($action);
            return true;
        }        
        
        return false;
    }
    
}
