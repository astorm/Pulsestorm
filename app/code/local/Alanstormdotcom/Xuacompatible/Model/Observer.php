<?php
class Alanstormdotcom_Xuacompatible_Model_Observer
{
	const RE_HEAD = '{(<head[^>]*?>)}i';
	public function applyTag($observer)
	{
		$body = $observer->getResponse()->getBody();
		if(strpos(strToLower($body), 'x-ua-compatible') !== false)
		{
			return;
		}		
		$body = preg_replace(self::RE_HEAD,'$1'.$this->_getMetaTagString(),$body);
		$observer->getResponse()->setBody($body);
	}

	/**
	* Just hardcode this for now
	*/	
	protected function _getMetaTagString()
	{
		return '<meta http-equiv="X-UA-Compatible" content="IE=8" />';	
	}
}