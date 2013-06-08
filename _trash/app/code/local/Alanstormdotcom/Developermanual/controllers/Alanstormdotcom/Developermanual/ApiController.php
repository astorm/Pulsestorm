<?php
class Alanstormdotcom_Developermanual_Alanstormdotcom_Developermanual_ApiController extends Mage_Adminhtml_Controller_Action
{
    protected function _initCssBlock()
    {
        $styles = $this->getLayout()->createBlock('alanstormdotcom_developermanual/template')->setTemplate('styles.phtml');			
        $this->_addJs($styles);		
    }
    
    protected function _initSingleTemplateBlock()
    {        
        $this->_initCssBlock();			
        $this->_addContent(
            $this->getLayout()->createBlock('alanstormdotcom_developermanual/template')
            ->setTemplate($this->getFullActionName().'.phtml')
        );		
    }

    public function referenceAction()
    {
        $this->loadLayout();        
        $this->_initSingleTemplateBlock();  
        $this->renderLayout();
    }

    public function referenceAllAction()
    {
        $this->loadLayout();        
        $this->_initSingleTemplateBlock();  
        $this->renderLayout();
    }    
    
    public function singleResourceAction()
    {
        $api        = Mage::getSingleton('alanstormdotcom_developermanual/apiref');
        $resource   = $api->getResourceByName($this->getRequest()->getParam('resource'));
        
        $block      = $this->getLayout()->createBlock('alanstormdotcom_developermanual/apiresource');
        $block->setResource($resource)
        ->setShowMethod(true);
        $this->getResponse()->setBody($block->toHtml());
    }
}