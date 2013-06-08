<?php
// returns an array of node/callback pairs
// the callback will be used to generate a 
// runtime value for the configuration node
// in non-admin pages. Swap occurs pre action
// controller dispatch, admin page is 
//     $store->isAdmin();
// The callback is a value PHP callback pseudo 
// type.  Also, before checking the callability
// of the callback, Chaos wil check a local 
// helper/domain-model for a method.  This local
// object "trumps" any PHP callback that's found
return array(
    'catalog/frontend/flat_catalog_category'=>'getRandomBoolean',
    'catalog/frontend/flat_catalog_product'=>'getRandomBoolean',
//     'design/theme/default'=>function(){
//         $themes = array('modern','default');
//         return $themes[rand(0,1)];
//     }
);