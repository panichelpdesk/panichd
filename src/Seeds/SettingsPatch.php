<?php

namespace PanicHD\PanicHD\Seeds;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use PanicHD\PanicHD\Models\Setting;

class SettingsPatch extends Seeder
{
    // Settings to delete
    public $a_delete = [
        'routes',
    ];

    // Settings to reset value
    public $a_reset = [
        'admin_route',
        'admin_route_path',
        'master_template',
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        // Delete not used parameters
        Setting::whereIn('slug', $this->a_delete)->delete();

        // Reset necessary settings
        foreach ($this->a_reset as $setting) {
            $row = Setting::where('slug', $setting)->first();
            $row->value = $row->default;
            $row->save();
        }
    }
}
