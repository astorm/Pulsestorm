<?php
	class Alanstormdotcom_Layoutviewer_Model_Observer extends Varien_Object{
		const FLAG_SHOW_LAYOUT 			= 'showLayout';
		const FLAG_SHOW_LAYOUT_FORMAT 	= 'showLayoutFormat';		
		const HTTP_HEADER_TEXT			= 'Content-Type: text/plain';
		const HTTP_HEADER_HTML			= 'Content-Type: text/html';
		const HTTP_HEADER_XML			= 'Content-Type: text/xml';
		
		private $request;
		
		private function init() {
			$this->setLayout(Mage::app()->getFrontController()->getAction()->getLayout());
			$this->setUpdate($this->getLayout()->getUpdate());
		}
		
		//entry point
		public function checkForLayoutDisplayRequest($observer) {			
			$this->init();
			$is_set = array_key_exists(self::FLAG_SHOW_LAYOUT, $_GET);
			if(		$is_set && 'package' == $_GET[self::FLAG_SHOW_LAYOUT]) {
				$this->outputPackageLayout();
			}
			else if($is_set && 'page'    == $_GET[self::FLAG_SHOW_LAYOUT]) {
				$this->outputPageLayout();			
			}
			else if($is_set && 'handles' == $_GET[self::FLAG_SHOW_LAYOUT]) {
				$this->outputHandles();
			}
		}

		private function outputHandles() {
			$update = $this->getUpdate();
			$handles = $update->getHandles();
			echo '<h1>','Handles For This Request','</h1>'."\n";
			echo '<ol>' . "\n";
			foreach($handles as $handle) {
				echo '<li>',$handle,'</li>';
			}
			echo '</ol>' . "\n";			
			die();
		}
		
		private function outputHeaders() {
			$is_set = array_key_exists(self::FLAG_SHOW_LAYOUT_FORMAT,$_GET);			
			$header		= self::HTTP_HEADER_XML;
			if($is_set && 'text' == $_GET[self::FLAG_SHOW_LAYOUT_FORMAT]) {
				$header = self::HTTP_HEADER_TEXT;
			}
			header($header);
		}
		
		private function outputPageLayout() {
			$layout = $this->getLayout();
			$this->outputHeaders();		
			die($layout->getNode()->asXML());		
		}
		
		private function outputPackageLayout() {
			$update = $this->getUpdate();
			$this->outputHeaders();
			die($update->getPackageLayout()->asXML());
		}
	}