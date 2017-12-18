<?php

namespace PanicHD\PanicHD;

use Collective\Html\FormFacade as CollectiveForm;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use PanicHD\PanicHD\Console\Htmlify;
use PanicHD\PanicHD\Controllers\InstallController;
use PanicHD\PanicHD\Controllers\NotificationsController;
use PanicHD\PanicHD\Controllers\ToolsController;
use PanicHD\PanicHD\Helpers\LaravelVersion;
use PanicHD\PanicHD\Models\Agent;
use PanicHD\PanicHD\Models\Comment;
use PanicHD\PanicHD\Models\Setting;
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
        
		$this->publishes([__DIR__.'/Translations' => base_path('resources/lang/vendor/panichd')], 'panichd-lang');
		$this->publishes([__DIR__.'/Views' => base_path('resources/views/vendor/panichd')], 'panichd-views');
        $this->publishes([__DIR__.'/Public' => public_path('vendor/panichd')], 'panichd-public');
        $this->publishes([__DIR__.'/Migrations' => base_path('database/migrations')], 'panichd-db');
		
		$installer = new InstallController();
		
        // if a migration or new setting is missing scape to the installation
        if (empty($installer->inactiveMigrations()) && !$installer->inactiveSettings()) {
            // Send the Agent User model to the view under $u
            // Send settings to views under $setting

            //cache $u
            $u = null;

            view()->composer('panichd::*', function ($view) use (&$u) {
                if (auth()->check()) {
                    if ($u === null) {
                        $u = Agent::find(auth()->user()->id);
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

            //inlude font awesome css or not
            view()->composer('panichd::shared.assets', function ($view) {
                $include_font_awesome = Setting::grab('include_font_awesome');
                $view->with(compact('include_font_awesome'));
            });

            view()->composer('panichd::tickets.partials.summernote', function ($view) {
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
				$user_editor = Agent::find(auth()->user()->id)->currentLevel() < 2 ? Setting::grab('summernote_options_user') : 0;


				// Load summernote options
				# $editor_options = $user_editor != "0" ? $user_editor : file_get_contents(base_path(Setting::grab('summernote_options_json_file')));
				if($user_editor != "0"){
					$editor_options = $user_editor;
				}elseif(Setting::grab('summernote_options_json_file') == 'default'){
					$editor_options = file_get_contents(realpath(__DIR__).'/JSON/summernote_init.json');
				}else{
					$editor_options = file_get_contents(base_path(Setting::grab('summernote_options_json_file')));
				}
				
                $view->with(compact('editor_locale', 'editor_options'));
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
                    $notification->newComment($comment);
                }
            });

            Event::listen('PanicHD\PanicHD\Events\TicketUpdated', function ($event) {
                $original = $event->original;
				$modified = $event->modified;
				
				// Send notification when ticket status is modified or ticket is closed
				if (Setting::grab('status_notification')) {
                    if ($original->status_id != $modified->status_id || $original->completed_at != $modified->completed_at) {
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

            $this->loadTranslationsFrom(__DIR__.'/Translations', 'panichd');
            $this->loadViewsFrom(__DIR__.'/Views', 'panichd');

            // Check public assets are present, publish them if not
//            $installer->publicAssets();

            $main_route = Setting::grab('main_route');
            $main_route_path = Setting::grab('main_route_path');
            $admin_route = Setting::grab('admin_route');
            $admin_route_path = Setting::grab('admin_route_path');
			if (Setting::grab('routes') != 'default' and file_exists(Setting::grab('routes'))){
				include Setting::grab('routes');
			}else{
				include __DIR__.'/routes.php';
			}
			
        } elseif (Request::path() == 'tickets-install'
                || Request::path() == 'tickets-upgrade'
                || Request::path() == 'tickets'
                || Request::path() == 'tickets-admin'
                || (isset($_SERVER['ARTISAN_TICKETIT_INSTALLING']) && $_SERVER['ARTISAN_TICKETIT_INSTALLING'])) {
            $this->loadTranslationsFrom(__DIR__.'/Translations', 'panichd');
            $this->loadViewsFrom(__DIR__.'/Views', 'panichd');

            $authMiddleware = Helpers\LaravelVersion::authMiddleware();

            Route::get('/tickets-install', [
                'middleware' => $authMiddleware,
                'as'         => 'tickets.install.index',
                'uses'       => 'PanicHD\PanicHD\Controllers\InstallController@index',
            ]);
            Route::post('/tickets-install', [
                'middleware' => $authMiddleware,
                'as'         => 'tickets.install.setup',
                'uses'       => 'PanicHD\PanicHD\Controllers\InstallController@setup',
            ]);
            Route::get('/tickets-upgrade', [
                'middleware' => $authMiddleware,
                'as'         => 'tickets.install.upgrade',
                'uses'       => 'PanicHD\PanicHD\Controllers\InstallController@upgrade',
            ]);
            Route::get('/tickets', function () {
                return redirect()->route('tickets.install.index');
            });
            Route::get('/tickets-admin', function () {
                return redirect()->route('tickets.install.index');
            });
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
		$this->app->register(\Yajra\Datatables\DatatablesServiceProvider::class);        
        
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
