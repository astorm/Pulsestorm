<?php
/**
* So as not to break TOO much with how Magento handles things, Simple Page still
* routes to a controller, but the controller's perDispatch and postDispatch method
* are responsible for layout rendering.
*
* @see Pulsestorm_Simplepage_Controller_Base
*/
class Pulsestorm_Simplepage_IndexController extends Pulsestorm_Simplepage_Controller_Base
{    
    public function indexAction()
    {
        //empty    
    }
}