<?php

namespace PanicHD\PanicHD\Models;

use Illuminate\Database\Eloquent\Model;

class Priority extends Model
{
    protected $table = 'panichd_priorities';

    protected $fillable = ['name', 'color', 'magnitude'];

    /**
     * Indicates that this model should not be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    public function delete($tickets_new_priority_id = false)
    {
        $tickets = $this->tickets()->get();

        if (!$tickets_new_priority_id) {
            $new_priority = self::whereNotIn('id', [$this->id])->first();

            if ($new_priority and $tickets->count() > 0) {
                $tickets_new_priority_id = $new_priority->id;
            }
        }

        if ($tickets_new_priority_id and $tickets->count() > 0) {
            foreach ($tickets as $ticket) {
                $ticket->priority_id = $tickets_new_priority_id;
                $ticket->save();
            }
        }

        parent::delete();
    }

    /**
     * Get related tickets.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tickets()
    {
        return $this->hasMany('PanicHD\PanicHD\Models\Ticket', 'priority_id');
    }
}
