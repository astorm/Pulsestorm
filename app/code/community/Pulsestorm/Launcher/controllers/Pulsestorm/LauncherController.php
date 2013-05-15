<?php
require_once 'Mage/Adminhtml/controllers/IndexController.php';
class Pulsestorm_Launcher_Pulsestorm_LauncherController extends Mage_Adminhtml_IndexController
{
    protected $_publicActions=array('index','globalSearch');
    public function indexAction()
    {
        var_dump(__METHOD__);
    }
    
    public function globalSearchAction()
    {
        //end session to avoid locking problems
        //https://github.com/astorm/Pulsestorm/issues/22#issuecomment-17904578    
        session_write_close();
        return parent::globalSearchAction();
    }
}