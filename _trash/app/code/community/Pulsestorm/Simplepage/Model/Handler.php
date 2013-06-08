<?php
/**
* This is the class responisble for parsing through the URLs configured
* in simplepage.xml and deciding if there's a match.  Its instantiated
* as a singleton
*/
class Pulsestorm_Simplepage_Model_Handler extends Varien_Object
{
    const DEFAULT_MODULE        = 'Pulsestorm_Simplepage';
    const DEFAULT_BLOCK_CLASS   = 'pulsestorm_simplepage/view';
    public function init($request)
    {        
        $tree = Mage::getConfig()->loadModulesConfiguration('simplepage.xml');
        foreach($tree->getNode('pulsestorm_simplepage_routes')->children() as $item)
        {            
            $regex      = '#' . $item->url_regex . '#';                        
            $path = trim($request->getPathInfo(), '/');
            if(preg_match($regex, $path, $matches))
            {
                $this->setViewName((string)$item->view)
                ->setModule((string)$item->module)
                ->setBlockClass((string)$item->block_class)
                ->setParams($matches);

                if(!$this->getModule())
                {
                    $this->setModule(self::DEFAULT_MODULE);
                }
                
                if(!$this->getBlockClass())
                {
                    $this->setBlockClass(self::DEFAULT_BLOCK_CLASS);
                }
                
                if(!$this->getViewName())
                {
                    throw new Exception(
                    'Simple Page regular expression match, but no view set.'
                    );
                }
                break; //can stop for each since we found a match
            }                        
        }
        return $this;
    }
    
    /**
    * Generates a layout handle in case users will want to use layout xml
    */    
    public function getLayoutHandleName()
    {
        return strToLower('simplepage' . '_' . $this->getModule() . '_' . $this->getViewName());
    }
}