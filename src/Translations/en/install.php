<?php

return [
	'main-title'                      => 'Panic Help Desk',
	'not-yet-installed'               => '<b>Current status:</b> The package installation <b>hasn\'t finished yet</b>.',
	'installation-description'        => 'Welcome to the <b>Panic Help Desk installation menu!</b> Once you click on "Install now!" button, the following tasks will be done:',
    'initial-setup'                   => 'Initial Setup',
	'setup-list-migrations'           => '<b>:num</b> migration files will be published to Laravel app and installed',
	'setup-migrations-more-info'      => 'View migrations detail',
	'setup-migrations-less-info'      => 'Hide migrations detail',
	'setup-list-settings'             => 'PanicHD settings table will be filled up with it\'ts default values stored in SettingsTableSeeder.php',
	'setup-list-admin'                => 'Your user account (:name &lt;:email>) will be added as Panic Help Desk administrator. You may add other administrators later.',
    'setup-list-public-assets'        => 'All the imperative css, js... package files will be copied to  "public\vendor\panichd" Laravel folder.',
	'optional-config'                 => 'Optional configuration',
	'optional-quickstart-data'        => 'Insert basic parameters for a quick start: Includes some essential priorities, statuses and a new category. All of them are editable after installation.',
	'install-now'                     => 'Install now!',
	
	'master-template-file'            => 'Master template file',
    'master-template-other-path'      => 'Other path to the master template file',
    'master-template-other-path-ex'   => 'ex. views/layouts/app.blade.php',
    'migrations-to-be-installed'      => 'These migrations will be installed:',
    'all-tables-migrated'             => 'All needed tables are migrated',
    'another-file'                    => 'another File',
    'upgrade'                         => 'Panic Help Desk version upgrade', // New v0.2.3
    'settings-to-be-installed'        => 'These settings will be installed:', // New v0.2.3
    'all-settings-installed'          => 'All needed settings are installed', // New v0.2.3
];
