<?php
return array(
'base_dir'           => '/fakehome/Documents/github/Pulsestorm/var/build',
'archive_files'      => 'Pulsestorm_Modulelist.tar',
'extension_name'     => 'Module_List',
'extension_version'  => '0.2.2',
'archive_connect'    => 'Module_List-0.2.2.tgz',
'path_output'        => '/fakehome/Pulsestorm/var/build-connect',


'stability'          => 'stable',
'license'            => 'MIT',
'channel'            => 'community',
'summary'            => 'Reports on installed Magento code modules',
'description'        => 'This extension adds a section to the Magento Admin console which lists all install code modules.  Modules are distinct from Magento Connect packages.  A Connect package may contain a module, but it not limited to a module.',
'notes'              => 'Checks for disabled local module configuration node',

'author_name'        => 'Alan Storm',
'author_user'        => 'alanstorm',
'author_email'       => 'foo@example.com',
'php_min'            => '5.2.0',
'php_max'            => '6.0.0',
'skip_version_compare' => false,
);