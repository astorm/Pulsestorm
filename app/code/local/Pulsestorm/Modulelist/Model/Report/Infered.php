<?php
class Pulsestorm_Modulelist_Model_Report_Infered extends Varien_Object
{
	protected $_lists 				= array();	
	protected $_inferedButMissing 	= array();
	protected $_conflictingFolders 	= array();
	public function gather()
	{		
		$modules = $this->_getModuleFolders();	
		$this->_setListsFromModulePaths($modules);
		$this->_gatherInferedButMissing();
		$this->_gatherConflictingPackages();
		return $this;
	}

	public function getInferedConflicts()
	{
		return $this->_conflictingFolders;
	}
	
	protected function _gatherConflictingPackages()
	{
		$modules = $this->_getModuleFolders();	
		$items = array();
		foreach($modules as $path)
		{
			$parts = explode(DS, $path);
			$small_module 	= array_pop($parts);
			$package 		= array_pop($parts);
			$identifier = $package . DS . $small_module;
			if(array_key_exists($identifier, $items))
			{
				$items[$identifier]->count++;
				$items[$identifier]->paths[] = $path;
			}
			else
			{
				$o = new stdClass();
				$o->count 	= 1;
				$o->paths 	= array();
				$o->paths[] = $path;
				$items[$identifier]= $o;
			}
		}
		
		foreach($items as $key=>$report)
		{
			if($report->count > 1)
			{
				$o 					= Mage::getModel('pulsestorm_modulelist/inferredconflict');
				$o->setModuleName(str_replace('/','_',$key));
				$o->setPaths($report->paths);
				$this->_conflictingFolders[] = $o;
			}
		}
	}
	
	protected function _gatherInferedButMissing()
	{
		$this->_inferedButMissing = array();
		foreach($this->_lists as $pool=>$items)
		{			
			foreach($items as $item)
			{
				if(!$item->getIsConfigured())
				{
					$this->_inferedButMissing[] = $item;		
				}
			}
		}			
	}
	
	public function getInferedButMissing()
	{
		return $this->_inferedButMissing;
	}
	protected function _setListsFromModulePaths($modules)
	{
		$config = $this->_getConfig();
		foreach($modules as $path)
		{
			$o = Mage::getModel('pulsestorm_modulelist/inferedmodule');							
			$o->setFromFolder($path);
			
			$items = explode(DS,$path);			
			$module_small 	= array_pop($items);
			$package 		= array_pop($items);
			$o->setModuleName($package . '_' . $module_small);
			
			$pool			= array_pop($items);
			$o->setCodePool($pool);
			
			$o->setIsConfigured((boolean) $config->getNode('modules/' . $o->getModuleName()) );
			if(!array_key_exists($o->getCodePool(), $this->_lists))
			{
				$this->_lists[$o->getCodePool()] = array();
			}
			$this->_lists[$o->getCodePool()][] = $o;			
		}
	
	}
	
	protected function _getModuleFolders()
	{
		$pools		= array('core','community','local');
		$config 	= $this->_getConfig();		
		$codePath 	= $config->getOptions()->getCodeDir();	
		$modules	= array();
		foreach($pools as $pool)
		{			
			$packages = $this->_getPackageFoldersFromPool($pool);				
			foreach($packages as $folder)
			{
				$modules  = array_merge($modules, $this->_getModuleFoldersFromPackageFolder($folder));
			}			
		}
		return $modules;
	}
	
	protected function _getModuleFoldersFromPackageFolder($folder)
	{
		$files = glob($folder . DS . '*');
		$final = array();
		foreach($files as $file)
		{
			if(is_dir($file))
			{
				$final[] = $file;
			}		
		}
		return $final;
	}
	
	protected function _getPackageFoldersFromPool($pool)
	{
		$config = $this->_getConfig();
		$codePath = $config->getOptions()->getCodeDir();
		$poolPath = $codePath . DS . $pool;
		
		$files = glob($poolPath . DS . '*');
		$final = array();
		foreach($files as $file)
		{
			if(is_dir($file))
			{
				$final[] = $file;
			}
		}
		return $final;
	}
	
	protected function _getConfig()
	{
		return Mage::getConfig();
	}	
}