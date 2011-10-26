#!/bin/bash

mkdir var/build

#OS X, prevent ._ files
export COPYFILE_DISABLE=true
tar -cvf var/build/Layout_Viewer.tar app/code/local/Alanstormdotcom/Layoutviewer app/etc/modules/Alanstormdotcom_Layoutviewer.xml
