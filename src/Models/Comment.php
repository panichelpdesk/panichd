<?php

namespace PanicHD\PanicHD\Models;

use Illuminate\Database\Eloquent\Model;
use PanicHD\PanicHD\Traits\ContentEllipse;

class Comment extends Model
{
    use ContentEllipse;

    protected $table = 'panichd_comments';

    /**
     * All of the relationships to be touched.
     *
     * @var array
     */
    protected $touches = ['ticket'];

    public function delete()
    {
        $a_errors = [];
        // Delete attachments
        foreach ($this->attachments()->get() as $att) {
            $error = $att->delete();
            if ($error) {
                $a_errors[] = $error;
            }
        }

        // Delete notifications
        \DB::table('panichd_comment_email')->where('comment_id', $this->id)->delete();

        $error = $a_errors ? implode('. ', $a_errors) : null;
        if ($error != '') {
            return $error;
        }

        parent::delete();
    }

    /**
     * Get emails | members whom notifications have been sent.
     *
     * Return @collection
     */
    public function notifications()
    {
        return $this->hasMany('PanicHD\PanicHD\Models\CommentNotification')->orderBy('name');
    }

    /**
     * Get related ticket.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ticket()
    {
        return $this->belongsTo('PanicHD\PanicHD\Models\Ticket', 'ticket_id');
    }

    // TODO: Delete user() method

    /**
     * Get comment related \PanicHDMember.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('\PanicHDMember', 'user_id');
    }

    /**
     * Get Comment owner as PanicHDMember model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner()
    {
        return $this->belongsTo('\PanicHDMember', 'user_id');
    }

    public function attachments()
    {
        return $this->hasMany(Attachment::class, 'comment_id')->orderByRaw('CASE when mimetype LIKE "image/%" then 1 else 2 end');
    }

    /**
     * Filter visible comments depending on member level.
     */
    public function scopeForLevel($query, $level)
    {
        // User level
        if ($level < 2) {
            return $query->where(function ($q1) {
                // Common public comments
                return $q1->whereIN('type', ['reply', 'complete', 'completetx', 'reopen'])
                    ->orWhere(function ($q2) {
                        $q2->where('type', 'note')->whereHas('notifications', function ($q3) {
                            // Notes from where current user has been notified
                            return $q3->where('member_id', auth()->user()->id);
                        });
                    });
            });
        }

        // For agent or admin
        return $query;
    }

    /**
     * Filter comment entries that are countable as real comments (complete and reopen comments are excluded).
     */
    public function scopeCountable($query)
    {
        return $query->whereIN('type', ['reply', 'note', 'completetx']);
    }
}
