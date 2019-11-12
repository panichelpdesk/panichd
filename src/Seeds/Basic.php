<?php

namespace PanicHD\PanicHD\Seeds;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use PanicHD\PanicHD\Models\Category;

class Basic extends Seeder
{
    public $categories = [
        'Issues'     => '#ffbc1b',
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
            $category = Category::firstOrNew(['name'  => $name]);
            $category->color = $color;
            $category->save();
        }

        // Create priorities
        $this->call(BasicPriorities::class);

        // Create statuses
        $this->call(BasicStatuses::class);
    }
}
