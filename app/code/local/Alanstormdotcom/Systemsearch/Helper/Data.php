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
	class Alanstormdotcom_Systemsearch_Helper_Data extends Mage_Core_Helper_Abstract
	{	
		public function reverseTranslate($word)
		{
			$translated = $this->__($word);
			$reverse_legend = array_flip(Mage::app()->getTranslator()->getData());			
			if(array_key_exists($translated, $reverse_legend))
			{
				return $reverse_legend[$translated];
			}
			return $word;
		}	
		
		public function isModuleOutputEnabled($moduleName = null)
		{
			if(is_callable(array(Mage::helper('core'),'isModuleOutputEnabled')))
			{
				return parent::isModuleOutputEnabled();
			}
			
			if ($moduleName === null) {
				$moduleName = $this->_getModuleName();
			}
			if (Mage::getStoreConfigFlag('advanced/modules_disable_output/' . $moduleName)) {
				return false;
			}
			return true;
		}		
	}
	
	