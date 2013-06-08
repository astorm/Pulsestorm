#!/bin/bash

mkdir var/build

#OS X, prevent ._ files
export COPYFILE_DISABLE=true
tar -cvf var/build/Pulsestorm_Chaos.tar app/code/community/Pulsestorm/Chaos app/etc/modules/Pulsestorm_Chaos.xml
