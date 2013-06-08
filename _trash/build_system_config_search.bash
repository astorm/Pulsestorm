#!/bin/bash

mkdir var/build

#OS X, prevent ._ files
export COPYFILE_DISABLE=true

tar -cvf var/build/System_Config_Search.tar app/design/adminhtml/default/default/images/alanstormdotcom  app/code/local/Alanstormdotcom/Systemsearch  app/etc/modules/Alanstormdotcom_Systemsearch.xml
