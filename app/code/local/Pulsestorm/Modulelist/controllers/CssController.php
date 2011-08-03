<?php

class Pulsestorm_Modulelist_CssController extends Mage_Adminhtml_Controller_Action
{
    public function mainAction()
    {
    	header('Content-Type: text/css');
    	include(realpath(dirname(__FILE__)) . '/../css/main.css');
    	exit;
    }
}