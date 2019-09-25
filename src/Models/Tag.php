<?php

namespace PanicHD\PanicHD\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $table = 'panichd_tags';
    protected $fillable = ['name', 'text_color', 'bg_color'];

    /**
     * Get related categories.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function categories()
    {
        return $this->morphedByMany('PanicHD\PanicHD\Models\Category', 'taggable', 'panichd_taggables')->orderBy('name');
    }

    /**
     * Get related tickets.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function tickets()
    {
        return $this->morphedByMany('PanicHD\PanicHD\Models\Ticket', 'taggable', 'panichd_taggables');
    }
}
