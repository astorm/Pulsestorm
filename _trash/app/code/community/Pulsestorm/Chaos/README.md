Pulse Storm Chaos
--------------------------------------------------
**Important**: This module is **not** intended for production use, or for beginning Magento developers.  It's a little crazy, so procede at your own risk.

The Chaos module provides a Magento programmer with the ability to assign random configuration values at runtime.  Introducing this "chaos" into the system exposes incorrect assumptions about the core Magento PHP APIs in client code. It could also be used to cuss with people's heads. 

This is done via the `applyConfigNodeCallbacks` method of the "entropy" model. This method accepts a PHP array of key/value pairs

    $fields = array(
    'catalog/frontend/flat_catalog_category'=>function()
    {
        return (string) rand(0,1);
    });
    
    Mage::getModel('pulsestorm_chaos/entropy')
    ->applyConfigNodeCallbacks($fields);

Each key of the PHP array is the configuration node whose value you wish to change.  Each value is <a href="http://php.net/manual/en/language.types.callable.php">a valid PHP callback</a>.  This callback is responsible for returning a random value.  In the above example, the `catalog/frontend/flat_catalog_category` configuration node will be set to either 0 or 1.

In addition to providing a valid PHP callback, the developer may also provide the name of a method on the `pulsestorm_chaos/values` model.  For example, the class for this model ships with the `getRandomBoolean` method for randomly assigning booleans.

    class Pulsestorm_Chaos_Model_Values
    {
        public function getRandomBoolean()
        {
            return (string) rand(0,1);
        } 
    }
    
This method may be used with the following

    $fields = array(
    'catalog/frontend/flat_catalog_category'=>'getRandomBoolean');
    
    Mage::getModel('pulsestorm_chaos/entropy')->applyConfigNodeCallbacks($fields);
    
Default Observer
--------------------------------------------------
In addition to the above mechanism, the Pulse Storm Chaos module ships with an observer method for the `controller_action_predispatch` event.  This observer

1. Applies the callbacks in `etc/fields.php`
2. Fires two events of its own, `pulsestorm_chaos_start_before` and `pulsestorm_chaos_start_after`

This observer will **not** apply itself to the Magento backend.

Configuration Changes
--------------------------------------------------
The configuration values this module sets are at runtime only, and for the current store only.  They're applied with code that looks something like this

    $config = Mage::getConfig();
    $config->setNode("stores/$code/" . $path, call_user_func($callback));
    
**IMPORTANT**: If the configuration object is persisted to cache **after** the callbacks have been applied, the chaotic values may be persisted to cache along with the object.     