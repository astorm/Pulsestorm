<?php

class Pulsestorm_Modulelist_Pulsestorm_CssController extends Mage_Adminhtml_Controller_Action
{
    public function cssAction()
    {
    	header('Content-Type: text/css');
    	include(realpath(dirname(__FILE__)) . '/../css/main.css');
    	exit;
    }
}