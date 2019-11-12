<?php

/*
 * Setting descriptions
 *
 * See Seeds/SettingsTableSeeder.php
 */

$codemirrorVersion = PanicHD\PanicHD\Helpers\Cdn::CodeMirror;
$summernoteVersion = PanicHD\PanicHD\Helpers\Cdn::Summernote;

return [
    // init
    'main_route'         => 'PanicHD prefix used in Laravel route names (ex. route(\'<code>tickets</code>.index\'))',
    'main_route_path'    => 'URL prefix to see tickets (ex. http://hostname/<code>tickets</code>/)',
    'admin_route'        => 'PanicHD configuration menues prefix for Laravel route names (ex. route(\'<code>panichd</code>.status.index\')). Dashboard doesn\'t use it because it has it\'ts own route name "dashboard"',
    'admin_route_path'   => 'URL prefix for PanicHD dashboard and configuration menues (ex. http://url/<code>panichd</code>/priority)',
    'master_template'    => 'The blade template that all PanicHD views will extend',
    'user_route'         => 'Route name for Member pages. If configured, any Member name within a view will have a link to it\'s own page<br />The <b>used parameter</b> for this route is "<b>user</b>"',
    'member_model_class' => 'PanicHD "Member" model <b>full</b> namespace. Default is <code>PanicHD\PanicHD\Models\Member</code> and it\'s loaded as <code>\PanicHDMember</code>',
    'admin_button_text'  => 'PanicHD cofiguration nav menu name',

    // features
    'departments_feature'         => 'View Member department related information. This feature is under development <a href="https://github.com/panichelpdesk/panichd/wiki/Under-development">as described in our wiki</a><br /><code>0</code>: disabled<br /><code>1</code>: enabled',
    'departments_notices_feature' => 'Ability to link specific Members to a noticed department. If a ticket is created with any of these special users as an owner, all related department members will see that ticket as a Notice. This feature is under development <a href="https://github.com/panichelpdesk/panichd/wiki/Under-development">as described in our wiki</a><br /><code>0</code>: disabled<br /><code>1</code>: enabled',
    'ticket_attachments_feature'  => 'Ability to attach files to tickets and/or comments. <br /><code>0</code>: disabled<br /><code>1</code>: enabled',

    // table
    'paginate_items'            => 'Default table row number',
    'length_menu'               => 'Options for table pagination menu',
    'max_agent_buttons'         => 'Max agent number that will be shown as separate butttons in filter panel. If there are more agents available, they will be shown as a select list',
    'subject_content_column'    => 'Group subject and content column in table<br /><code>0</code>: disabled<br /><code>1</code>: enabled',
    'calendar_month_filter'     => 'View calendar filter available options by calendar periods (week, month).<br /><code>0</code>: View options by day counts (7 dies, 14 dies)<br /><code>1</code>: View options by calendar periods (week, month)',
    'list_text_max_length'      => 'Max visible length for description and intervention fields. If any of these is bigger than this setting, the text will be cutted to this length and a button to view full text will be shown<br /><code>0</code>: Disable',
    'check_last_update_seconds' => 'Interval in seconds in which an AJAX last updated ticket check will be done to trigger data reload',

    // tickets
    'default_status_id'                => 'The default status for new tickets',
    'default_close_status_id'          => 'The default ticket closing status',
    'default_reopen_status_id'         => 'The default ticket reopening status',
    'delete_modal_type'                => 'Choose which confirmation message type to use when confirming a deleting<br /><code>builtin</code>: javascript confirmation<br /><code>modal</code>: jquery modal message',
    'attachments_path'                 => 'Subfolder inside <b>storage</b> where to save attached files',
    'attachments_ticket_max_size'      => 'Max size in <b>MB</b> for all attachments in a single ticket, including comments',
    'attachments_ticket_max_files_num' => 'Max number of attachments in a single ticket including comments',
    'attachments_mimes'                => 'List of allowed attachment file extensions separated by comma',
    'thumbnails_path'                  => 'Subfolder within "storage\\app\\public" where to save attached images thumbnails',
    'oldest_year'                      => 'Allowed oldest year for ticket start date or limit date',
    'html_replacements'                => 'Automatic string replacements for content and intervention HTML fields',
    'default_priority_id'              => 'The default priority for new tickets',
    'use_default_status_id'            => 'Whether to allow or not the default_status_id to be assignable in any ticket',

    // notifications
    'email.template'                 => 'The email blade template that all notifications extend',
    'comment_notification'           => 'Send notification when new comment is posted<br /><code>1</code>: Send notification<br /><code>0</code>: Do not send notification',
    'queue_emails'                   => 'Use Queue method when sending emails (Mail::queue).<br /><code>0</code>: Notificate via Mail::send<br /><code>1</code>: Notificate via Laravel Mail::queue. Note that it requires to be configured <a target="_blank" href="http://laravel.com/docs/master/queues">in Laravel</a>.',
    'assigned_notification'          => 'Send notification to any newly assigned agent<br /><code>0</code>: Don\'t send notification<br /><code>1</code>: Send notification',
    'email.account.name'             => 'The email sender name for all PanicHD notifications<br /><code>default</code>: Use Laravel defaults',
    'email.account.mailbox'          => 'The email address for all PanicHD notifications<br /><code>default</code>: Use Laravel defaults',
    'list_owner_notification'        => 'Notify owner when ticket list changes from active to complete or vice versa<br /><code>0</code>: disabled<br /><code>1</code>: enabled',
    'status_owner_notification'      => 'Notify owner when ticket status changes<br /><code>0</code>: disabled<br /><code>1</code>: enabled. Requires <b>status_notification</b> setting to be enabled too',
    'custom_recipients'              => 'Show option in a comment form to select one or many custom recipients for it',
    'status_notification'            => 'Send email notification to ticket owner/agent when ticket status is changed<br /><code>1</code>: Send notification<br /><code>0</code>: Do not send notification',
    'email.owner.newticket.template' => 'Notice notification to ticket owner uses the email blade template specified here',

    // TODO: Delete deprecated settings email.*
    'email.header' => '<p><img src="http://i.imgur.com/5aJjuZL.jpg"/></p>', 'email.signoff' => '<p><img src="http://i.imgur.com/jONMwgF.jpg"/></p>', 'email.signature' => '<p><img src="http://i.imgur.com/coi3R63.jpg"/></p>', 'email.dashboard' => '<p><img src="http://i.imgur.com/qzNzJD4.jpg"/></p>', 'email.google_plus_link' => '<p><b>Toogle icon link</b>: empty or string</p><p><img src="http://i.imgur.com/fzyxfSg.jpg"/></p>', 'email.facebook_link' => '<p><b>Toogle icon link</b>: empty or string</p><p><img src="http://i.imgur.com/FQQzr98.jpg"/></p>', 'email.twitter_link' => '<p><b>Toogle icon link</b>: empty or string</p><p><img src="http://i.imgur.com/5JmkrF1.jpg"/></p>', 'email.footer' => '', 'email.footer_link' => '', 'email.color_body_bg' => '<p><img src="http://i.imgur.com/KTF7rEJ.jpg"/></p>', 'email.color_header_bg' => '<p><img src="http://i.imgur.com/wenw5H5.jpg"/></p>', 'email.color_content_bg' => '<p><img src="http://i.imgur.com/7r8dAFj.jpg"/></p>', 'email.color_footer_bg' => '<p><img src="http://i.imgur.com/KTjkdSN.jpg"/></p>', 'email.color_button_bg' => '<p><img src="http://i.imgur.com/0TbGIyt.jpg"/></p>',

    // permissions
    'agent_restrict'     => 'Restrict agents access to only their assigned tickets<br /><code>0</code>: disabled<br /><code>1</code>: enable restricted access',
    'close_ticket_perm'  => 'Array specifying which member types may <b>close</b> a ticket',
    'reopen_ticket_perm' => 'Array specifying which member types may <b>reopen</b> a ticket',

    // editor
    'editor_enabled'               => 'Enable summernote editor on textareas',
    'summernote_locale'            => 'Which language should summernote js texteditor use. If value is <code>laravel</code>, locale set in <code>config/app.php</code> will be used<br /><br />Example: <code>hu-HU</code> for Hungarian. <a target="_blank" href="https://github.com/summernote/summernote/tree/master/lang">See available language codes</a>',
    'editor_html_highlighter'      => 'Whether include <a target="_blank" href="http://summernote.org/examples/#codemirror-as-codeview">codemirror syntax highlighter</a> or not<br /><code>0</code>: Don\'t include<br /><code>1</code>: Include',
    'codemirror_theme'             => '<p>Theme for <b>codemirror</b> syntax highlighter</p><a target="_blank" href="https://cdnjs.com/libraries/codemirror/$codemirrorVersion">View available themes</a>',
    'summernote_options_json_file' => 'App relative path for file that contains init values for summernote in JSON. <a target="_blank" href="http://summernote.org/deep-dive/#initialization-options">See avaiable options</a><br /><code>default</code>: Use default options',
    'purifier_config'              => <<<'ENDHTML'
			<p>Set which HTML tags are allowed</p>
			<p>
				Configuring this parameter overrides the settings in <a target="_blank" href="https://github.com/mewebstudio/Purifier/blob/master/config/purifier.php">Purifier config file</a><br>
				The same config can be achived by running <code>php artisan vendor:publish</code> and modifying <code>config/purifier.php</code>
			</p>

			<p><a target="_blank" href="http://htmlpurifier.org/docs">Purifier documentation</a></p>
ENDHTML
    , 'summernote_options_user' => 'Member without current permissions uses it\'s own summernote options if specified in this setting<br /><code>default</code>: Summernote default options',
];
