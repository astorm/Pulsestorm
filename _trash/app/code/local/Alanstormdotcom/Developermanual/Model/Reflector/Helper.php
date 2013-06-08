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
		$return = array('own_methods' => array(),
						'inherited' => array());
		foreach($parents as $parent) {
			$return['inherited'][$parent] = array();
		}
			
		$methods = $this->_reflector->getMethods(ReflectionMethod::IS_STATIC | ReflectionMethod::IS_PUBLIC |
												 ReflectionMethod::IS_PROTECTED | ReflectionMethod::IS_FINAL);
		foreach($methods as $method) {
			$line = array();
			$line['name'] = $method->name;
			$line['fileName'] = $method->getFileName();
			$line['modifiers'] = Reflection::getModifierNames($method->getModifiers());
			$line['parameters'] = $this->_getParameters($method);
			$line['docComment'] = $method->getDocComment();
			$line['lineStart'] = $method->getStartLine();
			$line['lineEnd'] = $method->getEndLine();
			if($method->getDeclaringClass()->getName() == $this->_className) {
				$return['own_methods'][] = $line;
			} else{
				$return['inherited'][$method->getDeclaringClass()->getName()][] = $line;
			}
		}
		
		return $return;
	}
	
	public function getProperties(array $parents)
	{
		$return = array('own_props' => array(),
						'inherited' => array());
		foreach($parents as $parent) {
			$return['inherited'][$parent] = array();
		}
		
		foreach($this->_reflector->getProperties(ReflectionProperty::IS_PUBLIC | ReflectionProperty::IS_PROTECTED) as $prop) {
			$line = array();
			$line['name'] = $prop->getName();
			$line['modifiers'] = Reflection::getModifierNames($prop->getModifiers());
			$line['docComment'] = $prop->getDocComment();
			$line['default'] = $this->_getDefaultValue($prop);
			if($prop->getDeclaringClass()->getName() == $this->_className) {
				$line['fileName'] = $this->_reflector->getFileName();
				$return['own_props'][] = $line;
			} else{
				$class = new ReflectionClass($prop->getDeclaringClass()->getName());
				$line['fileName'] = $class->getFileName();
				$return['inherited'][$prop->getDeclaringClass()->getName()][] = $line;
			}
		}

		return $return;
	}
	
	public function getConstants()
	{
		return $this->_reflector->getConstants();
	}
	
	public function getDocComment()
	{
		return $this->_reflector->getDocComment();
	}
	
	protected function _getParameters(Reflector $method)
	{
		$return = array();
		
		foreach($method->getParameters() as $param) {
			$line = array();
			$line['name'] = $param->__toString();
			if($param->isOptional()) {
				$line['default'] = $param->getDefaultValue();
			}
			
			$return[] = $line;
		}
		
		
		return $return;
	}
	
	protected function _getDefaultValue(Reflector $prop)
	{
		if($prop->isProtected() && method_exists($prop,'setAccesible')) {
			$prop->setAccessible(true);
		}
		else if($prop->isProtected())
		{
		    return '[UNKNOWN DEFAULT VALUE] (upgrade to PHP 5.3+ to reflect into protected properties)';
		}
		
		if($prop->isStatic()) {
			return $prop->getValue();
		}
		
		if(! $prop->getDeclaringClass()->isAbstract()) {
			$className = $prop->getDeclaringClass()->getName();
			$class = new $className();
			return $prop->getValue($class);
		}
		
		return null;
	}
}