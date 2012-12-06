#!/usr/bin/env php
<?php
// this is a quickly ginned up shell script that accepts a single attribute code 
//
// $ php magento-create-setup.php gender
//
// and spits out a setup resource migration script that will install the product
// attribute into a second system.  
// Developed and tested against Magento CE 1.7.0.1, but should work elsewhere
// Feedback and forks/improvements welcome


// Copyright (c) <2012> <PulseStorm LLC>
// 
// Permission is hereby granted, free of charge, to any person
// obtaining a copy of this software and associated documentation
// files (the "Software"), to deal in the Software without
// restriction, including without limitation the rights to use, copy,
// modify, merge, publish, distribute, sublicense, and/or sell copies
// of the Software, and to permit persons to whom the Software is
// furnished to do so, subject to the following conditions:
// 
// The above copyright notice and this permission notice shall be
// included in all copies or substantial portions of the Software.
// 
// THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
// EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
// MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
// NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS
// BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN
// ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN
// CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
// SOFTWARE.


function bootstrap()
{
    /**
     * Error reporting
     */
    error_reporting(E_ALL | E_STRICT);
    
    $mageFilename = 'app/Mage.php';    
    $maintenanceFile = 'maintenance.flag';    
    require_once $mageFilename;
    
    #Varien_Profiler::enable();
    
    Mage::setIsDeveloperMode(true);
    
    ini_set('display_errors', 1);
    
    umask(0);

    /* Store or website code */
    $mageRunCode = isset($_SERVER['MAGE_RUN_CODE']) ? $_SERVER['MAGE_RUN_CODE'] : '';
    
    /* Run store or run website */
    $mageRunType = isset($_SERVER['MAGE_RUN_TYPE']) ? $_SERVER['MAGE_RUN_TYPE'] : 'store';

    if (method_exists('Mage', 'init')) {
        Mage::init($mageRunCode, $mageRunType);
    } else {
        Mage::app($mageRunCode, $mageRunType);
    }

    return $mageFilename;
}


function get_option_array_for_attribute($attribute)
{
    $read   = Mage::getModel('core/resource')->getConnection('core_read');
    $select = $read->select()
    ->from('eav_attribute_option')
    ->join('eav_attribute_option_value','eav_attribute_option.option_id=eav_attribute_option_value.option_id')
    ->where('attribute_id=?',$attribute->getId())
    ->where('store_id=0')
    ->order('eav_attribute_option_value.option_id');
    
    $query = $select->query();
    
    $values = array();
    foreach($query->fetchAll() as $rows)
    {
        $values[] = $rows['value'];
    }
    
    //$values = array('#f00000','abc123');
    return array('values'=>$values);
}

function get_key_legend()
{
    return array(

        //catalog
        'frontend_input_renderer'       => 'input_renderer',
        'is_global'                     => 'global',
        'is_visible'                    => 'visible',
        'is_searchable'                 => 'searchable',
        'is_filterable'                 => 'filterable',
        'is_comparable'                 => 'comparable',
        'is_visible_on_front'           => 'visible_on_front',
        'is_wysiwyg_enabled'            => 'wysiwyg_enabled',
        'is_visible_in_advanced_search' => 'visible_in_advanced_search',
        'is_filterable_in_search'       => 'filterable_in_search',
        'is_used_for_promo_rules'       => 'used_for_promo_rules',


        'backend_model'                 => 'backend',
        'backend_type'                  => 'type',
        'backend_table'                 => 'table',
        'frontend_model'                => 'frontend',
        'frontend_input'                => 'input',
        'frontend_label'                => 'label',
        'source_model'                  => 'source',
        'is_required'                   => 'required',
        'is_user_defined'               => 'user_defined',
        'default_value'                 => 'default',
        'is_unique'                     => 'unique',
        'is_global'                     => 'global',

        );  
}

function get_migration_script_for_attribute($code)
{
    //load the existing attribute model
    $m = Mage::getModel('catalog/resource_eav_attribute')
    ->loadByCode('catalog_product',$code);
    
    if(!$m->getId())
    {
        error(sprintf("Could not find attribute [%s].",$code));
    }
    //get a map of "real attribute properties to properties used in setup resource array
    $real_to_setup_key_legend = get_key_legend();

    //swap keys from above
    $data = $m->getData();
    $keys_legend = array_keys($real_to_setup_key_legend);
    $new_data    = array();
    foreach($data as $key=>$value)
    {
        if(in_array($key,$keys_legend)) 
        {
            $key = $real_to_setup_key_legend[$key];
        }
        $new_data[$key] = $value;
    }

    //unset items from model that we don't need and would be discarded by 
    //resource script anyways
    $attribute_code = $new_data['attribute_code'];
    unset($new_data['attribute_id']);
    unset($new_data['attribute_code']);
    unset($new_data['entity_type_id']);

    //chuck a few warnings out there for things that were a little murky
    if($new_data['attribute_model'])
    {
        echo "//WARNING, value detected in attribute_model.  We've never seen a value there before and this script doesn't handle it.  Caution, etc. " . "\n";
    }

    if($new_data['is_used_for_price_rules'])
    {
        echo "//WARNING, non false value detected in is_used_for_price_rules.  The setup resource migration scripts may not support this (per 1.7.0.1)" . "\n";
    }
    
    
    //load values for attributes (if any exist)
    $new_data['option'] = get_option_array_for_attribute($m);
    
    //get text for script
    $array = var_export($new_data, true);
    
    //generate script using simple string concatenation, making
    //a single tear fall down the cheek of a CS professor
    $script = "<?php
if(! (\$this instanceof Mage_Catalog_Model_Resource_Setup) )
{
    throw new Exception(\"Resource Class needs to inherit from \" .
    \"Mage_Catalog_Model_Resource_Setup for this to work\");
}

\$attr = $array;
\$this->addAttribute('catalog_product','$attribute_code',\$attr);

";
    return $script;
}

function error($error)
{
    echo $error . "\n";
    exit;
}

function usage()
{
    echo "USAGE: magento-create-setup.php attribute_code" . "\n";
}

function main($argv)
{
    $script = array_shift($argv);
    $code   = array_shift($argv);
    if(!$code)
    {
        usage();
        exit;
    }
    $script = get_migration_script_for_attribute($code);        
    echo $script;
}

bootstrap();
main($argv);