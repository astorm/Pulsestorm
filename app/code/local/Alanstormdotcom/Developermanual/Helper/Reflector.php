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
	class Alanstormdotcom_Developermanual_Helper_Reflector extends Mage_Core_Helper_Abstract
	{
		protected $_baseAbstractMethods=false;
		public function getActionInformation($alias)
		{
			$values = array('certain'=>array(),'maybe'=>array());
			$block = $this->getLayout()->createBlock($alias);			
			
			$r = new ReflectionClass($block);
			foreach($r->getMethods() as $method)
			{
				$tmp = new Varien_Object();
				if($this->_isBlockAction($method) && !$this->_hasObjectParam($method))
				{					
					$values['certain'][] = $method;
				}
				else if(!$this->_hasObjectParam($method))
				{
					$values['maybe'] = $method;
				}
			}
			
			return $values;
		}

		/**
		* Should we show this as an action, based on logic and hard coding
		*
		* @todo Break into better system if too many clauses.  Well, not really, but include this line so people who are snobby will think we're considering it.
		* @param ReflectionMethod $var_name
		* @return boolean
		*/		
		protected function _isBlockAction($method)
		{
			if($this->_isWhitelisted($method))
			{
				return true;
			}
			
			if(!$method->isPublic())
			{
				return false;
			}
			
			if($method->isStatic())
			{
				return false;
			}
			
			if(strpos($method->name,'get') === 0 ) 	//starts with get; getFoo, getUrl
			{
				return false;
			}

			if(strpos($method->name,'is') === 0 ) 	//starts with is; isFoo, isUrl
			{
				return false;
			}
			
			if($this->_isFromAbstract($method)) 	//most methods from the base abstrac class don't make sense here
			{
				return false;				
			}
			
			//still here?  awesome!
			return true;
		}

		/**
		* Is this method from the base abstract block class?
		* @param ReflectionMethod $var_name
		* @return type $var_name
		*/
		protected function _isFromAbstract($method)
		{
			if(!$this->_baseAbstractMethods)
			{
				$base = $this->getLayout()->createBlock('alanstormdotcom_developermanual/abstractref');
				$r = new ReflectionClass($base);
				foreach($r->getMethods() as $method)
				{
					$this->_baseAbstractMethods[$method->name] = $method;
				}			
			}
			
			return (array_key_exists($method->name, $this->_baseAbstractMethods));
		}
		
		/**
		* Methods where we've gathered real evidence that they're whitlisted
		* @param ReflectionMethods
		* @return boolean
		*/
		protected function _isWhitelisted($method)
		{
			$whitelist = array('setTemplate');
			foreach($whitelist as $safe)
			{
				if($method->name == $safe)
				{
					return true;
				}
			}
			return false;			
		}
		
		/**
		* Methods that accept objets are **probably** not meant to be
		* called via an action method
		*
		* @todo leave unimplemented for now, need to research type hint reflection report
		* @param ReflectionMethod
		* @return boolean
		*/
		protected function _hasObjectParam($method)
		{
			// foreach($method->getParameters() as $hint)
			// 			{
			// 				var_dump($hint);
			// 				var_dump($hint->getTypeHint());
			// 			}
			return false;
		}
		
		public function getLayout()
		{
			return Mage::getSingleton('core/layout');
		}
	}
	