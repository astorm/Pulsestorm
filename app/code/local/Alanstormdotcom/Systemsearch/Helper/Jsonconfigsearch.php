<?php
	class Alanstormdotcom_Systemsearch_Helper_Jsonconfigsearch extends Mage_Core_Helper_Abstract
	{
		public function prepareAndSendResponse($nodes, $terms)
		{			
			$response = new stdClass();
			$response->responseText 	= array();
			$response->results			= array();
			$response->search_terms		= $terms;
			$helper 					= Mage::helper('adminhtml');
			foreach($nodes as $type=>$all_of_type)
			{
				foreach($all_of_type as $value)
				{
					$value = $this->fetchSimpleXmlHelper()->switchFromCommentToLabel($value);
					
					if($type == Alanstormdotcom_Systemsearch_Model_Configsearch::TYPE_SYSTEM_CONFIG_SECTION)
					{
						$response->responseText[] = 'Configuration/' . (string) $value;
		
						$result 				= new stdClass();
						$result->found			= true;
						$result->found_type		= 'nav';   //other option is 'group'
						$result->found_label	= $helper->__((string) $value);	
						$result->type 			= $type;
						$response->results[]	= $result;
					}
					else if($type == Alanstormdotcom_Systemsearch_Model_Configsearch::TYPE_SYSTEM_CONFIG_GROUP)
					{		
						$result 				= new stdClass();
						$result->found			= true;
						$result->found_type		= 'group';   //other option is 'group'
						$result->found_label	= $helper->__((string) $value);		
						$label_group			= $result->found_label;				
						$result->type 			= $type;
						$result->path 			= $this->fetchSimpleXmlHelper()->getPathExpression($value);
						
						$response->results[]	= $result;			
						
						//hop up three nodes to get the nav section
						$parent = $this->fetchSimpleXmlHelper()->getParentNode($value);
						$parent = $this->fetchSimpleXmlHelper()->getParentNode($parent);
						$parent = $this->fetchSimpleXmlHelper()->getParentNode($parent);
						$result 				= new stdClass();
						$result->found			= true;
						$result->found_type		= 'nav';   //other option is 'group'
						$result->found_label	= $helper->__((string) $parent->label);	
						$label_nav 				= $result->found_label;
						$result->type 			= $type;
						$response->results[]	= $result;
						
						//and also point out the nav
						$response->responseText[] = 'Configuration/' . 
						$label_nav . 
						'/' .
						$label_group . 
						'';				
					}
					else if($type == Alanstormdotcom_Systemsearch_Model_Configsearch::TYPE_SYSTEM_CONFIG_FIELD)
					{
						//hop up to group
						$parent = $this->fetchSimpleXmlHelper()->getParentNode($value);
						$parent = $this->fetchSimpleXmlHelper()->getParentNode($parent);
						$parent = $this->fetchSimpleXmlHelper()->getParentNode($parent);
		
						$result 				= new stdClass();
						$result->found			= true;
						$result->found_type		= 'group';   //other option is 'group'
						$result->found_label	= $helper->__((string) $parent->label);		
						$label_group			= $result->found_label;
						$result->type 			= $type;
						$response->results[]	= $result;	
		
		// 				//hope up to section
						$parent = $this->fetchSimpleXmlHelper()->getParentNode($parent);
						$parent = $this->fetchSimpleXmlHelper()->getParentNode($parent);
						
						$result 				= new stdClass();
						$result->found			= true;
						$result->found_type		= 'nav';   //other option is 'group'
						$result->found_label	= $helper->__((string) $parent->label);		
						$label_nav				= $result->found_label;
						$result->type 			= $type;
						$response->results[]	= $result;	
						
						$response->responseText[] = 'Configuration/' . 
						$label_nav . 
						'/' .
						$label_group . 
						'/' .
						(string) $value;
					}
				}
			}

			$response->responseText = $this->fetchLayout()->createBlock('alanstormdotcomsystemsearch/searchresults')			
			->addResultsArray($response->responseText)
			->toHtml();
			
			header('Content-Type: application/json');
			echo json_encode($response);	
			exit;
		}	
		
		protected function fetchSimpleXmlHelper()
		{
			return Mage::helper('alanstormdotcomsystemsearch/simplexml');
		}				
		
		private function fetchLayout()
		{
			return Mage::getSingleton('core/layout');;
		}		
		
	}