<?php
class Pulsestorm_Javascriptevent_Model_Observer
{
    public function addJavascriptBlock($observer)
    {
        $controller = $observer->getAction();
        $layout = $controller->getLayout();
        $block = $layout->createBlock('adminhtml/template');
        $block->setTemplate('pulsestorm_javascriptevent/hello.phtml');        
        $layout->getBlock('js')->append($block);
    }
}