<?php

return [

    /*
  *  Constants
  */
    'nav-administrators'            => 'Administrators',
    'nav-agents'                    => 'Agents',
    'nav-categories'                => 'Categories',
    'nav-configuration'             => 'Configuration',
    'nav-dashboard'                 => 'Dashboard',
    'nav-dashboard-title'           => 'Administrator dashboard',
    'nav-members'                   => 'Members',
    'nav-notices'                   => 'Notices',
    'nav-priorities'                => 'Priorities',
    'nav-settings'                  => 'Settings',
    'nav-statuses'                  => 'Statuses',

    'table-hash'                    => '#',
    'table-id'                      => 'ID',
    'table-name'                    => 'Name',
    'table-create-level'            => 'Create tickets',
    'table-action'                  => 'Action',
    'table-categories'              => 'Categories',
    'table-categories-autoasg-title'=> 'New tickets automatic assignment',
    'table-email'                   => 'E-mail',
    'table-magnitude'               => 'Magnitude',
    'table-num-tickets'             => 'Tickets count',
    'table-remove-agent'            => 'Remove from agents',
    'table-remove-administrator'    => 'Remove from administrators', // New

    'table-slug'                    => 'Slug',
    'table-default'                 => 'Default Value',
    'table-value'                   => 'My Value',
    'table-lang'                    => 'Lang',
    'table-description'             => 'Description',
    'table-edit'                    => 'Edit',

    'btn-add-new'                   => 'Add new one',
    'btn-back'                      => 'Back',
    'btn-change'                    => 'Change',
    'btn-create'                    => 'Create',
    'btn-delete'                    => 'Delete',
    'btn-edit'                      => 'Edit',
    'btn-join'                      => 'Join',
    'btn-remove'                    => 'Remove',
    'btn-submit'                    => 'Submit',
    'btn-save'                      => 'Save',
    'btn-update'                    => 'Update',

    // Vocabulary
    'admin'                         => 'Admin',
    'colon'                         => ': ',
    'role'                          => 'Role',

    /* Access Levels */
    'level-1'                       => 'Everyone',
    'level-2'                       => 'assigned agents + admins.',
    'level-3'                       => 'admins.',

    /*
  *  Page specific
  */

    // $admin_route_path/dashboard
    'index-title'                         => 'Tickets System Dashboard',
    'index-empty-records'                 => 'No tickets yet',
    'index-total-tickets'                 => 'Total tickets',
    'index-newest-tickets'                => 'New tickets',
    'index-active-tickets'                => 'Active tickets',
    'index-complete-tickets'              => 'Closed tickets',
    'index-performance-indicator'         => 'Performance Indicator',
    'index-periods'                       => 'Periods',
    'index-3-months'                      => '3 months',
    'index-6-months'                      => '6 months',
    'index-12-months'                     => '12 months',
    'index-tickets-share-per-category'    => 'Tickets share per category',
    'index-tickets-share-per-agent'       => 'Tickets share per agent',
    'index-categories'                    => 'Categories',
    'index-category'                      => 'Category',
    'index-agents'                        => 'Agents',
    'index-agent'                         => 'Agent',
    'index-administrators'                => 'Administrators',
    'index-administrator'                 => 'Administrator',
    'index-users'                         => 'Users',
    'index-user'                          => 'User',
    'index-tickets'                       => 'Tickets',
    'index-open'                          => 'Open',
    'index-closed'                        => 'Closed',
    'index-total'                         => 'Total',
    'index-month'                         => 'Month',
    'index-performance-chart'             => 'How many days in average to resolve a ticket?',
    'index-categories-chart'              => 'Tickets distribution per category',
    'index-agents-chart'                  => 'Tickets distribution per Agent',
    'index-view-category-tickets'         => 'View :list tickets in :category category',
    'index-view-agent-tickets'            => 'View agent assigned :list tickets',
    'index-view-user-tickets'             => 'View user own :list tickets',

    // $admin_route_path/agent/____
    'agent-index-title'             => 'Agents Management',
    'agent-index-no-agents'         => 'There are no agents',
    'agent-index-create-new'        => 'Add agent',
    'agent-create-form-agent'       => 'User',
    'agent-create-add-agents'       => 'Add Agents',
    'agent-create-no-users'         => 'There are no user accounts, create user accounts first.',
    'agent-store-ok'                => 'User ":name" has been added to agents',
    'agent-updated-ok'              => 'Agent ":name" updated successfully',
    'agent-excluded-ok'             => 'Agent ":name" excluded from agents',

    'agent-store-error-no-category' => 'To add an agent you must check at least one category',

    // $admin_route_path/agent/edit
    'agent-edit-title'                 => 'User permissions :agent',
    'agent-edit-table-category'        => 'Category',
    'agent-edit-table-agent'           => 'Agent permission',
    'agent-edit-table-autoassign'      => 'New tickets auto.',

    // $admin_route_path/administrators/____
    'administrator-index-title'                   => 'Administrators Management',
    'btn-create-new-administrator'                => 'Create new administrator',
    'administrator-index-no-administrators'       => 'There are no administrators, ',
    'administrator-index-create-new'              => 'Add administrators',
    'administrator-create-title'                  => 'Add Administrator',
    'administrator-create-add-administrators'     => 'Add Administrators',
    'administrator-create-no-users'               => 'There are no user accounts, create user accounts first.',
    'administrator-create-select-user'            => 'Select user accounts to be added as administrators',

    // $admin_route_path/category/____
    'category-index-title'          => 'Categories Management',
    'btn-create-new-category'       => 'Create new category',
    'category-index-no-categories'  => 'There are no categories, ',
    'category-index-create-new'     => 'create new category',
    'category-index-js-delete'      => 'Are you sure you want to delete the category: ',
    'category-index-email'          => 'Notifications email',
    'category-index-reasons'        => 'Closing reasons',
    'category-index-tags'           => 'Tags',

    'category-create-title'              => 'Create New Category',
    'category-create-name'               => 'Name',
    'category-create-email'              => 'Notifications email',
    'category-email-origin'              => 'Origin',
    'category-email-origin-website'      => 'Website',
    'category-email-origin-tickets'      => 'Tickets general email',
    'category-email-origin-category'     => 'This category',
    'category-email-from'                => 'From',
    'category-email-name'                => 'Name',
    'category-email'                     => 'E-mail',
    'category-email-reply-to'            => 'Reply to',
    'category-email-default'             => 'Default',
    'category-email-this'                => 'This mailbox',
    'category-email-from-info'           => 'Mail sender used on all notifications in this category',
    'category-email-reply-to-info'       => 'Destination e-mail for notification e-mail replies',
    'category-email-reply-this-info'     => 'The one specified below',
    'category-create-color'              => 'Color',
    'category-create-new-tickets'        => 'Who may create tickets',
    'category-create-new-tickets-help'   => 'Minimum level to create tickets in category',

    'category-edit-title'           => 'Edit Category: :name',

    'category-edit-closing-reasons'      => 'Ticket closing reasons',
    'category-edit-closing-reasons-help' => 'Options that user may choose when closing ticket',
    'category-edit-reason'               => 'Closing reason',
    'category-edit-reason-label'         => 'Reason',
    'category-edit-reason-status'        => 'Status',
    'category-delete-reason'             => 'Delete reason',

    'category-edit-new-tags'        => 'New tags',
    'category-edit-current-tags'    => 'Current tags',
    'category-edit-new-tag-title'   => 'Create a new tag',
    'category-edit-new-tag-default' => 'New tag',
    'category-edit-tag'             => 'Edit tag',
    'category-edit-tag-background'  => 'Background',
    'category-edit-tag-text'        => 'Text',

    'new-tag-validation-empty'      => 'You cannot register a tag with an empty name',
    'update-tag-validation-empty'   => 'You cannot leave blank the tag name of the one previously named ":name"',

    // Category form validation
    'category-reason-is-empty'      => 'Closing reason :number has no text',
    'category-reason-too-short'     => 'Closing reason :number with name ":name" requires :min characters',
    'category-reason-no-status'     => 'Closing reason :number with name ":name" requires a defined status',

    'tag-regex'                     => '/^[A-Za-z0-9?@\/\-_\s]+$/',
    'category-tag-not-valid-format' => 'Tag ":tag" format is not valid',
    'tag-validation-two'            => 'You have introduced two tags with the same name ":name"',

    // $admin_route_path/member/____
    'member-index-title'            => 'Members management',
    'member-index-help'             => 'Members are all registered users in database. This website administrator may have filtered the list',
    'member-index-empty'            => 'No registered members were found',
    'member-table-own-tickets'      => 'Own tickets',
    'member-table-assigned-tickets' => 'Assigned tickets',
    'member-modal-update-title'     => 'Update member',
    'member-modal-create-title'     => 'Create member',
    'member-delete-confirmation'    => 'Are you sure you want to delete this user from database?',
    'member-password-label'         => 'Password',
    'member-new-password-label'     => 'New password (optional)',
    'member-password-repeat-label'  => 'Repeat password',
    'member-added-ok'               => 'Member ":name" has been created correctly',
    'member-updated-ok'             => 'Member ":name" has been updated correctly',
    'member-deleted'                => 'Member ":name" has been DELETED',
    'member-delete-own-user-error'  => 'You cannot delete your own user account',
    'member-delete-agent'           => 'To enable this member deletion, delete it\'ts agent roles first',
    'member-with-tickets-delete'    => 'You cannot delete a member with related tickets',

    // $admin_route_path/priority/____
    'priority-index-title'              => 'Priorities Management',
    'priority-index-help'               => 'You may change priority order dragging this table rows. This order will be used also in ticket list when checking this field',
    'btn-create-new-priority'           => 'Create new priority',
    'priority-index-no-priorities'      => 'There are no priorities, ',
    'priority-index-create-new'         => 'create new priority',
    'priority-index-js-delete'          => 'Are you sure you want to delete the priority: ',
    'priority-create-title'             => 'Create New Priority',
    'priority-create-name'              => 'Name',
    'priority-create-color'             => 'Color',
    'priority-edit-title'               => 'Edit priority: :name',
    'priority-delete-title'             => 'Delete priority: :name',
    'priority-delete-warning'           => 'There are <span class="modal-tickets-count"></span> tickets that use this priority. You must choose another one for all of them',
    'priority-delete-error-no-priority' => 'You have to specify a new priority for ":name" priority related tickets',

    // $admin_route_path/status/____
    'status-index-title'            => 'Statuses Management',
    'btn-create-new-status'         => 'Create new status',
    'status-index-no-statuses'      => 'There are no statues,',
    'status-index-create-new'       => 'create new status',
    'status-index-js-delete'        => 'Are you sure you want to delete the status: ',
    'status-create-title'           => 'Create New Status',
    'status-create-name'            => 'Name',
    'status-create-color'           => 'Color',
    'status-edit-title'             => 'Edit Status: :name',
    'status-delete-title'           => 'Delete status ":name"',
    'status-delete-warning'         => 'There are <span class="modal-tickets-count"></span> tickets that use this status. You must choose another one for all of them',
    'status-delete-error-no-status' => 'You have to specify a new status for ":name" status related tickets',

    // $admin_route_path/notice/____
    'notice-index-title'          => 'Notices to departments management',
    'btn-create-new-notice'       => 'Add notice',
    'notice-index-empty'          => 'There are no notices configured.',
    'notice-index-owner'          => 'Owner',
    'notice-index-email'          => 'Notice e-mail',
    'notice-index-department'     => 'Notice visible for',
    'notice-modal-title-create'   => 'Add a notice to department',
    'notice-modal-title-update'   => 'Update a notice to department',
    'notice-saved-ok'             => 'Notice saved correctly',
    'notice-deleted-ok'           => 'Notice deleted',
    'notice-index-js-delete'      => 'Are you sure you want to delete this notice?',
    'notice-index-help'           => 'When a ticket set with one of the following owners is created, there will happen two things:<br /><br /><ol><li>An e-mail will be sent to ticket <b>owner</b>, with a specific e-mail template.</li><li>As long as the ticket is <b>open</b>, users in the same department will see the ticket as a <b>notice</b> in the create ticket menu.',
    'notice-index-owner-alert'    => 'A normal user, when creating a new ticket, will not be able to see any user listed here',

    // $admin_route_path/configuration/____
    'config-index-title'            => 'Configuration Settings',
    'config-index-subtitle'         => 'Settings',
    'btn-create-new-config'         => 'Add new setting',
    'config-index-no-settings'      => 'There are no settings,',
    'config-index-initial'          => 'Initial',
    'config-index-features'         => 'Features',
    'config-index-tickets'          => 'Tickets',
    'config-index-table'            => 'Table',
    'config-index-notifications'    => 'Notifications',
    'config-index-permissions'      => 'Permissions',
    'config-index-editor'           => 'Editor', //Added: 2016.01.14
    'config-index-other'            => 'Other',
    'config-create-title'           => 'Create: New Global Setting',
    'config-create-subtitle'        => 'Create Setting',
    'config-edit-title'             => 'Edit: Global Configuration',
    'config-edit-subtitle'          => 'Edit Setting',
    'config-edit-id'                => 'ID',
    'config-edit-slug'              => 'Slug',
    'config-edit-default'           => 'Default value',
    'config-edit-value'             => 'My value',
    'config-edit-language'          => 'Language',
    'config-edit-unserialize'       => 'Get the array values, and change the values',
    'config-edit-serialize'         => 'Get the serialized string of the changed values (to be entered in the field)',
    'config-edit-should-serialize'  => 'Serialize', //Added: 2016-01-16
    'config-edit-eval-warning'      => 'When checked, the server will run eval()!
  									  Don\'t use this if eval() is disabled on your server or if you don\'t exactly know what you are doing!
  									  Exact code executed:', //Added: 2016-01-16
    'config-edit-reenter-password'  => 'Re-enter your password', //Added: 2016-01-16
    'config-edit-auth-failed'       => 'Password mismatch', //Added: 2016-01-16
    'config-edit-eval-error'        => 'Invalid value', //Added: 2016-01-16
    'config-edit-tools'             => 'Tools:',
    'config-update-confirm'         => 'Configuration :name has been updated',
    'config-delete-confirm'         => 'Configuration :name has been deleted',
];
