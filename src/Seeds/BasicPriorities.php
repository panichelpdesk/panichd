<?php

namespace PanicHD\PanicHD\Seeds;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use PanicHD\PanicHD\Models\Priority;

class BasicPriorities extends Seeder
{
    public $priorities = [
        'Critical' => [
            'color'     => '#cc0000',
            'magnitude' => '4', ],
        'High' => [
            'color'     => '#ffbc1b',
            'magnitude' => '3', ],
        'Normal' => [
            'color'     => '#aaa',
            'magnitude' => '2', ],
        'Low' => [
            'color'     => '#4bcd54',
            'magnitude' => '1', ],
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        // Create priorities
        foreach ($this->priorities as $name => $data) {
            $priority = Priority::firstOrNew(['name'  => $name]);
            $priority->color = $data['color'];
            $priority->magnitude = $data['magnitude'];
            $priority->save();
        }
    }
}
