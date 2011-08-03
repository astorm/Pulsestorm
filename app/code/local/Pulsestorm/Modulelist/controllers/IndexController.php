<?php

class Pulsestorm_Modulelist_IndexController extends Mage_Adminhtml_Controller_Action
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
        $this->getLayout()->getBlock('head')->addLinkRel('stylesheet',$this->getUrl('pulsestorm_modulelist/css/main'));
        $this->getLayout()->getBlock('content')->append($this->_initListBlock());
        $this->renderLayout();
    }
}