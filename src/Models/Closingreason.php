<?php

namespace Kordy\Ticketit\Models;

use Illuminate\Database\Eloquent\Model;

class Closingreason extends Model
{
    protected $table = 'ticketit_closingreasons';

    /**
     * Get related category.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function category()
    {
        return $this->belongsTo('Kordy\Ticketit\Models\Category', 'category_id');
    }

}
