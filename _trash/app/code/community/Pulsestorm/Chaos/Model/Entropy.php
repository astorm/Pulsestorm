<?php
class Pulsestorm_Chaos_Model_Entropy
{
    public function applyConfigNodeCallbacks($nodes)
    {
        $config = Mage::getConfig();
        $store = Mage::app()->getStore();
        $code  = $store->getCode();
        
        $value_helper = Mage::getSingleton('pulsestorm_chaos/values');

        foreach($nodes as $path=>$value)
        {
            $callback = array($value_helper, $value);
            if(is_callable($callback))
            {
                $config->setNode("stores/$code/" . $path, call_user_func($callback));
            }
            else if(is_callable($value))
            {
                $config->setNode("stores/$code/" . $path, call_user_func($value));
            }
            else
            {
                throw new Exception("Callback $value not found");
            }
            
        }    
    }
}