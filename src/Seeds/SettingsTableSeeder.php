<?php

namespace PanicHD\PanicHD\Seeds;

use Illuminate\Database\Seeder;
use PanicHD\PanicHD\Models\Setting;

class SettingsTableSeeder extends Seeder
{
    public $config = [];

    /**
     * Seed the Plans table.
     */
    public function run()
    {
        $defaults = [];

        $defaults = $this->cleanupAndMerge($this->getDefaults(), $this->config);

        foreach ($defaults as $slug => $column) {
            $setting = Setting::bySlug($slug);

            if ($setting->count()) {
                $setting->first()->update([
                    'default' => $column,
                ]);
            } else {
                Setting::create([
                    'lang'    => null,
                    'slug'    => $slug,
                    'value'   => $column,
                    'default' => $column,
                ]);
            }
        }
    }

    /**
     * Takes config/ticketit.php, merge with package defaults, and returns serialized array.
     *
     * @param $defaults
     * @param $config
     *
     * @return array
     */
    public function cleanupAndMerge($defaults, $config)
    {
        $merged = array_merge($defaults, $config);

        foreach ($merged as $slug => $column) {
            if (is_array($column)) {
                foreach ($column as $key => $value) {
                    if ($value == 'yes') {
                        $merged[$slug][$key] = true;
                    }

                    if ($value == 'no') {
                        $merged[$slug][$key] = false;
                    }
                }

                $merged[$slug] = serialize($merged[$slug]);
            }

            if ($column == 'yes') {
                $merged[$slug] = true;
            }

            if ($column == 'no') {
                $merged[$slug] = false;
            }
        }

        return (array) $merged;
    }

