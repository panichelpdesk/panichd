<?php

namespace Kordy\Ticketit\Models;

use Illuminate\Database\Eloquent\Model;

class Closingreason extends Model
{
    protected $table = 'ticketit_closingreasons';

    /**
     * Get related category.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongTo
     */
    public function category()
    {
        return $this->belongsTo('Kordy\Ticketit\Models\Category', 'category_id');
    }
	
	/**
     * Get related status.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongTo
     */
    public function status()
    {
        return $this->belongsTo('Kordy\Ticketit\Models\Status', 'status_id');
    }

}
