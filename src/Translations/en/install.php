<?php

return [
    'main-title'                      => 'Panic Help Desk',

    'not-ready-to-install'            => 'Package not yet installable!',
    'ticketit-still-installed'        => 'The Kordy/Ticketit package is still installed. To continue, please follow the uninstallation steps specified at <a href=":link">:link</a>',

    'not-yet-installed'               => '<b>Current status:</b> The package installation <b>hasn\'t finished yet</b>.',
    'welcome'                         => 'Welcome to the <b>Panic Help Desk installation menu!</b>',
    'setup-list'                      => 'Once you click on "Install now!" button, the following tasks will be done:',
    'initial-setup'                   => 'Initial Setup',

    'setup-list-migrations'           => '<b>:num</b> migration files will be published to Laravel app and installed',
    'setup-more-info'                 => 'View details',
    'setup-less-info'                 => 'Hide details',

    'setup-list-ticketit-settings'    => 'There will changes on some incompatible Kordy/Ticketit parameters',
    'setup-list-ticketit-admin_route' => 'Administration route related parameters will be set to default: "admin_route" and "admin_route_path" ',
    'setup-list-ticketit-template'    => 'Main template file will be set to default: "master_template"',
    'setup-list-ticketit-routes'      => '"routes" setting will be deleted',
    'setup-list-settings'             => 'PanicHD settings table will be filled up with it\'s default values stored in SettingsTableSeeder.php',
    'setup-list-folders'              => 'The necessary folders to store tickets attachment files will be created',
    'setup-list-admin'                => 'Your user account (:name &lt;:email>) will be added as Panic Help Desk administrator. You may add other administrators later.',
    'setup-list-public-assets'        => 'All the required css, js... package files will be copied to  "public\vendor\panichd" Laravel folder.',
    'configurable-parameters'         => 'Configurable parameters',
    'existent-parameters'             => 'There are already configured parameters from Kordy/Ticketit, so no quickstart options will be shown here.<br />To edit them, please access administration menues after Panic Help Desk installation.',
    'optional-quickstart-data'        => 'Add quickstart configuration:<ul><li>Add essential priorities, statuses and a new category.</li><li>Current user will be added as an agent on that category.</li><li>All of them are editable after installation.</li></ul>',
    'install-now'                     => 'Install now!',

    'just-installed'                  => 'Congratulations! You have <b>installed and configured</b> Panic Help Desk.',
    'installed-package-description'   => 'From now you may access whenever you want to <a href=":panichd">:panichd</a> to control package status.',
    'continue-to-main-menu'           => 'Continue to main menu',

    'package-requires-update'         => 'Panic Help Desk requires to <b>configure an upgrade</b>',
    'package-requires-update-info'    => 'The administrator has installed a Panic Help Desk upgrade but has not finished configuring it. Please wait until the process has ended',

    'status-updated'                  => 'The package is up to date and <b>Online</b> right now',
    'status-out-of-date'              => '<b>Current status: Offline</b> until the the upgrade is configured',
    'about-to-update'                 => 'Upgrade menu',
    'about-to-update-description'     => 'Once you click on the "Upgrade now" button, the following changes are going to be done:',
    'all-tables-migrated'             => 'All database modifications are already installed. No changes to do here',

    'optional-config'                 => 'Optional configuration',
    'choose-public-folder-action'     => 'Do you want to backup "public/vendor/panichd" folder before it is reinstalled? (Needed if you have you done any modifications on it\'s files)',
    'public-folder-destroy'           => 'No',
    'public-folder-backup'            => 'Yes (A backup folder will be created inside "public/vendor")',

    'upgrade-now'                     => 'Upgrade now!',

    'upgrade-done'                    => 'The upgrade has finished correctly',

    'pending-settings'                => 'There are still some <b>configurations pending</b>',
    'pending-settings-description'    => 'Before you can add new tickets, you must ensure that you have at least one status, priority, category and an agent assigned to it.',

    'master-template-file'            => 'Master template file',
    'master-template-other-path'      => 'Other path to the master template file',
    'master-template-other-path-ex'   => 'ex. views/layouts/app.blade.php',
    'migrations-to-be-installed'      => 'These migrations will be installed:',
    'another-file'                    => 'another File',
    'upgrade'                         => 'Panic Help Desk version upgrade',
    'settings-to-be-installed'        => 'These settings will be installed:',
    'all-settings-installed'          => 'All needed configuration settings are registered. No changes to do here',
    'public-folder-will-be-replaced'  => 'The public/vendor/panichd folder content will be <b>deleted and reinstalled</b>',
];