    public function getDefaults()
    {
        return [

            /*
             * Panic Help Desk tickets route: Where to load the ticket list (ex. http://url/tickets)
             * Default: /tickets
             */
            'main_route'      => 'tickets',
            'main_route_path' => 'tickets',
            /*
             * Panic Help Desk admin route: Where to load all the administration menusd (ex. http://url/panichd/category)
             * Default: /panichd
             */
            'admin_route'      => 'panichd',
            'admin_route_path' => 'panichd',
            /*
             * Template adherence: The master blade template to be extended
             * Default: panichd::master
             */
            'master_template' => 'panichd::master',
			
			/*
			 * Default Member model for PanicHD
			 * Default: default (which means using PanicHD\PanicHD\Models\Member)
			 */
			'member_model_class' => 'default',
			 
			
			/*
			 * Admin navbar button text is configurable
			*/
			'admin_button_text' => 'PanicHD',
			
            /*
			 * Tickets email account: The email address for all package notifications.
			 * Overrides Laravel email and name but uses it's connection parameters
			 * Default: use Laravel config('mail.from.name') and config('mail.from.address') parameters
			*/
			'email.account.name' => 'default',
			'email.account.mailbox' => 'default',
			
			/*
             * Template adherence: The email blade template to be extended
             * Default: panichd::emails.templates.panichd
             */
            'email.template' => 'panichd::emails.templates.panichd',
			'email.owner.newticket.template' => 'panichd::emails.templates.simple',
            // resources/views/emails/templates/ticketit.blade.php
            'email.header'           => 'Ticket Update',
            'email.signoff'          => 'Thank you for your patience!',
            'email.signature'        => 'Your friends',
            'email.dashboard'        => 'My Dashboard',
            'email.google_plus_link' => '#', // Toogle icon link: false or string
            'email.facebook_link'    => '#', // Toogle icon link: false or string
            'email.twitter_link'     => '#', // Toogle icon link: false or string
            'email.footer'           => 'Powered by Ticketit',
            'email.footer_link'      => 'https://github.com/panichelpdesk/panichd',
            'email.color_body_bg'    => '#FFFFFF',
            'email.color_header_bg'  => '#44B7B7',
            'email.color_content_bg' => '#F46B45',
            'email.color_footer_bg'  => '#414141',
            'email.color_button_bg'  => '#AC4D2F',
			
			
			/**
			 * Configurable notifications.
			*/
			
			/*
             * Agent notify: To notify assigned agent (either auto or manual assignment) of new assigned or transferred tickets
             * Default: 'yes'
             * not to notify agent: 'no'
             */
            'assigned_notification' => 'yes',
			
			/*
             * Comment notification: Send notification when new comment is posted
             * Default is send notification: 'yes'
             * Do not send notification: 'no'
             */
            'comment_notification' => 'yes',
			
			/*
             * Status notification: Send email notification to ticket owner/Agent when ticket status is changed
             * Default: 'yes'
             */
            'status_notification' => 'yes',
			
			/*
			* Notify owner when ticket list changes (between active and complete only)
			* Default: 'yes'
            */			
			'list_owner_notification' => 'yes',

			/*
			* Notify owner when ticket status changes
			* Default: 'yes'
            */
			'status_owner_notification' => 'yes',
			
			
			/*
             * The default priority for new tickets
             * Default: 1
             */
            'default_priority_id' => 1,
            /*
             * The default status for new tickets
             * Default: 1
             */
            'default_status_id' => 1,
            /*
             * The default closing status
             * Default: false
             */
            'default_close_status_id' => false,
            /*
             * The default reopening status
             * Default: false
             */
            'default_reopen_status_id' => false,
            /*
             * [Deprecated] User ids who are members of admin role
             * Default: 1
             */
//            'admin_ids' => [1],
            /*
             * Pagination length: For standard pagination.
             * Default: 1
             */
            'paginate_items' => 10,
			
			/*
			 * Ticket list: View combined column for subject and content?
			 * Default: 'no'
			*/
			'subject_content_column' => 'no',
			
			
			/*
			 * Max total size for attached files (ticket + comments)
			 *
			 * Default: 2 (Size in MegaBytes)
			 */
			'attachments_ticket_max_size' => '2',
			/*
			 * Max total file number for a ticket (ticket + comments)
			 *
			*/
			'attachments_ticket_max_files_num' => '20',
			
			/**
			 * Attachment allowed mimetypes
			 *
			 * Default: jpg,jpeg,png,gif,doc,docx,rtf,xls,xlsx,pdf
			*/
			'attachments_mimes' => 'jpg,jpeg,png,gif,doc,docx,rtf,xls,xlsx,pdf',
			
			/*
             * Defines relative path under storage_path() where to store attached files
             *
             * Default: <storage_path>/panichd_attachments
             */
            'attachments_path' => 'panichd_attachments',
			'thumbnails_path' => 'panichd_thumbnails',
			
			/*
			 * Oldest year for ticket start date or limit date
			*/
			'oldest_year' => '2017',
			
			/*
			 * String replacements to execute within Purifiable trait before clean() method
			*/
			'html_replacements' => [
				'</p><p>' => '</p><p> ',
				'<br />' => '<br>',
				'<br>' => ' '
			],
			
			
			
            /*
             * Pagination length: For tickets table.
             * Default: 1
             */
            'length_menu' => [[10, 50, 100], [10, 50, 100]],
            
			
            /*
             * Use Queue method when sending emails (Mail::queue instead of Mail::send). Note that Mail::queue needs to be
             * configured first http://laravel.com/docs/5.1/queues
             * Default is to not use queue: 'no'
             * use queue: 'yes'
             */
            'queue_emails' => 'no',
            
            /*
             * Agent restrict: Restrict agents access to only their assigned tickets
             * Default: 'no'
             * Agent access only assigned tickets: 'yes'
             */
            'agent_restrict' => 'no',
            /*
             * Close Ticket Perm: Who has a permission to close tickets
             * Default: ['owner' => 'yes', 'agent' => 'yes', 'admin' => 'yes']
             */
            'close_ticket_perm' => ['owner' => 'yes', 'agent' => 'yes', 'admin' => 'yes'],
            /*
             * Reopen Ticket Perm: Who has a permission to reopen tickets
             * Default: ['owner' => 'yes', 'agent' => 'yes', 'admin' => 'yes']
             */
            'reopen_ticket_perm' => ['owner' => 'yes', 'agent' => 'yes', 'admin' => 'yes'],
            /*
             * Delete Confirmation: Choose which confirmation message type to use when confirming a deleting
             * Default: builtin
             * Options: builtin, modal
             */
            'delete_modal_type' => 'builtin',

            /* ------------------ JS EDITOR ------------------ */

            /*
             * Enable summernote editor on textareas
             * Default: yes
             */
            'editor_enabled' => 'yes',

            /*
             * If Font-awesome css is included outside Panic Help Desk, this should be set to 'no'
             * Default: 'yes'
             */
            'include_font_awesome' => 'yes',

            /*
             * Which language should summernote js texteditor use
             * If value is 'laravel', locale set in config/app.php will be used
             *
             * Example: 'hu-HU' for Hungarian
             *
             * See available language codes here: https://cdnjs.com/libraries/summernote/0.7.3
             *
             * Default: 'en'
             */
            'summernote_locale' => 'en',

            /*
             * Whether include codemirror sytax highlighter or not
             * http://summernote.org/examples/#codemirror-as-codeview
             *
             * Default: 'yes'
             */

            'editor_html_highlighter' => 'yes',

            /*
             * Theme for sytax highlighter
             *
             * Available themes here: https://cdnjs.com/libraries/codemirror/5.10.0
             *
             * Default: 'monikai'
             */
            'codemirror_theme' => 'monokai',

            /*
             * Init values for summernote js texteditor in JSON
             * See avaiable options here: http://summernote.org/deep-dive/#initialization-options
             *
             * This setting stores the path to the json config file, relative to project route
             */
            'summernote_options_json_file' => 'default',
			/* User role specific summernote options json string */
			'summernote_options_user' => 'no',

            /*
             * Set which html tags are allowed
             *
             * This overrides the settings part of this file: https://github.com/mewebstudio/Purifier/blob/master/config/purifier.php
             * The same config can be achived by running php artisan vendor:publish and modifying config/purifier.php
             *
             * Full docs: http://htmlpurifier.org/docs
             */

            'purifier_config' => [
                'HTML.SafeIframe'      => 'true',
                'URI.SafeIframeRegexp' => '%^(http://|https://|//)(www.youtube.com/embed/|player.vimeo.com/video/)%',
                'URI.AllowedSchemes'   => ['data' => true, 'http' => true, 'https' => true, 'mailto' => true, 'ftp' => true],
            ],
			
			/*
			 * Panic Help Desk optional features
			*/
			
			/*
			 * View department and sub1 where ticket owner belongs
			*/
			'departments_feature' => 'no',	
			
			/*
			 * This feature represents two connected functionalities:
			 * - Ability of associate certain users to departments.
			 * - On new ticket menu: User department's associated users open tickets will be shown on special panel called "Notices"
			*/
			'departments_notices_feature' => 'no',
			
			/**
			 * Allow file attachments for tickets and comments
			*/
			'ticket_attachments_feature' => 'yes',
			
			/*
			 * Bootstrap widths in where specified navigation elements get text replaced with an icon
			*/
			'nav_icons_user_sizes' => 'sm',
			'nav_icons_admin_sizes' => 'sm,md',
			
			/*
			 * Calendar filter options switch between week and month or 7 and 14 days
			*/
			'calendar_month_filter' => 'no',
			
			/*
			 * Max number of agent specific buttons in filter panel. If agent count is bigger, select2 will be shown
			*/
			'max_agent_buttons' => '4',
			
			/**
			 * User card route name if it exists in your app. Configuring it makes Owner name link to it's card by passing user id.
			 *
             * Default: 'disabled'
			*/
			'user_route' => 'disabled',
        ];
    }
}
