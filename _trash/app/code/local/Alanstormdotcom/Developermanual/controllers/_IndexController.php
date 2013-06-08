<?php
throw new Exception("Controller Moved to admin namespace, please update your references.");
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
	class Alanstormdotcom_Developermanual_IndexController extends Mage_Adminhtml_Controller_Action
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