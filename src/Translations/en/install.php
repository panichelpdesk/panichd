<?php

return [
	'main-title'                      => 'Panic Help Desk',
	'not-yet-installed'               => '<b>Current status:</b> The package installation <b>hasn\'t finished yet</b>.',
	'welcome'                         => 'Welcome to the <b>Panic Help Desk installation menu!</b>',
	'setup-list'                      => 'Once you click on "Install now!" button, the following tasks will be done:',
    'initial-setup'                   => 'Initial Setup',
	
	'setup-list-migrations'           => '<b>:num</b> migration files will be published to Laravel app and installed',
	'setup-more-info'                 => 'View details',
	'setup-less-info'                 => 'Hide details',
	
	'setup-list-settings'             => 'PanicHD settings table will be filled up with it\'ts default values stored in SettingsTableSeeder.php',
	'setup-list-folders'              => 'The necessary folders to store tickets attachment files will be created',
	'setup-list-admin'                => 'Your user account (:name &lt;:email>) will be added as Panic Help Desk administrator. You may add other administrators later.',
    'setup-list-public-assets'        => 'All the imperative css, js... package files will be copied to  "public\vendor\panichd" Laravel folder.',
	'optional-config'                 => 'Optional configuration',
	'optional-quickstart-data'        => 'Add quickstart configuration:<ul><li>Add essential priorities, statuses and a new category.</li><li>Current user will be added as an agent on that category.</li><li>All of them are editable after installation.</li></ul>',
	'install-now'                     => 'Install now!',
	
	'just-installed'                  => 'Congratulations! You have <b>installed and configured</b> Panic Help Desk.',
	'installed-package-description'   => 'From now you may access whenever you want to <a href=":panichd">:panichd</a> to control package status.',
	'continue-to-main-menu'           => 'Continue to main menu',
	
	'package-requires-update'         => 'Panic Help Desk requires to <b>configure an upgrade</b>',
	'package-requires-update-info'    => 'The administrator has installed a Panic Help Desk upgrade but has not finished configuring it. Please wait until the process has ended',
	
	'status-out-of-date'              => '<b>Current status: Offline</b> until the the upgrade is configured',
	'about-to-update'                 => 'This is the Panic Help Desk <b>upgrade menu</b>',
	'about-to-update-description'     => 'Once you click on the "Upgrade now" button, the following changes are going to be done:',
	'all-tables-migrated'             => 'There are no pending database updates, so no migration will be executed',
	
	'choose-public-folder-action'     => 'Have you done any modifications to the files at Laravel\'s "public/vendor/panichd" folder? Do you want to backup it?',
	'public-folder-destroy'           => 'No (Recommended option)',
	'public-folder-backup'            => 'Yes. Make a directory backup before copying the new files',
	
	'upgrade-now'                     => 'Upgrade now!',
	
	'upgrade-done'                    => 'The upgrade has finished correctly',
	
		'pending-settings'                => 'There are still some <b>configurations pending</b>',
	'pending-settings-description'    => 'Before you can add new tickets, you must ensure that you have at least one status, priority, category and an agent assigned to it.',
	
	'master-template-file'            => 'Master template file',
    'master-template-other-path'      => 'Other path to the master template file',
    'master-template-other-path-ex'   => 'ex. views/layouts/app.blade.php',
    'migrations-to-be-installed'      => 'These migrations will be installed:',
    'another-file'                    => 'another File',
    'upgrade'                         => 'Panic Help Desk version upgrade', // New v0.2.3
    'settings-to-be-installed'        => 'These settings will be installed:', // New v0.2.3
    'all-settings-installed'          => 'All needed settings are installed', // New v0.2.3
];
