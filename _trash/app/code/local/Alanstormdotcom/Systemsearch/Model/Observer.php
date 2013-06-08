<?php
/**
* Open Source Initiative OSI - The MIT License (MIT):Licensing
* 
* The MIT License (MIT)
* Copyright (c) 2009 - 2011 Pulse Storm LLC
* 
* Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
* 
* The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
* 
* THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/
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