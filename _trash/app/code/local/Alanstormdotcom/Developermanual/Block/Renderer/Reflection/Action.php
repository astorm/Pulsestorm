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
	class Alanstormdotcom_Developermanual_Block_Renderer_Reflection_Action extends Mage_Core_Block_Text
	{
		const COMMENT_INDENT = '     ';
		
		public function _toHtml()
		{			
			$this->_checkRequired();
			$info = $this->getInfo();
			$certain = $info['certain'];
			
			$dom = new DomDocument();
			$dom->preserveWhitespace = false;
			
			$block 			= $dom->createElement('block');
			$attr  			= $dom->createAttribute('type');
			$attr->value 	= $this->getAlias();
			
			$block->appendChild($attr);			
			$dom->appendChild($block);
			
			
			$output = simplexml_load_string('<block />');
			foreach($certain as $method)
			{		
				$block->appendChild(
					$dom->createComment("\n     " . $this->_getDocumentation($method) . "\n  ")
				);					
				
				$dom_action = $dom->createElement('action');
				$block->appendChild($dom_action);			

				$dom_attr = $dom->createAttribute('method');
				$dom_attr->value = $method->getName();
				$dom_action->appendChild($dom_attr);
				
				$this->addParamsToDomActionNodeFromReflectionMethod($dom, $dom_action, $method);
				//$action = $this->addParamsToActionNodeFromReflectionMethod($action, $method);
			}			
			
			$dom->formatOutput = true;
			return $this->_extraXmlFormatting($dom->saveXml());
		}

		/**
		* Replace with proper formatter in future
		*/		
		protected function _extraXmlFormatting($string)
		{			
			#Mage::Log($string);
			$string = str_replace('</action>',"</action>	\n",$string);						
			if($this->getEscapeXml())
			{
				$string = '<pre>'.htmlspecialchars($string).'</pre>';
			}
			
			#EEEEEEEEEVIL
			$string = preg_replace('%method=(.+?)\&gt;%','method=<strong>$1</strong>&gt;',$string);
			$string = preg_replace('%(&lt;!--.+?--&gt;)%s','<em>$1</em>',$string);			
			return $string;
		}
		
		protected function _hasDocumentation($method)
		{
			return $method->getDocComment();		
		}
		
		protected function _getDocumentation($method)
		{		
			if($this->_hasDocumentation($method))
			{
				return $this->_getYesDocumentation($method);
			}
			
			return $this->_getNoDocumentation($method);
		}
		
		static public function normalizeDocComment($string)
		{
		    return preg_split('%\*%', trim(str_replace('*/','',str_replace('/**','',$string))),-1,PREG_SPLIT_NO_EMPTY);
		}
		protected function _getYesDocumentation($method)
		{
			#$lines = preg_split('%\*%', trim(str_replace('*/','',str_replace('/**','',$method->getDocComment()))),-1,PREG_SPLIT_NO_EMPTY);
			$lines = self::normalizeDocComment($method->getDocComment());
			
			$output = array();			
			$hit_at = false;
			$indent = '';
			foreach($lines as $line)
			{
				$line = trim($line);
				if(!$hit_at && strlen($line) > 1 && $line[0] == '@')
				{
					$output[] 	= $this->_getImplementationCopy($method);
					$hit_at 	= true;
				}
				$output[] = $indent . $line;
				$indent = self::COMMENT_INDENT;
			}
			return implode("\n",$output);
		}
		
		protected function _getImplementationCopy($method)
		{
			return self::COMMENT_INDENT . 'See implementation in ' . "\n"
			. self::COMMENT_INDENT . $method->getDeclaringClass()->getFileName();				
		}
		
		protected function _getNoDocumentation($method)
		{
			return 'No Documentation Available for ' . 
			$method->class . '::' . $method->getName() . "\n"
			. $this->_getImplementationCopy($method);
		}
		
		public function addParamsToDomActionNodeFromReflectionMethod($dom, $dom_action, $method)
		{
			foreach($method->getParameters() as $param)
			{
				$xParam = $dom->createElement($param->getName(),'VALUE');
				$dom_action->appendChild($xParam);
			}		
			return $dom;
		}
		
		protected function _checkRequired()
		{
			$to_set = array(
				'setInfo'=>$this->getInfo(),
				'setAlias'=>$this->getAlias(),
			);
			
			foreach($to_set as $key=>$value)
			{
				if(!$value)
				{
					throw new Exception(sprintf('Please Call %s before rendering a %s',$key, __CLASS__));
				}
			}
		}
	}
	