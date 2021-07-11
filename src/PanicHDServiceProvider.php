<?php

namespace PanicHD\PanicHD;

use Cache;
use Collective\Html\FormFacade as CollectiveForm;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use PanicHD\PanicHD\Console\Htmlify;
use PanicHD\PanicHD\Controllers\InstallController;
use PanicHD\PanicHD\Controllers\NotificationsController;
use PanicHD\PanicHD\Controllers\ToolsController;
use PanicHD\PanicHD\Models\Comment;
use PanicHD\PanicHD\Models\Setting;
use PanicHD\PanicHD\Models\Status;
use PanicHD\PanicHD\Models\Ticket;

class PanicHDServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        if (!Schema::hasTable('migrations')) {
            // Database isn't installed yet.
            return;
        }

        // Alias for Member model
        $loader = \Illuminate\Foundation\AliasLoader::getInstance();
        if (Schema::hasTable('panichd_settings') and Setting::where('slug', 'member_model_class')->count() == 1) {
            $member_model_class = Setting::grab('member_model_class');
        }

        if (!isset($member_model_class) or $member_model_class == 'default') {
            $member_model_class = Cache::remember('panichd::provider_member_class', 3600, function () {
                // Check App\Models\User existence first
                if (class_exists('\App\Models\User')) {
                    return 'PanicHD\PanicHD\Models\Member_AppModelsUser';
                } else {
                    // Inherit from App\User as default
                    return 'PanicHD\PanicHD\Models\Member_AppUser';
                }
            });
        }

        $loader->alias('PanicHDMember', $member_model_class);

        $this->loadTranslationsFrom(__DIR__.'/Translations', 'panichd');
        $this->loadViewsFrom(__DIR__.'/Views', 'panichd');

        $authMiddleware = Helpers\LaravelVersion::authMiddleware();

        Route::get('panichd', 'PanicHD\PanicHD\Controllers\InstallController@index')
            ->middleware($authMiddleware)
            ->name('panichd.install.index');

        if (Request::is('panichd') || Request::is('panichd/*')) {
            Route::post('/panichd/install', [
                'middleware' => $authMiddleware,
                'as'         => 'panichd.install.setup',
                'uses'       => 'PanicHD\PanicHD\Controllers\InstallController@setup',
            ]);

            Route::get('/panichd/upgrade', [
                'middleware' => $authMiddleware,
                'as'         => 'panichd.install.upgrade_menu',
                'uses'       => 'PanicHD\PanicHD\Controllers\InstallController@upgrade_menu',
            ]);

            Route::post('/panichd/upgrade', [
                'middleware' => $authMiddleware,
                'as'         => 'panichd.install.upgrade',
                'uses'       => 'PanicHD\PanicHD\Controllers\InstallController@upgrade',
            ]);
        }

        $this->publishes([__DIR__.'/Translations' => base_path('resources/lang/vendor/panichd')], 'panichd-lang');
        $this->publishes([__DIR__.'/Views' => base_path('resources/views/vendor/panichd')], 'panichd-views');
        $this->publishes([__DIR__.'/Public' => public_path('vendor/panichd')], 'panichd-public');
        $this->publishes([__DIR__.'/Migrations' => base_path('database/migrations')], 'panichd-db');

        $installer = new InstallController();

        // if a migration or new setting is missing scape to the installation
        if ($installer->isUpdated()) {
            // Send the Agent User model to the view under $u
            // Send settings to views under $setting

            //cache $u
            $u = null;

            view()->composer('panichd::*', function ($view) use (&$u) {
                if (auth()->check()) {
                    if ($u === null) {
                        $u = \PanicHDMember::find(auth()->user()->id);
                    }
                    $view->with('u', $u);
                }
                $setting = new Setting();
                $view->with('setting', $setting);
            });

            // Adding HTML5 color picker to form elements
            CollectiveForm::macro('custom', function ($type, $name, $value = '#000000', $options = []) {
                $field = $this->input($type, $name, $value, $options);

                return $field;
            });

            // Passing to views the master view value from the setting file
            view()->composer('panichd::*', function ($view) {
                $tools = new ToolsController();
                $master = Setting::grab('master_template');
                $email = Setting::grab('email.template');
                $editor_enabled = Setting::grab('editor_enabled');
                $codemirror_enabled = Setting::grab('editor_html_highlighter');
                $codemirror_theme = Setting::grab('codemirror_theme');
                $view->with(compact('master', 'email', 'tools', 'editor_enabled', 'codemirror_enabled', 'codemirror_theme'));
            });

            // Include $n_notices in shared.nav and tickets.createedit templates
            view()->composer(['panichd::shared.nav', 'panichd::tickets.createedit'], function ($view) {
                $n_notices = Setting::grab('departments_notices_feature') ? Ticket::active()->notHidden()->whereIn('user_id', \PanicHDMember::find(auth()->user()->id)->getMyNoticesUsers())->count() : 0;
                $view->with(compact('n_notices'));
            });

            view()->composer(['panichd::tickets.partials.summernote', 'panichd::shared.summernote'], function ($view) {
                $editor_locale = Setting::grab('summernote_locale');

                if ($editor_locale == 'laravel') {
                    $editor_locale = config('app.locale');
                }

                if (substr($editor_locale, 0, 2) == 'en') {
                    $editor_locale = null;
                } else {
                    if (strlen($editor_locale) == 2) {
                        switch ($editor_locale) {
                            case 'ca':
                                $editor_locale = 'ca-ES';
                                break;
                            case 'cs':
                                $editor_locale = 'cs-CZ';
                                break;
                            case 'da':
                                $editor_locale = 'da-DK';
                                break;
                            case 'fa':
                                $editor_locale = 'fa-IR';
                                break;
                            case 'he':
                                $editor_locale = 'he-IL';
                                break;
                            case 'ja':
                                $editor_locale = 'ja-JP';
                                break;
                            case 'ko':
                                $editor_locale = 'ko-KR';
                                break;
                            case 'nb':
                                $editor_locale = 'nb-NO';
                                break;
                            case 'sl':
                                $editor_locale = 'sl-SI';
                                break;
                            case 'sr':
                                $editor_locale = 'sr-RS';
                                break;
                            case 'sv':
                                $editor_locale = 'sv-SE';
                                break;
                            case 'uk':
                                $editor_locale = 'uk-UA';
                                break;
                            case 'vi':
                                $editor_locale = 'vi-VN';
                                break;
                            case 'zh':
                                $editor_locale = 'zh-CN';
                                break;
                            default:
                                $editor_locale = $editor_locale.'-'.strtoupper($editor_locale);
                                break;
                        }
                    }
                }

                // User level uses it's own summernote options if specified
                $user_editor = \PanicHDMember::find(auth()->user()->id)->currentLevel() < 2 ? Setting::grab('summernote_options_user') : 0;

                // Load summernote options
                // $editor_options = $user_editor != "0" ? $user_editor : file_get_contents(base_path(Setting::grab('summernote_options_json_file')));
                if ($user_editor != '0') {
                    $editor_options = $user_editor;
                } elseif (Setting::grab('summernote_options_json_file') != 'default' and file_exists(base_path(Setting::grab('summernote_options_json_file')))) {
                    $editor_options = file_get_contents(base_path(Setting::grab('summernote_options_json_file')));
                } else {
                    $editor_options = file_get_contents(realpath(__DIR__).'/JSON/summernote_init.json');
                }

                $view->with(compact('editor_locale', 'editor_options'));
            });

            // Change agent modal
            view()->composer('panichd::tickets.partials.modal_agent', function ($view) {
                $status_check_name = Setting::grab('default_reopen_status_id') == '0' ? Status::first()->name : Status::find(Setting::grab('default_reopen_status_id'))->name;
                $view->with(compact('status_check_name'));
            });

            // Notices widget
            view()->composer(['panichd::notices.widget', 'panichd::notices.index'], function ($view) {
                if (Setting::grab('departments_notices_feature')) {
                    // Get available notice users list
                    if (is_null(auth()->user())) {
                        $all_dept_users = \PanicHDMember::where('ticketit_department', '0');
                        if (version_compare(app()->version(), '5.3.0', '>=')) {
                            $a_notice_users = $all_dept_users->pluck('id')->toArray();
                        } else {
                            $a_notice_users = $all_dept_users->lists('id')->toArray();
                        }
                    } else {
                        $a_notice_users = \PanicHDMember::find(auth()->user()->id)->getMyNoticesUsers();
                    }

                    // Get notices
                    $a_notices = Ticket::active()->notHidden()->whereIn('user_id', $a_notice_users)
                        ->join('panichd_priorities', 'priority_id', '=', 'panichd_priorities.id')
                        ->select('panichd_tickets.*')
                        ->with('owner.department')
                        ->with('status')->with('tags')
                        ->withCount('allAttachments')
                        ->orderByRaw('CASE when status_id="'.Setting::grab('default_close_status_id').'" then 2 else 1 end')
                        ->orderByRaw('date(start_date)')
                        ->orderBy('panichd_priorities.magnitude', 'desc')
                        ->orderBy('start_date', 'asc')
                        ->get();
                } else {
                    // Don't show notices
                    $a_notices = [];
                }

                $n_notices = $a_notices ? $a_notices->count() : 0;

                $view->with(compact('a_notices', 'n_notices'));
            });

            // Send notification when comment is modified
            Event::listen('PanicHD\PanicHD\Events\CommentUpdated', function ($event) {
                $original = $event->original;
                $modified = $event->modified;

                $notification = new NotificationsController($modified->ticket->category);
                $notification->commentUpdate($original, $modified);
            });

            // Send notification when new comment is added
            Event::listen('PanicHD\PanicHD\Events\CommentCreated', function ($event) {
                if (Setting::grab('comment_notification')) {
                    $comment = $event->comment;
                    $notification = new NotificationsController($comment->ticket->category);
                    $notification->newComment($comment, $event->request);
                }
            });

            Ticket::saved(function ($ticket) {
                Cache::forget('panichd::a_complete_ticket_year_counts');
            });
            Ticket::deleted(function ($ticket) {
                Cache::forget('panichd::a_complete_ticket_year_counts');
            });

            Event::listen('PanicHD\PanicHD\Events\TicketUpdated', function ($event) {
                $original = $event->original;
                $modified = $event->modified;

                // Send notification when ticket status is modified or ticket is closed
                if (Setting::grab('status_notification')) {
                    if (!strtotime($original->completed_at) and strtotime($modified->completed_at)) {

                        // Notificate closed ticket
                        $notification = new NotificationsController($modified->category);
                        $notification->ticketClosed($original, $modified);
                    } elseif ($original->status_id != $modified->status_id) {

                        // Notificate updated status
                        $notification = new NotificationsController($modified->category);
                        $notification->ticketStatusUpdated($original, $modified);
                    }
                }

                // Send notification when agent is modified
                if (Setting::grab('assigned_notification')) {
                    if ($original->agent->id != $modified->agent->id) {
                        $notification = new NotificationsController($modified->category);
                        $notification->ticketAgentUpdated($original, $modified);
                    }
                }

                return true;
            });

            // Send notification when ticket is created
            Event::listen('PanicHD\PanicHD\Events\TicketCreated', function ($event) {
                if (Setting::grab('assigned_notification')) {
                    $notification = new NotificationsController($event->ticket->category);
                    $notification->newTicket($event->ticket);
                }

                return true;
            });

            $main_route = Setting::grab('main_route');
            $main_route_path = Setting::grab('main_route_path');
            $admin_route = Setting::grab('admin_route');
            $admin_route_path = Setting::grab('admin_route_path');

            include __DIR__.'/routes.php';

            if ($this->app->runningInConsole()) {
                $this->commands([
                    Console\DemoRollback::class,
                    Console\WipeOffLists::class,
                    Console\WipeOffTickets::class,
                ]);
            }
        } else {
            Route::get('/tickets/{params?}', function () {
                return redirect()->route('panichd.install.index');
            })->where('params', '(.*)');

            Route::get('/panichd/{menu?}', function () {
                return redirect()->route('panichd.install.index');
            })->where('menu', '(.*)');
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        /*
         * Register the service provider for the dependency.
         */
        $this->app->register(\Collective\Html\HtmlServiceProvider::class);
        $this->app->register(\Intervention\Image\ImageServiceProvider::class);
        $this->app->register(\Jenssegers\Date\DateServiceProvider::class);
        $this->app->register(\Mews\Purifier\PurifierServiceProvider::class);
        if (class_exists('\Yajra\DataTables\DataTablesServiceProvider')) {
            $this->app->register(\Yajra\DataTables\DataTablesServiceProvider::class);
        } else {
            $this->app->register(\Yajra\Datatables\DatatablesServiceProvider::class);
        }

        /*
         * Create aliases for the dependency.
         */
        $loader = \Illuminate\Foundation\AliasLoader::getInstance();
        $loader->alias('CollectiveForm', 'Collective\Html\FormFacade');
        $loader->alias('CollectiveHtml', 'Collective\Html\HtmlFacade');
        $loader->alias('Image', 'Intervention\Image\Facades\Image');

        /*
         * Register htmlify command. Need to run this when upgrading from <=0.2.2
         */

        $this->app->singleton('command.panichd.panichd.htmlify', function ($app) {
            return new Htmlify();
        });
        $this->commands('command.panichd.panichd.htmlify');
    }
}
