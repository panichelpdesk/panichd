<?php

namespace PanicHD\PanicHD\Models;

use Illuminate\Database\Eloquent\Model;

class Closingreason extends Model
{
    protected $table = 'panichd_closingreasons';

    /**
     * Get related category.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongTo
     */
    public function category()
    {
        return $this->belongsTo('PanicHD\PanicHD\Models\Category', 'category_id');
    }

    /**
     * Get related status.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongTo
     */
    public function status()
    {
        return $this->belongsTo('PanicHD\PanicHD\Models\Status', 'status_id');
    }
}
