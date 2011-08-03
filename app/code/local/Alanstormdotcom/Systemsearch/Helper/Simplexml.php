<?php
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