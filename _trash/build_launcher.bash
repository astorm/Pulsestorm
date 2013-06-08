#!/bin/bash

mkdir var/build

#OS X, prevent ._ files
export COPYFILE_DISABLE=true
tar -cvf var/build/Pulsestorm_Launcher.tar app/code/community/Pulsestorm/Launcher/ app/design/adminhtml/default/default/template/pulsestorm_launcher/ app/etc/modules/Pulsestorm_Launcher.xml skin/adminhtml/default/default/pulsestorm_launcher/

#shameful!
#cp var/build/Pulsestorm_Launcher.tar /Users/alanstorm/Documents/svn_pair/pair.websites/trunk/public_html/alanstorm.com/2012/projects/

