<?php

namespace PanicHD\PanicHD\Models;

use Illuminate\Database\Eloquent\Model;

class CommentNotification extends Model
{
    protected $table = 'panichd_comment_email';

    protected $fillable = ['comment_id', 'name', 'email', 'member_id'];

    /**
     * primaryKey.
     *
     * @var int
     */
    protected $primaryKey = null;

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    public $timestamps = false;
}
