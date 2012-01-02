<?php
class Alanstormdotcom_Developermanual_Model_Apiref extends Varien_Object
{
    
    public function _construct()
    {
        $config = Mage::getConfig()->loadModulesConfiguration('api.xml');
        $api = $config->getNode('api');
        $this->setNodeApi($api);    
    }
    
    public function getResourceNodes()
    {
        return $this->getNodeApi()->resources->children();        
    }    
    
    public function getResourceByName($name)
    {
        foreach($this->getResourceNodes() as $node)
        {
            if((string)$node->getName() == $name)
            {
                return $node;
            }
        }
        return false;
    }
}