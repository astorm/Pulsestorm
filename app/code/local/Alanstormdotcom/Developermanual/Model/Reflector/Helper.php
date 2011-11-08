<?php
class Alanstormdotcom_Developermanual_Model_Reflector_Helper extends Mage_Core_Model_Abstract
{
	protected $_reflector;
	protected $_className;
	
	public function __construct(array $args)
	{
		if( sizeof($args) != 2) {
			throw new Exception('Wrong parameter count in ' . ___METHOD__);
		}
		
		$path = $args[0];
		$this->_className = $args[1];
		
		require_once($path);
		$this->_reflector = new ReflectionClass($this->_className);
	}
	
	public function getParents()
	{
		$parents = array();
		$class = $this->_reflector;
		
		while($class = $class->getParentClass()) {
			$parents[] = $class->getName();
		}

		return $parents;
	}
	
	public function getMethods(array $parents)
	{
		$return = array($this->_className => array());
		foreach($parents as $parent) {
			$return[$parent] = array();
		}
			
		$methods = $this->_reflector->getMethods(ReflectionMethod::IS_STATIC | ReflectionMethod::IS_PUBLIC |
												 ReflectionMethod::IS_PROTECTED | ReflectionMethod::IS_FINAL);
		foreach($methods as $method) {
			$line = array();
			$line['name'] = $method->name;
			$line['modifiers'] = Reflection::getModifierNames($method->getModifiers());
			$line['parameters'] = $this->_getParameters($method);
			$line['docComment'] = $method->getDocComment();
			$return[$method->getDeclaringClass()->getName()][] = $line;
		}
		
		
		return $return;
	}
	
	public function getProperties(array $parents)
	{
		$return = array($this->_className => array());
		foreach($parents as $parent) {
			$return[$parent] = array();
		}
		
		foreach($this->_reflector->getProperties(ReflectionProperty::IS_PUBLIC | ReflectionProperty::IS_PROTECTED) as $prop) {
			$line = array();
			$line['name'] = $prop->getName();
			$line['modifiers'] = Reflection::getModifierNames($prop->getModifiers());
			$line['docComment'] = $prop->getDocComment();
			$return[$prop->getDeclaringClass()->getName()][] = $line;
		}
		
		return $return;
	}
	
	protected function _getParameters(Reflector $method)
	{
		$return = array();
		
		foreach($method->getParameters() as $param) {
			$line = array();
			//$line['name'] = $param->getName();
			$line['name'] = $param->__toString();
			if($param->isOptional()) {
				$line['default'] = $param->getDefaultValue();
			}
			$return[] = $line;
		}
		
		
		return $return;
	}
}