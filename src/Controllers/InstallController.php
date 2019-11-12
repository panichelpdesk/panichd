<?php

namespace PanicHD\PanicHD\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use PanicHD\PanicHD\Models\Category;
use PanicHD\PanicHD\Models\Setting;
use PanicHD\PanicHD\Seeds\DemoDataSeeder;
use PanicHD\PanicHD\Seeds\SettingsTableSeeder;

class InstallController extends Controller
{
    public $migrations_tables = [];
    public $inactive_migrations = false;
    public $a_inactive_migrations = [];

    public function __construct()
    {
        $migrations = File::files(dirname(dirname(__FILE__)).'/Migrations');
        foreach ($migrations as $migration) {
            $this->migrations_tables[] = basename($migration, '.php');
        }
    }

    /*
     * Load member instance and Return true if auth() user has admin permissions
     *
     */
    public function auth_member_is_admin()
    {
        if (auth()->check()) {
            $member = \PanicHDMember::findOrFail(auth()->user()->id);

            return $member->isAdmin();
        } else {
            return false;
        }
    }

    /*
     * Initial install form
     */

    public function index()
    {
        if (session()->has('current_status')) {
            // Clear stored settings
            Artisan::call('cache:clear');

            // Load maintenance result page
            switch (session('current_status')) {
                case 'installed':
                    return view('panichd::install.status', [
                        'title'       => trans('panichd::install.just-installed'),
                        'description' => trans('panichd::install.installed-package-description', ['panichd' => url('panichd/')]),
                    ]);
                    break;
                case 'upgraded':
                    return view('panichd::install.status', [
                        'title' => trans('panichd::install.upgrade-done'),
                    ]);
                    break;
            }
        } else {
            if (class_exists('Kordy\Ticketit\TicketitServiceProvider')) {
                // Kordy/Ticketit is still installed
                return view('panichd::install.status', [
                    'title'       => trans('panichd::install.not-ready-to-install'),
                    'description' => trans('panichd::install.ticketit-still-installed', ['link' => 'https://github.com/panichelpdesk/panichd/tree/dev-package#if-kordyticketit-is-installed']),
                    'button'      => 'hidden',
                ]);
            }

            $inactive_migrations = $this->inactiveMigrations();

            if (!$this->isInstalled()) {
                \Cache::forever('panichd::installation', 'install');

                // Panic Help Desk is not installed yet

                $previous_ticketit = Schema::hasTable('ticketit_settings');

                $quickstart = true;
                if ($previous_ticketit and (\DB::table('ticketit_statuses')->count() > 0 or \DB::table('ticketit_priorities')->count() > 0 or \DB::table('ticketit_categories')->count() > 0)) {
                    $quickstart = false;
                }

                return view('panichd::install.index', compact('inactive_migrations', 'previous_ticketit', 'quickstart'));
            } elseif (!$this->isUpdated()) {

                // Panic Help Desk requires an upgrade
                if ($this->auth_member_is_admin()) {
                    return view('panichd::install.upgrade', [
                        'inactive_migrations' => $inactive_migrations,
                        'inactive_settings'   => $this->inactiveSettings(),
                        'isUpdated'           => false,
                    ]);
                } else {
                    return view('panichd::install.status', [
                        'title'       => trans('panichd::install.package-requires-update'),
                        'description' => trans('panichd::install.package-requires-update-info'),
                    ]);
                }
            } else {
                // Panic Help Desk installed and up to date. Go to stats page
                return redirect()->route('dashboard');
            }
        }
    }

    /**
     * Displays the upgrade menu although there isn't any pending action.
     */
    public function upgrade_menu()
    {
        if (!$this->isInstalled() or !$this->auth_member_is_admin()) {
            return redirect()->route('panichd.install.setup');
        }

        return view('panichd::install.upgrade', [
            'inactive_migrations' => $this->inactiveMigrations(),
            'inactive_settings'   => $this->inactiveSettings(),
            'isUpdated'           => $this->isUpdated(),
        ]);
    }

    /**
     * Check if PanicHD is installed.
     *
     * @Return bool
     */
    public function isInstalled()
    {
        // Not installed if we're in installation process
        if (\Cache::has('panichd::installation')) {
            return false;
        }

        // Not installed if no PanicHD migrations installed or choosen one is missing
        return (count($this->migrations_tables) == count($this->inactiveMigrations())
            || in_array('2017_12_25_222719_update_panichd_priorities_add_magnitude', $this->inactiveMigrations())) ? false : true;
    }

    /**
     * Check if PanicHD is up to date.
     *
     * @Return bool
     */
    public function isUpdated()
    {
        return (empty($this->inactiveMigrations()) && !$this->inactiveSettings()) ? true : false;
    }

    /*
     * Installation setup
     */

