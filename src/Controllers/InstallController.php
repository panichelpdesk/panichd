<?php

namespace PanicHD\PanicHD\Controllers;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use PanicHD\PanicHD\Models;
use PanicHD\PanicHD\Models\Agent;
use PanicHD\PanicHD\Models\Category;
use PanicHD\PanicHD\Models\Setting;
use PanicHD\PanicHD\Seeds\SettingsTableSeeder;
use PanicHD\PanicHD\Seeds\DemoDataSeeder;

class InstallController extends Controller
{
    public $migrations_tables = [];

    public function __construct()
    {
        $migrations = \File::files(dirname(dirname(__FILE__)).'/Migrations');
        foreach ($migrations as $migration) {
            $this->migrations_tables[] = basename($migration, '.php');
        }
    }

    /*
     * Initial install form
     */

    public function index()
    {
		if (session()->has('current_status')){
			// Clear stored settings
			Artisan::call('cache:clear');
			
			// Load maintenance result page
			switch (session('current_status')){
				case 'installed':
					return view('panichd::install.status', [
						'title' => trans('panichd::install.just-installed'),
						'description' => trans('panichd::install.installed-package-description', ['panichd' => url('panichd/')])
					]);
					break;
				case 'upgraded':
					return view('panichd::install.status', [
						'title' => trans('panichd::install.upgrade-done')
					]);
					break;
			}
		}else{
			$inactive_migrations = $this->inactiveMigrations();
			
			if (count($this->migrations_tables) == count($inactive_migrations)
            || in_array('2017_12_25_222719_update_panichd_priorities_add_position', $this->inactiveMigrations())
			) {
				// Panic Help Desk is not installed yet
				
				$inactive_migrations = $this->inactiveMigrations();
				$previous_ticketit = Schema::hasTable('ticketit_settings');
				
				return view('panichd::install.index', compact('inactive_migrations', 'previous_ticketit'));
			}else{
				$inactive_settings = $this->inactiveSettings();
				
				if($inactive_settings and count($inactive_settings) > 0){
					// Panic Help Desk requires an upgrade
					if (Agent::isAdmin()){
						return view('panichd::install.upgrade', compact('inactive_migrations', 'inactive_settings'));
					}else{
						return view('panichd::install.status', [
							'title' => trans('panichd::install.package-requires-update'),
							'description' => trans('panichd::install.package-requires-update-info'),
						]);
					}
					
				}else{
					// Panic Help Desk installed and configured. Go to stats page
					return redirect()->route('dashboard');
				}
			}
		}
    }

    /*
     * Installation setup
     */

    public function setup(Request $request)
    {	
		$previous_ticketit = Schema::hasTable('ticketit_settings');
		
		// Install migrations and Settings
		$this->initialSettings();
		
		// If this is an upgrade from Kordy\Ticketit, reset necessary old settings
		if ($previous_ticketit){
			$a_reset = [
				'admin_route',
				'admin_route_path',
				'master_template',
			];
			
			foreach ($a_reset as $setting){
				$row = Setting::where('slug', $setting)->first();
				$row->value = $row->default;
				$row->save();
			}
		}
		
		// Make attachments directory
		$att_dir = storage_path(Setting::grab('attachments_path'));
		if (!File::exists($att_dir)) File::makeDirectory($att_dir);
		
		// Make storage link for thumbnail public access
		Artisan::call('storage:link');
		
		// Make thumbnails directory
		$thb_dir = storage_path('app'.DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.Setting::grab('thumbnails_path'));
		if (!File::exists($thb_dir)) File::makeDirectory($thb_dir);
		
		// Publish asset files
		Artisan::call('vendor:publish', [
			'--provider' => 'PanicHD\\PanicHD\\PanicHDServiceProvider',
			'--tag'      => ['panichd-public'],
		]);
		
		$admin = Agent::find(auth()->user()->id);
        $admin->panichd_admin = true;
		
		if ($request->has('quickstart')){
			Artisan::call('db:seed', [
				'--class' => 'PanicHD\\PanicHD\\Seeds\\Basic',
			]);
			
			$admin->panichd_agent = true;
			$admin->categories()->sync([Category::first()->id]);
		}
		
		$admin->save();

        return redirect()->route('panichd.install.index')->with('current_status', 'installed');
    }
	
	/*
     * Upgrade setup
     */
	public function upgrade(Request $request)
	{
		if (Agent::isAdmin()){
			// Migrations and Settings
			$this->initialSettings();
			
			$path = public_path().DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'panichd';
			
			if($request->input('folder-action')=='backup'){
				// Backup assets
				File::move($path, $path.'_backup_'.date('Y-m-d_H_i', time()));
			}else{
				// Delete published assets
				File::deleteDirectory($path);
			}
			
			// Publish asset files
			Artisan::call('vendor:publish', [
				'--provider' => 'PanicHD\\PanicHD\\PanicHDServiceProvider',
				'--tag'      => ['panichd-public'],
				'--force'    => true
			]);
			
			return redirect()->route('panichd.install.index')->with('current_status', 'upgraded');
		}else{
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
     * Get list of all files in the views folder.
     *
     * @return mixed
     */
    public function viewsFilesList($dir_path)
    {
        $dir_files = File::files($dir_path);
        $files = [];
        foreach ($dir_files as $file) {
            $path = basename($file);
            $name = strstr(basename($file), '.', true);
            $files[$name] = $path;
        }

        return $files;
    }

    /**
     * Get list of all files in the views folder.
     *
     * @return mixed
     */
    public function allFilesList($dir_path)
    {
        $files = [];
        if (File::exists($dir_path)) {
            $dir_files = File::allFiles($dir_path);
            foreach ($dir_files as $file) {
                $path = basename($file);
                $name = strstr(basename($file), '.', true);
                $files[$name] = $path;
            }
        }

        return $files;
    }

    /**
     * Get all Panic Help Desk Package migrations that were not migrated.
     *
     * @return array
     */
    public function inactiveMigrations()
    {
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
        $default_Settings = $seeder->getDefaults();

        if (count($installed_settings) == count($default_Settings)) {
            return false;
        }

        $inactive_settings = array_diff_key($default_Settings, $installed_settings);
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
