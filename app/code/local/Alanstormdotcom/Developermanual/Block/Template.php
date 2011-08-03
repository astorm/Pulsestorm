<?php
	class Alanstormdotcom_Developermanual_Block_Template extends Mage_Core_Block_Template
	{
		public function fetchView($fileName)
		{
			//ignores file name, just uses a simple include with template name
			$path = Mage::getModuleDir('', 'Alanstormdotcom_Developermanual');
			$this->setScriptPath($path . '/templates');
			return parent::fetchView($this->getTemplate());
		}
	}