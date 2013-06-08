<?php
class Pulsestorm_Modulelist_Pulsestorm_ModulelistController extends Mage_Adminhtml_Controller_Action
{
	protected function _initReport()
	{
		$m = Mage::getSingleton('pulsestorm_modulelist/report');
		return $m;
	}
	
	protected function _initReportInfered()
	{
		$m = Mage::getSingleton('pulsestorm_modulelist/report_infered');
		return $m;	
	}
	
	protected function _initListBlock()
	{
		$block = $this->getLayout()
		->createBlock('pulsestorm_modulelist/top')
		->setTemplate('top.phtml');		
		
		$block->setReport($this->_initReport()->gather());
		$block->setReportInfered($this->_initReportInfered()->gather());
		return $block;
	}
    public function indexAction()
    {
        $this->loadLayout();
        $this->getLayout()->getBlock('head')->addLinkRel('stylesheet',$this->getUrl('adminhtml/pulsestorm_modulelist/css'));
        $this->getLayout()->getBlock('content')->append($this->_initListBlock());
        $this->renderLayout();
    }
    
    public function cssAction()
    {
    	header('Content-Type: text/css');
    	include(Mage::app()->getConfig()->getModuleDir('','Pulsestorm_Modulelist') . '/css/main.css');
    	exit;
    }
    
}