<?php

namespace PanicHD\PanicHD\Traits;

trait Ticketable
{

    /**
     * Returns all tickets for this model.
     */
    public function tickets()
    {
        return $this->morphMany('PanicHD\PanicHD\Models\Ticket', 'ticketable');
    }

}
