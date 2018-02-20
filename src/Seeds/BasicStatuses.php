<?php

namespace PanicHD\PanicHD\Seeds;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use PanicHD\PanicHD\Models\Status;

class BasicStatuses extends Seeder
{
	public $statuses = [		
		'New' => '#319df8',
		'Open' => '#ffbc1b',
		'Customer pending' => '#df32f9',
		'Pending by other' => '#df32f9',
		'Resolved' => '4bcd540',
		'Dismissed' => '#858585',
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		Model::unguard();

		// Create statuses
        foreach ($this->statuses as $name => $color) {
            $status = Status::firstOrNew(['name'  => $name]);
			$status->color = $color;
			$status->save();
        }
    }
}
