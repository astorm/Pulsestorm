<?php

function hello($params, $block, $layout, $request, $response)
{    
    $block->setTemplate(
    'simplepage/' . $request->getControllerName() .
    '_' . 
    $request->getActionName() . '.phtml');
    
    return $block;
}