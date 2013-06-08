<?php
class Alanstormdotcom_Developermanual_Alanstormdotcom_Developermanual_IndexController extends Mage_Adminhtml_Controller_Action
{
    public function indexAction()
    {
        $this->loadLayout();
        $this->renderLayout();		
    }

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
    
    public function aboutAction()
    {
        $this->loadLayout();			
        $this->_initSingleTemplateBlock();
        $this->renderLayout();				
    }
    
    public function blockLayoutactionsReferenceAction()
    {
        $this->loadLayout();			
        $this->_initCssBlock()			;
        
        $block = $this->getLayout()->createBlock('alanstormdotcom_developermanual/template')
        ->setTemplate('form_block_action.phtml');
        
        $this->_addContent($block);
        $this->renderLayout();				
    }
    
    public function blockLayoutactionsReferenceAjaxAction()
    {
        $params = $this->getRequest()->getParams();
        $alias = $params['alias'];
        
        $info = Mage::helper('alanstormdotcom_developermanual/reflector')
        ->getActionInformation($alias);			
        
        $this->loadLayout();
        
        $results = $this->getLayout()
        ->createBlock('alanstormdotcom_developermanual/renderer_reflection_action')
        ->setEscapeXml(true)
        ->setInfo($info)
        ->setAlias($alias);			
        
        //$this->getLayout()->getBlock('content')->insert($results);
        
        echo $results->toHtml();
        // $this->renderLayout();		
    }	
}