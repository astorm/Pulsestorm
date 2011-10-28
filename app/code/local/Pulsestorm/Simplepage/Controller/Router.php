<?php
class Pulsestorm_Simplepage_Controller_Router extends Mage_Core_Controller_Varien_Router_Standard
{

    public function initControllerRouters($observer)
    {
        /* @var $front Mage_Core_Controller_Varien_Front */
        $front = $observer->getEvent()->getFront();

        $front->addRouter('pulsestorm_simplepage', $this);
    }

    public function match(Zend_Controller_Request_Http $request)
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
        
        $view_name = Mage::getSingleton('pulsestorm_simplepage/handler')->init($request)
        ->getViewName();

        if($view_name)
        {
            $request->setDispatched(true);
            $controller_instance->dispatch($action);
            return true;
        }        
        return false;
//         var_dump($controller);
//         var_dump($controller_name);
//         var_dump($action);
//         var_dump($controller_instance);
//         throw new Exception("Trying to match");
    }
    
}
