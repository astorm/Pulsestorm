<?php
class Pulsestorm_Modulelist_Block_Top extends Pulsestorm_Modulelist_Block_Template
{
	public function createTableBlock($list)
	{
		return $this->getLayout()->createBlock('pulsestorm_modulelist/moduletable')
		->setList($list);
	}
}