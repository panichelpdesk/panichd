<?php

namespace PanicHD\PanicHD\Tests;

use PanicHD\PanicHD\Controllers\InstallController;
use PanicHD\PanicHD\Models\Member;
use PanicHD\PanicHD\Models\Setting;

abstract class PanicHDTestCase extends \PHPUnit_Framework_TestCase
{
    // Package general status
    protected $status = "Not installed";

    // Main route
    protected $main_route = null;

    // Member types tests set
    protected $member = null, $agent = null, $admin = null;

    // Eloquent reusable builder for Member Tickets
    protected $member_tickets_builder;

    /*
     * Load a basic vars set for package tests
    */
    public function load_vars()
    {
        if (is_null($this->main_route)){
            $InstallController = new InstallController();

            if (!$InstallController->isInstalled()){
                if (\Cache::has('panichd::installation')) $this->status = "Installing";

            }elseif (!$InstallController->isUpdated()){
                $this->status = "Out of date";

            }else{
                $this->status = "Installed";

                $this->main_route = Setting::grab('main_route');
            }
        }

        if (is_null($this->member)){
            if (Member::users()->count() > 0){
                $this->member = Member::whereHas('tickets', function($query){ $query->notHidden(); })->inRandomOrder()->users()->first();
                if (!is_null($this->member)){
                    $this->member_tickets_builder = $this->member->tickets()->inRandomOrder();
                }
            }
        }

        if (is_null($this->agent)){
            if (Member::agents()->count() > 0){
                $this->agent = Member::whereHas('agentTickets')->inRandomOrder()->agents()->first();
            }
        }

        if (is_null($this->admin)){
            if (Member::admins()->count() > 0){
                $this->admin = Member::inRandomOrder()->admins()->first();
            }
        }
    }
}

?>
