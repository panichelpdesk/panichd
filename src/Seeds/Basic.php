<?php

namespace PanicHD\PanicHD\Seeds;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use PanicHD\PanicHD\Models;

class Basic extends Seeder
{
    public $categories = [
        'Issues'     => '#ffbc1b',
    ];
	
    public $priorities = [
        'Critical' => [
			'color' => '#cc0000',
			'position' => '1'],
		'High' => [
			'color' => '#ffbc1b',
			'position' => '2'],
		'Normal' => [
			'color' => '#aaa',
			'position' => '3'],
		'Low' => [
			'color' => '#4bcd54',
			'position' => '4'],
    ];
	
	public $statuses = [		
		'New' => '#319df8',
		'Open' => '#ffbc1b',
		'Customer pending' => '#df32f9',
		'Pending by other' => '#df32f9',
		'Resolved' => '4bcd540',
		'Archived' => '#858585',
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        // Create categories
        foreach ($this->categories as $name => $color) {
            $category = Models\Category::firstOrNew(['name'  => $name]);
			$category->color = $color;
			$category->save();
        }

        // Create priorities
        foreach ($this->priorities as $name => $data) {
            $priority = Models\Priority::firstOrNew(['name'  => $name]);
			$priority->color = $data['color'];
			$priority->position = $data['position'];
			$priority->save();
        }
		
		// Create statuses
        foreach ($this->statuses as $name => $color) {
            $status = Models\Status::firstOrNew(['name'  => $name]);
			$status->color = $color;
			$status->save();
        }
    }
}
