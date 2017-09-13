<?php

namespace Kordy\Ticketit\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Kordy\Ticketit\Traits\ContentEllipse;

/**
 * @property Attachment[]|Collection attachments
 *
 * @see Comment::attachments()
 */
class Comment extends Model
{
    use ContentEllipse;

    protected $table = 'ticketit_comments';
	
	/**
     * All of the relationships to be touched.
     *
     * @var array
     */
    protected $touches = ['ticket'];

    /**
     * Get related ticket.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ticket()
    {
        return $this->belongsTo('Kordy\Ticketit\Models\Ticket', 'ticket_id');
    }

    /**
     * Get comment owner.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function attachments()
    {
        return $this->hasMany(Attachment::class, 'comment_id')->orderByRaw('CASE when mimetype LIKE "image/%" then 1 else 2 end');
    }
}
