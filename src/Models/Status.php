<?php

namespace PanicHD\PanicHD\Models;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    protected $table = 'panichd_statuses';

    protected $fillable = ['name', 'color'];

    /**
     * Indicates that this model should not be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    public function delete($tickets_new_status_id = false)
    {
        if ($tickets_new_status_id) {
            foreach ($this->tickets()->get() as $ticket) {
                $ticket->status_id = $tickets_new_status_id;
                $ticket->save();
            }
        } else {
            foreach ($this->tickets()->get() as $ticket) {
                if ($ticket->isComplete()) {
                    $ticket->status_id = Setting::grab('default_close_status_id');
                } else {
                    $ticket->status_id = Setting::grab('default_close_status_id');
                }
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
        return $this->hasMany('PanicHD\PanicHD\Models\Ticket', 'status_id');
    }

    /**
     * Get related closing reasons.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function closingReasons()
    {
        return $this->hasMany('PanicHD\PanicHD\Models\Closingreason', 'status_id');
    }
}
