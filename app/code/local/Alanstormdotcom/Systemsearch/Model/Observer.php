<?php
	class Alanstormdotcom_Systemsearch_Model_Observer
	{
		public function addSystemsearchInit($observer)
		{
			if(!Mage::helper('alanstormdotcomsystemsearch')->isModuleOutputEnabled())
			{
				return;
			}
			
			$this->addFormToSystemConfigPage($observer);
		}

		//questionable way to inject things into the 
		//document, but anything to avoid Layout		
		protected function addFormToSystemConfigPage($observer)
		{
			$response = $observer->getResponse();
			$block = $this->getLayout()->createBlock('alanstormdotcomsystemsearch/template')
			->setTemplate('prototype.phtml')->toHtml();			
			$response->setBody(
				str_replace('</body>',$block.'</body>',$response->getBody(false))
			);		

			if($this->isSystemConfigPage() && stripos($response->getBody(false),'<html') !== false)
			{
				$block = $this->getLayout()->createBlock('alanstormdotcomsystemsearch/template')
				->setTemplate('searchform.phtml')->toHtml();			
				$response->setBody(
					str_replace('<div id="page:main-container">','<div id="page:main-container">'.$block,$response->getBody(false))
				);		
        	}		
		}
		
		protected function isSystemConfigPage()
		{
			$parts = explode('/',$_SERVER['REQUEST_URI']);			
			return in_array('system_config',$parts);
			return true;
		}
		
		private function getLayout()
		{
			return Mage::getSingleton('core/layout');;
		}
		

	}