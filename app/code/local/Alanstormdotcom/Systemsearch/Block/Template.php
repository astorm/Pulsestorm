<?php

	class Alanstormdotcom_Systemsearch_Block_Template extends Mage_Core_Block_Template
	{
	
        public function fetchView($fileName)
        {
            //ignores file name, just uses a simple include with template name
            $this->setScriptPath(dirname(__FILE__) . '/../phtml');
            return parent::fetchView($this->getTemplate());
        }
	
	}