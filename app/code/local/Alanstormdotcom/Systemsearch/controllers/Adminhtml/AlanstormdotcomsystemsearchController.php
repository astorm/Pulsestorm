<?php
class Alanstormdotcom_Systemsearch_Adminhtml_AlanstormdotcomsystemsearchController extends Mage_Adminhtml_Controller_Action {        	
	protected $_publicActions = array('index');
	public function indexAction()
	{
		$url = Mage::getModel('adminhtml/url')->getUrl('*/alanstormdotcomsystemsearch/searchForConfig');
		
		var_dump($url);
// 		var_d
		var_dump(Mage::app()->getLocale()->getLocaleCode());
	}
	
	public function searchForConfigAction()
	{
		
		$terms = $this->getRequest()->getParam('search_terms');
		
		$nodes = Mage::getModel('alanstormdotcomsystemsearch/configsearch')
		->searchSystemConfigForTerms($terms);		
		
		Mage::helper('alanstormdotcomsystemsearch/jsonconfigsearch')
		->prepareAndSendResponse($nodes, $terms);
	}	
}
	    