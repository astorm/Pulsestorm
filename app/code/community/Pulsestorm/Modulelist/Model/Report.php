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
class Pulsestorm_Modulelist_Model_Report extends Varien_Object
{
	protected $_lists		= array();
	
	public function gather()
	{		
		$config = Mage::getConfig();
		foreach($config->getNode('modules')->children() as $item)
		{
			$o = Mage::getModel('pulsestorm_modulelist/configuredmodule');		
			$o->setName($item->getName());
			$o->setActive((string)$item->active);
			$o->setCodePool((string)$item->codePool);
			
			//use same logic from Mage_Core_Model_Config::getModuleDir
			//but recreated here to allow for poorly configued modules
			$codePool 	= $config->getModuleConfig($item->getName())->codePool;
			$dir		= $config->getOptions()->getCodeDir().DS.$codePool.DS.uc_words($item->getName(),DS);			
			$o->setPath($dir);			
			
			$exists = file_exists($o->getPath());
			$exists = $exists ? 'yes' : 'no';
			$o->setPathExists($exists);
			
			$exists = file_exists($o->getPath() . '/etc/config.xml');
			$exists = $exists ? 'yes' : 'no';
			$o->setConfigExists($exists);			
			$o->setModuleVersion('?');
			if($exists == 'yes')
			{
			    $xml = simplexml_load_file($o->getPath() . '/etc/config.xml');
			    $modules = $xml->modules;
			    if(!$modules){ continue; }
			    
			    $name = $modules->{$item->getName()};
			    if(!$name){ continue; }
			    
			    $version = $name->version;
			    if(!$version) { 
			        $version = '?';
			    }
			    
			    $version = (string) $version;
			    $o->setModuleVersion($version);
			}
			
			
			if(!array_key_exists($o->getCodePool(), $this->_lists))
			{
				$this->_lists[$o->getCodePool()] = array();
			}
			$this->_lists[$o->getCodePool()][] = $o;
		}
		
		return $this;
	}
	
	public function getCoreList()
	{
		return $this->_getList('core');
	}

	public function getLocalList()
	{
		return $this->_getList('local');
	}
	
	public function getCommunityList()
	{
		return $this->_getList('community');
	}
	
	protected function _getList($type)
	{
		return $this->_lists[$type];
	}	
	
	public function getUnknownCodePools()
	{
		$known = array('core','local','community');
		$pools = array_keys($this->_lists);		
		$final = array();
		foreach($pools as $item)
		{
			if(!in_array($item,$known))
			{
				$final[] = $item;
			}
		}
		return $final;
	}
}