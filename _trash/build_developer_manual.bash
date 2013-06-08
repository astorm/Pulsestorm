#!/bin/bash

mkdir var/build

#OS X, prevent ._ files
export COPYFILE_DISABLE=true
tar -cvf var/build/Developer_Manual.tar app/code/local/Alanstormdotcom/Developermanual app/etc/modules/Alanstormdotcom_Developermanual.xml
