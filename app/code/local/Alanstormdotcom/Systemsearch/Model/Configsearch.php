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
	class Alanstormdotcom_Systemsearch_Model_Configsearch
	{
		public function searchSystemConfigForTerms($terms)
		{
			return $this->xPathTextSearch($terms);		
		}		
				
		public function xPathTextSearch($search_for = 'advanced')
		{	
			#$search_for = Mage::helper('alanstormdotcomsystemsearch')->reverseTranslate($search_for); //more complicated
			$sections = Mage::getConfig()
			->loadModulesConfiguration('system.xml')
			->getNode('sections');        
			
// 			header('Content-Type: text/xml');
// 			echo $sections->asXml();
// 			exit;
			$nodes = $this->getNodesWithText($search_for, $sections);
			$nodes_by_type = array();
			foreach($nodes as $node)
			{			
				$type = $this->getTypeOfSystemConfigNode($node);
				$nodes_by_type[$type] = array_key_exists($type, $nodes_by_type) ? $nodes_by_type[$type] : array();
				$nodes_by_type[$type][] = $node;
			}
			
			return $nodes_by_type;
		}
	
		const TYPE_SYSTEM_CONFIG_UNKNOWN 	= 'UNKNOWN';
		const TYPE_SYSTEM_CONFIG_TAB		= 'TAB';
		const TYPE_SYSTEM_CONFIG_SECTION 	= 'SECTION';
		const TYPE_SYSTEM_CONFIG_GROUP 		= 'GROUP';
		const TYPE_SYSTEM_CONFIG_FIELD 		= 'FIELD';		
		protected function getTypeOfSystemConfigNode($node)
		{
			$path 	= $this->fetchSimpleXmlHelper()->getPathExpression($node);
			// var_dump($path);
			
			$parts 	= explode('/',$path);
			// var_dump($parts);
			
			$parent_name = $parts[count($parts)-2];
			// var_dump($parent_name);
			
			switch($parent_name)
			{
				case 'sections':
					return self::TYPE_SYSTEM_CONFIG_SECTION;
				case 'groups':
					return self::TYPE_SYSTEM_CONFIG_GROUP;			
				case 'fields':
					return self::TYPE_SYSTEM_CONFIG_FIELD;				
				case 'tabs':
					return self::TYPE_SYSTEM_CONFIG_TAB;				
				default:
					return self::TYPE_SYSTEM_CONFIG_UNKNOWN;
			}
		}
				
		protected function getNodesWithText($search_for, $xml)
		{
			$nodes = array();
			#only works with us_EN, bad americano!
// 			$nodes = array_merge($nodes, $this->getSpecificNodeWithText('label',$search_for, $xml));			
// 			$nodes = array_merge($nodes, $this->getSpecificNodeWithText('comment',$search_for, $xml));					
			
			//get the nodes
			$nodes = array_merge($nodes,$xml->xpath('//label'));
			$nodes = array_merge($nodes,$xml->xpath('//comment'));
			
			//filter the nodes 
			$found = array();
			$helper = Mage::helper('adminhtml');			
			foreach($nodes as $node)
			{
				if( mb_stristr($helper->__((string) $node),$search_for) !== false)
				{
					$found[] = $node;
				}
			}			
			
			return $found;
		}
		
		protected function getSpecificNodeWithText($node_name, $search_for, $xml)
		{
			$expression = $this->createXpathQuery($node_name, $search_for);
			return $xml->xpath($expression);
		}
		
		//oh xpath 1, how I love thee
		protected function createXpathQuery($node, $search_for)
		{
			return sprintf(
			"//%s[contains(translate(.,'ABCDEFGHIJKLMNOPQRSTUVWXYZ','abcdefghijklmnopqrstuvwxyz'),translate('%s','ABCDEFGHIJKLMNOPQRSTUVWXYZ','abcdefghijklmnopqrstuvwxyz'))]",
			$node,
			$search_for
			);
		}
		
		public function showAllConfigDebug() {			
			$sections = Mage::getConfig()
			->loadModulesConfiguration('system.xml')
			->getNode('sections');        
			
			$sections = $sections->xpath('//sections');
			$sections = $sections[0];
	
			foreach($sections as $section)
			{			
				$label_section = (string) $section->label;
				var_dump('Section: ' . $label_section);
				$groups = $section->xpath('groups');
				$groups = $groups[0];
				foreach($groups as $group)
				{
					$label_group = (string) $group->label;
					var_dump('    Group: ' . $label_group);
					$fields = $group->xpath('fields');
					$fields = count($fields) > 0 ? $fields[0] : array();				
					foreach($fields as $field)
					{
						$label_field   = (string) $field->label;
						$comment_field = (string) $field->comment;
						var_dump('        Field: ' . $label_field);
						if($comment_field)
						{
							var_dump('        Comment: ' . $comment_field);					
						}
					}
				}
			}		
		}
				
		protected function fetchSimpleXmlHelper()
		{
			return Mage::helper('alanstormdotcomsystemsearch/simplexml');
		}								
	}