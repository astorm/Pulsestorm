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
	class Alanstormdotcom_Systemsearch_Helper_Simplexml extends Mage_Core_Helper_Abstract
	{
		public function switchFromCommentToLabel($node)		
		{
			if($node->getName() != 'comment')
			{
				return $node;
			}
			$nodes = $node->xpath('../label');
			$node = count($nodes) ? $nodes[0] : $node;
			return $node;
		}		
		
		public function getPathExpression($node)
		{
			$reverse = $this->getReversePathExpression($node);
			return implode('/',array_reverse(explode('/', $reverse)));
		}
		
		public function getParentNode($node)
		{
			$nodes = $node->xpath('..');
			if(count($nodes) > 0)
			{
				return $nodes[0];
			}		
			return false;
		}	
		
		public function getReversePathExpression($node,$fragment='')
		{
			$parent = $this->getParentNode($node);
			if($parent)
			{
				//recursion, HO!
				$fragment .= $parent->getName() . '/';
				return $this->getReversePathExpression($parent, $fragment);
			}		
			return $fragment;
		}
		
	}