    public function setup(Request $request)
    {
        $previous_ticketit = Schema::hasTable('ticketit_settings');

        // Install migrations and Settings
        $this->initialSettings();

        // If this is an upgrade from Kordy\Ticketit
        if ($previous_ticketit) {
            Artisan::call('db:seed', [
                '--class' => 'PanicHD\\PanicHD\\Seeds\\SettingsPatch',
            ]);
        }

        // Make attachments directory
        $att_dir = storage_path(Setting::grab('attachments_path'));
        if (!File::exists($att_dir)) {
            File::makeDirectory($att_dir, 0775);
        }

        // Make storage link for thumbnail public access
        Artisan::call('storage:link');

        // Make thumbnails directory
        $thb_dir = storage_path('app'.DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.Setting::grab('thumbnails_path'));
        if (!File::exists($thb_dir)) {
            File::makeDirectory($thb_dir, 0775);
        }

        // Publish asset files
        Artisan::call('vendor:publish', [
            '--provider' => 'PanicHD\\PanicHD\\PanicHDServiceProvider',
            '--tag'      => ['panichd-public'],
        ]);

        // Add current user to panichd_admin
        $admin_user = \PanicHDMember::find(auth()->user()->id);
        $admin_user->panichd_admin = true;

        if ($request->input('quickstart') != '') {
            // Insert quickstart seed data
            Artisan::call('db:seed', [
                '--class' => 'PanicHD\\PanicHD\\Seeds\\Basic',
            ]);

            // Add current user as an agent in the last added category
            $admin_user->panichd_agent = true;

            // App\User doesn't have categories()
            $admin_member = \PanicHDMember::find(auth()->user()->id);
            $admin_member->categories()->sync([Category::first()->id]);
        }

        $admin_user->save();

        \Cache::forget('panichd::installation');

        return redirect()->route('panichd.install.index')->with('current_status', 'installed');
    }

    /*
     * Upgrade setup
     */
    public function upgrade(Request $request)
    {
        if ($this->auth_member_is_admin()) {
            // Migrations and Settings
            $this->initialSettings();

            $path = public_path().DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'panichd';

            if ($request->input('folder-action') == 'backup') {
                // Backup assets
                File::move($path, $path.'_backup_'.date('Y-m-d_H_i', time()));
            } else {
                // Delete published assets
                File::deleteDirectory($path);
            }

            // Publish asset files
            Artisan::call('vendor:publish', [
                '--provider' => 'PanicHD\\PanicHD\\PanicHDServiceProvider',
                '--tag'      => ['panichd-public'],
                '--force'    => true,
            ]);

            return redirect()->route('panichd.install.index')->with('current_status', 'upgraded');
        } else {
            return redirect()->route('panichd.install.index');
        }
    }

    /*
     * Initial installer to install migrations, seed default settings, and configure the master_template
     */

    public function initialSettings()
    {
        $inactive_migrations = $this->inactiveMigrations();
        if ($inactive_migrations) { // If a migration is missing, do the migrate
            Artisan::call('vendor:publish', [
                '--provider' => 'PanicHD\\PanicHD\\PanicHDServiceProvider',
                '--tag'      => ['panichd-db'],
            ]);
            Artisan::call('migrate');

            $this->settingsSeeder();

            // if this is the first install of the html editor, seed old posts text to the new html column
            if (in_array('2016_01_15_002617_add_htmlcontent_to_ticketit_and_comments', $inactive_migrations)) {
                Artisan::call('panichd:htmlify');
            }
        } elseif ($this->inactiveSettings()) { // new settings to be installed

            $this->settingsSeeder();
        }
        \Cache::forget('panichd::settings');
    }

    /**
     * Run the settings table seeder.
     */
    public function settingsSeeder()
    {
        $cli_path = 'config/ticketit.php'; // if seeder run from cli, use the cli path
        $provider_path = '../config/ticketit.php'; // if seeder run from provider, use the provider path
        $config_settings = [];
        $settings_file_path = false;
        if (File::isFile($cli_path)) {
            $settings_file_path = $cli_path;
        } elseif (File::isFile($provider_path)) {
            $settings_file_path = $provider_path;
        }
        if ($settings_file_path) {
            $config_settings = include $settings_file_path;
            File::move($settings_file_path, $settings_file_path.'.backup');
        }
        $seeder = new SettingsTableSeeder();
        $seeder->config = $config_settings;
        $seeder->run();
    }

    /**
     * Get all Panic Help Desk Package migrations that were not migrated.
     *
     * @return array
     */
    public function inactiveMigrations()
    {
        if ($this->inactive_migrations) {
            return $this->a_inactive_migrations;
        }
        $inactiveMigrations = [];
        $migration_arr = [];

        // Package Migrations
        $tables = $this->migrations_tables;

        // Application active migrations
        $migrations = DB::select('select * from '.DB::getTablePrefix().'migrations');

        foreach ($migrations as $migration_parent) { // Count active package migrations
            $migration_arr[] = $migration_parent->migration;
        }

        foreach ($tables as $table) {
            if (!in_array($table, $migration_arr)) {
                $inactiveMigrations[] = $table;
            }
        }
        $this->inactive_migrations = true;
        $this->a_inactive_migrations = $inactiveMigrations;

        return $inactiveMigrations;
    }

    /**
     * Check if all Panic Help Desk Package settings that were not installed to setting table.
     *
     * @return bool
     */
    public function inactiveSettings()
    {
        $seeder = new SettingsTableSeeder();

        // Package Settings
        // if Laravel v5.2 or 5.3
        if (version_compare(app()->version(), '5.2.0', '>=')) {
            $installed_settings = DB::table('panichd_settings')->pluck('value', 'slug');
        } else { // if Laravel 5.1
            $installed_settings = DB::table('panichd_settings')->lists('value', 'slug');
        }

        if (!is_array($installed_settings)) {
            $installed_settings = $installed_settings->toArray();
        }

        // Application active migrations
        $seeder_settings = $seeder->getDefaults();

        $inactive_settings = array_diff_key($seeder_settings, $installed_settings);

        if (!$inactive_settings) {
            return false;
        }

        return $inactive_settings;
    }

    /**
     * Generate demo users, agents, and tickets.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function demoDataSeeder()
    {
        $seeder = new DemoDataSeeder();
        $seeder->run();
        session()->flash('status', 'Demo tickets, users, and agents are seeded!');

        return redirect()->action('\PanicHD\PanicHD\Controllers\TicketsController@index');
    }
}
