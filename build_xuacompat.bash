#!/bin/bash

mkdir var/build

#OS X, prevent ._ files
export COPYFILE_DISABLE=true
tar -cvf var/build/Pulsestorm_Modulelist.tar app/code/local/Alanstormdotcom/Xuacompatible app/etc/modules/Alanstormdotcom_Xuacompatible.xml


