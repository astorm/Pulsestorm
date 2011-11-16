<?php
class Alanstormdotcom_Developermanual_Model_Helper extends Mage_Core_Model_Abstract
{
	protected $_configHelpers;
	
	public function getClassinfo($filePath, $classAlias)
	{
		$classInfo = array();
		
		$classInfo['filePath'] = $filePath;
		$classInfo['fileParts'] = $this->_getPartsFromPath($filePath);
		$groupName = $this->_getGroupName($classInfo['fileParts']['classPath'], $classInfo['fileParts']['module']);
		$classInfo['groupName'] = $groupName;
		$classInfo['alias'] = $classAlias;
		$classInfo['rewrite'] = $this->_getRewrite($groupName, $classAlias, $classInfo['fileParts']['className']);
		
		$reflector = Mage::getModel('alanstormdotcom_developermanual/reflector_helper', array($filePath,
																							  $classInfo['fileParts']['className']));
		
		$classInfo['parents'] = $reflector->getParents();
		$classInfo['docComment'] = $reflector->getDocComment();
		$classInfo['methods'] = $reflector->getMethods($classInfo['parents']);
		$classInfo['properties'] = $reflector->getProperties($classInfo['parents']);
		$classInfo['constants'] = $reflector->getConstants();
		
		return $classInfo;
	}
	
	protected function _getPartsFromPath($path)
	{
		$parts = explode('/', $path);
		$return = array();
		$return['codepool'] = $parts[2];
		$return['namespace'] = $parts[3];
		$return['module'] = $parts[4];
		$return['className'] = str_replace('.php', '', implode('_', array_slice($parts, 3)));
		$return['classPath'] = $parts[3] . '_' . $parts[4]  . '_' . $parts[5];
		
		return $return;
	}
	
	protected function _getGroupName($classpath, $module)
	{
		$helpers = $this->_getConfigHelpers();
		$groups = array();
		$classes = array();
		
		foreach($helpers as $group => $helper) {
			$groups[] = $group;
			if($helper->class) {
				$classes[] = $helper->class;
			} else {
				$classes[] = null;
			}
		}
		
		if(($key = array_search($classpath, $classes))) {
			return $groups[$key];
			//return $key;
		} else {
			return strtolower($module);
		}
	}
	
	protected function _getRewrite($groupName, $classAlias, $className)
	{
		$helpers = $this->_getConfigHelpers();
		
		if(isset($helpers->{$groupName}->rewrite->{$classAlias})) {
			if($className == (string) $helpers->{$groupName}->rewrite->{$classAlias}) {
				if(isset($helpers->{$groupName}->class)) {
					return array('for' => (string) $helpers->{$groupName}->class);
				} else {
					$return = array('for' => str_replace(' ', '_', ucwords('mage ' . $groupName . ' helper ' . $classAlias)));
				}
			} else {
				$return = array('by' => (string) $helpers->{$groupName}->rewrite->{$classAlias});
			}
			return $return;
		} else {
			return array();
		}
	}
	
	protected function _getConfigHelpers()
	{
		if(! $this->_configHelpers) {
			$this->_configHelpers = new SimpleXMLElement(Mage::getConfig()->getNode('global/helpers')->asXML());
		}
		
		return $this->_configHelpers;
	}
}