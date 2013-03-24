#!/bin/bash

mkdir var/build

#OS X, prevent ._ files
export COPYFILE_DISABLE=true
tar -cvf var/build/Pulsestorm_Customerpage.tar app/code/community/Pulsestorm/Customerpage/Helper/Data.php app/code/community/Pulsestorm/Customerpage/controllers/IndexController.php app/code/community/Pulsestorm/Customerpage/etc/config.xml app/design/frontend/base/default/layout/pulsestorm_customerpage.xml app/design/frontend/base/default/template/pulsestorm_customerpage.phtml app/etc/modules/Pulsestorm_Customerpage.xml 