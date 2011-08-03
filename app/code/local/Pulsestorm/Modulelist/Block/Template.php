<?php
class Pulsestorm_Modulelist_Block_Template extends Mage_Core_Block_Abstract
{
	protected function _toHtml()
	{
		$template = $this->getTemplate();
		include(dirname(__FILE__) . '/../templates/' . $template);
	}
}