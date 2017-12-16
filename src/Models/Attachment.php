<?php

namespace PanicHD\PanicHD\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use PanicHD\PanicHD\Traits\Attachments;

/**
 * @property int id
 * @property int ticket_id
 * @property int comment_id
 * @property int uploaded_by_id
 * @property string file_path
 * @property string original_filename
 * @property string new_filename
 * @property long description
 * @property int bytes
 * @property string mimetype
 * @property Ticket ticket
 *
 * @see Attachment::ticket()
 *
 * @property Comment comment
 *
 * @see Attachment::comment()
 *
 * @property User uploadedBy
 *
 * @see Attachment::uploadedBy()
 */
class Attachment extends Model
{
    use Attachments;
	
	protected $table = 'ticketit_attachments';

	/**
     * All of the relationships to be touched.
     *
     * @var array
     */
    protected $touches = ['ticket', 'comment'];
	
	/**
	 * Delete Attachment instance and related files at server storage folder
	*/
	public function delete()
	{
		$error = $this->destroyAttachedElement($this, true);
		return $error ? false : true;
	}
	
    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function comment()
    {
        return $this->belongsTo(Comment::class);
    }

    public function uploadedBy()
    {
        return $this->belongsTo(User::class, 'uploaded_by_id');
    }
	
	public function scopeImages($query)
	{
		$query->where('mimetype', 'like', 'image/%');
	}
	
	public function scopeNotImages($query)
	{
		$query->where('mimetype', 'not like', 'image/%');
	}
	
	public function getShorthandMime($mimetype)
	{
		$mimetype_patterns = [
			'image/' => 'image',
			'application/pdf' => 'pdf',
			'application/msword' => 'msword',
			'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'msword',
			'text/rtf' => 'msword',
			'application/vnd.ms-excel' => 'msexcel',
			'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'msexcel',
			'application/zip' => 'compressed'
		];
		
		if (preg_match('/('.str_replace('/', '\/', implode(')|(', array_keys($mimetype_patterns))).')/', $mimetype, $ret, PREG_OFFSET_CAPTURE) == 1
			and isset($ret[0]) and isset($mimetype_patterns[$ret[0][0]])){			
			return $mimetype_patterns[$ret[0][0]];
		}else{
			return "default";
		}
	}
}
