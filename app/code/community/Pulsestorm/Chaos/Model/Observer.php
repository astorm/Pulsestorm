<?php
class Pulsestorm_Chaos_Model_Observer
{
    static protected $_hasRun = false;
    public function setup($observer)
    {
        if($this->_shouldBail($observer))
        {
            return;
        }
        Mage::dispatchEvent('pulsestorm_chaos_start_before');
        
        $path = Mage::getConfig()->getModuleDir('etc','Pulsestorm_Chaos') . DS . 'fields.php';
        $fields = include $path;        
        Mage::getModel('pulsestorm_chaos/entropy')->applyConfigNodeCallbacks($fields);

        Mage::dispatchEvent('pulsestorm_chaos_start_after');
    }
    
    protected function _shouldBail($observer)
    {
        $config = Mage::getConfig();
        if(!$config)
        {
            return true;
        }

        if(Mage::app()->getStore()->isAdmin())
        {
            return true;
        }
        
        return false;
    }
}