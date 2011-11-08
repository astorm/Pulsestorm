<?php
class Alanstormdotcom_Developermanual_Block_Renderer_Reflection_Helper extends Alanstormdotcom_Developermanual_Block_Template
{
	public function parseParameters($params)
	{	
		$list = array();
		foreach($params as $param) {
			$start = strpos($param['name'], '$');
			$end = strpos($param['name'], ']');
			
			$part = substr($param['name'], $start, $end - $start - 1);
			
			$list[] = $part;
		}
		
		return '(' . implode(', ', $list) . ')';
		
	}
	
	protected function _parseDefault($default)
	{
		if(is_string($default)) {
			
		} elseif(is_numeric($default)) {
			
		} elseif(is_bool($default)) {
			
		} elseif(is_null($default)) {
			
		} elseif(is_array($default)) {
			
		}
	}
}