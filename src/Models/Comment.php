<?php

namespace PanicHD\PanicHD\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use PanicHD\PanicHD\Traits\ContentEllipse;

/**
 * @property Attachment[]|Collection attachments
 *
 * @see Comment::attachments()
 */
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

	/**
	 *
	*/
	public function delete()
	{
		$this->attachments()->delete();
		parent::delete();
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

    /**
     * Get comment related App\User.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
	
	/**
     * Get Comment owner as PanicHD\PanicHD\Models\Agent model
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner()
    {
        return $this->belongsTo('PanicHD\PanicHD\Models\Agent', 'user_id');
    }

    public function attachments()
    {
        return $this->hasMany(Attachment::class, 'comment_id')->orderByRaw('CASE when mimetype LIKE "image/%" then 1 else 2 end');
    }
}
