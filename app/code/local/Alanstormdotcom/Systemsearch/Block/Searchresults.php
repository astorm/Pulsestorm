<?php
	class Alanstormdotcom_Systemsearch_Block_Searchresults extends Alanstormdotcom_Systemsearch_Block_Template
	{
		protected $_resultsArray;
		
		const ARROW_SEP =  ' <span style="font-family:fixed">-&gt;</span> ';
		public function __construct()
		{
			$this->setTemplate('results.phtml');
		}
		
		public function addResultsArray($array)
		{
			$array = array_unique($array);
			sort($array);
			$this->assignResultsArray($array);			
			return $this;
		}
		
		protected function assignResultsArray($array)
		{
			$this->_resultsArray = $array;
		}
		
		protected function fetchResultsArray()
		{
			return $this->_resultsArray;
		}
		
		protected function fetchStyledResultsArray()
		{
			return str_replace('/',self::ARROW_SEP,$this->fetchResultsArray());			
		}
	}