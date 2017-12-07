<?php

namespace PanicHD\PanicHD\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $table = 'ticketit_tags';
    protected $fillable = ['name'];

    /**
     * Get related categories.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function categories()
    {
        return $this->morphedByMany('PanicHD\PanicHD\Models\Category', 'taggable', 'ticketit_taggables')->orderBy('name');
    }

    /**
     * Get related tickets.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function tickets()
    {
        return $this->morphedByMany('PanicHD\PanicHD\Models\Ticket', 'taggable', 'ticketit_taggables');
    }
}
