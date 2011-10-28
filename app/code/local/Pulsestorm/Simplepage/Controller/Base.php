<?php
class Pulsestorm_Simplepage_Controller_Base extends Mage_Core_Controller_Front_Action
{
    public function preDispatch()
    {
        $r = parent::preDispatch();
        $this->loadLayout();
        
        $simplepage_viewhandler = Mage::getSingleton('pulsestorm_simplepage/handler');
        
        $path_views = Mage::getModuleDir('',$simplepage_viewhandler->getModule()) . 
        DS . 'views/simplepage.php';
        
        @include($path_views);
        
        $block = $this->getLayout()->createBlock($simplepage_viewhandler->getBlockClass(),'');
        
        $block = call_user_func($simplepage_viewhandler->getViewName(),
            $simplepage_viewhandler->getParams(),
            $block,            
            $this->getLayout(),            
            $this->getRequest(), 
            $this->getResponse()
        );
        
        $this->getLayout()->getBlock('content')->append($block);        
        return $r;
    }
    
    public function postDispatch()
    {
        $this->renderLayout();
    }    
}