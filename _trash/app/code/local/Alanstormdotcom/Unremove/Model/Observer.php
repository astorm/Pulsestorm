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
	class Alanstormdotcom_Unremove_Model_Observer
	{
		const ROOTNODE = 'root';
		public function unremoveUpdate($observer)
		{
			$layout = $observer->getLayout();
			$update = $observer->getLayout()->getUpdate();			
			$original_updates = $update->asArray();			
			$update->resetUpdates();			
			
			$to_unremove  = $this->_getUnremoveNames($this->_getSimplexmlFromFragment(implode('',$original_updates)));

			foreach($original_updates as $s_xml_update)
			{				
				$s_xml_update = $this->_processUnremoveNodes($s_xml_update, $to_unremove);
				$update->addUpdate($s_xml_update);
			}			
		}
		
		protected function _processUnremoveNodes($string, $to_unremove)
		{
			$o_xml_update = $this->_getSimplexmlFromFragment($string);
			$nodes = $o_xml_update->xpath('//remove');
			foreach($nodes as $node)
			{
				if(in_array($node['name'], $to_unremove))
				{
					unset($node['name']);
				}				
			}

			$s_xml = '';
			foreach($o_xml_update->children() as $node)
			{
				$s_xml .= $node->asXml();
			}
			return $s_xml;

		}
		
		protected function _getUnremoveNames($xml)
		{
			$nodes 		= $xml->xpath('//x-unremove');
			$unremove 	= array();
			foreach($nodes as $node)
			{
				$unremove[] = (string) $node['name'];
			}				
			return $unremove;
		}
		
		protected function _getSimplexmlFromFragment($fragment)
		{
			return simplexml_load_string('<'.self::ROOTNODE.'>'.$fragment.'</'.self::ROOTNODE.'>');		
		}
	}