<?php
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
	
	