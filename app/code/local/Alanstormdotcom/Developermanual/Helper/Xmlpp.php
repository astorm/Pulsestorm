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
	class Alanstormdotcom_Developermanual_Helper_Xmlpp extends Mage_Core_Helper_Abstract
	{
		protected $_lastDepth		= 0;	
		protected $_reader;
		protected $_tagQueue		= array();
		const INDENT = '    ';
		
		public function pp($string)	
		{
			$this->_reader = new XmlReader();
			$this->_reader->xml($string);
			$this->_output = array();
			
			echo '<pre>';
			
			$this->_currentDepth = 0;
			
			while($this->_reader->read())
			{
				$this->_depthChange();
				$this->_renderIndent();
			    //echo $reader->name;
			    $this->_renderNode($this->_reader->nodeType, $this->_reader->name);
				if ($this->_reader->hasValue && !trim($this->_reader->value)) {
					echo ": " . trim($this->_reader->value);
				}
				echo ' [TYPE: '.$this->_reader->nodeType.']';
				echo ' [DEPTH: '.$this->_reader->depth.']';
				echo "<br />";
			}
			echo '</pre>';
// 			var_dump($string);	
			exit("here");
		}
		
		protected function _renderIndent()
		{
  			echo str_repeat(self::INDENT,$this->_reader->depth);
		}
		
		protected function _depthChange()
		{
			if($this->_reader->depth > $this->_lastDepth)
			{
				$this->_tagQueue[] = $this->_reader->name;
				echo "\n";
			}
			else if($this->_reader->depth < $this->_lastDepth)
			{
				$tag = array_pop($this->_tagQueue);
				$this->_renderEndTag($tag);
				echo "\n";
			}
			
			$this->_lastDepth = $this->_reader->depth;
			return;
		}
		
		protected function _renderNode($type, $name)
		{
			switch($type)
			{
				case XmlReader::COMMENT:
					$this->_renderCommentNode($name);
					break;
				case XmlReader::TEXT:
					$this->_reanderTextNode($name);
					break;			
				case XMLReader::SIGNIFICANT_WHITESPACE:
					break;
				default:
					$this->_renderStartTag($name);
			}
		}
		
		protected function _renderStartTag($name)
		{
			echo '&lt;' . $name . '&gt;';
		}
		
		protected function _renderEndTag($name)
		{
			echo '&lt;/' . $name . '&gt;';		
		}
		
		protected function _renderDepthIncrease()
		{
		}

		protected function _renderDepthDecrease()
		{
		}
		
		protected function _renderCommentNode($name)
		{
			echo $name;
		}
		
		protected function _reanderTextNode($name)
		{
			echo $name;
		}		
	}