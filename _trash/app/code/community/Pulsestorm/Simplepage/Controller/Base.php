<?php
/**
* Contains most of our Simple Page rendering logic.
*/
class Pulsestorm_Simplepage_Controller_Base extends Mage_Core_Controller_Front_Action
{
    /**
    * The preDispatch calls the normal preDispatch method, and then checks
    * the handler to see if it found a URL match in simplepage.xml.  If so,
    * is loads the appropriate views.php file, instantiates our default block,
    * and calls the view function.  Then, the block returned from the view is 
    * added to the layout's content. 
    */
    public function preDispatch()
    {
        $r = parent::preDispatch();
        $this->loadLayout();
        
        $simplepage_viewhandler = Mage::getSingleton('pulsestorm_simplepage/handler');
        
        $path_views = Mage::getModuleDir('',$simplepage_viewhandler->getModule()) . 
        DS . 'views/simplepage.php';
        
        $include_results = include($path_views);
        
        $block = $this->getLayout()->createBlock($simplepage_viewhandler->getBlockClass(),'');
        $block->setParams($simplepage_viewhandler->getParams());
        $block = call_user_func($simplepage_viewhandler->getViewName(),
            $block,            
            $this->getLayout(),            
            $this->getRequest(), 
            $this->getResponse()
        );
        
        $this->getLayout()->getBlock('content')->append($block);        
        return $r;
    }

    /**
    * Renders the loaded layout
    */    
    public function postDispatch()
    {
        $this->renderLayout();
    }    

    /**
    * Adds an additional layout handle based on view name, since
    * full action handle will always be the same.
    */    
    public function addActionLayoutHandles()
    {
        $r = parent::addActionLayoutHandles();
        $update = $this->getLayout()->getUpdate();
        $update->addHandle(strtolower($this->getHandlerActionHandle()));        
        return $r;
    }    
    
    /**
    * Asks the handler what to use for our layuot handle, and reveals that maybe
    * calling the handler a handler wasn't the best choice, as it doesn't have
    * much to do with layout handles. 
    */    
    public function getHandlerActionHandle()
    {
        return Mage::getSingleton('pulsestorm_simplepage/handler')->getLayoutHandleName();
    }
}