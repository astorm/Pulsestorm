<?php
class Pulsestorm_Modulelist_Block_Moduletable extends Pulsestorm_Modulelist_Block_Template
{
	public function _construct()
	{
		$this->setTemplate('table.phtml');
	}
	
	private function _paramToArrayOfParams($param)
	{
		$param = $param ? $param : array();
		if(!is_array($param))
		{
			$param = array($param);
		}
		return $param;
	}
	
	public function yesNoCell($value,$tag='td',$classes=false)
	{
		$classes 	= $this->_paramToArrayOfParams($classes);
		$class		= '';
		if($value === 'no')
		{
			$classes[] = 'pulsestorm_modulelist_no';	
		}
		if($classes)
		{
			$class = 'class="'.implode(' ',$classes).'"';
		}
		return sprintf('<%s %s>%s</%s>',$tag,$class,$value,$tag);
	}
	
	public function trueFalseCell($value,$tag='td',$classes=false)
	{
		$classes 	= $this->_paramToArrayOfParams($classes);
		$class		= '';
		if($value === 'false')
		{
			$classes[] = 'pulsestorm_modulelist_no';	
		}
		if($classes)
		{
			$class = 'class="'.implode(' ',$classes).'"';
		}
		return sprintf('<%s %s>%s</%s>',$tag,$class,$value,$tag);
	}	
}