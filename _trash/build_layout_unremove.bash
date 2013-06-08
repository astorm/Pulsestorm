#!/bin/bash

mkdir var/build

#OS X, prevent ._ files
export COPYFILE_DISABLE=true
tar -cvf var/build/Layout_Unremove.tar app/code/local/Alanstormdotcom/Unremove app/etc/modules/Alanstormdotcom_Unremove.xml
