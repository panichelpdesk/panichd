<?php

namespace Kordy\Ticketit\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

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
    protected $table = 'ticketit_attachments';

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
	
	public function getShorthandMime($mimetype)
	{
		$mimetype_patterns = [
			'image/' => 'image',
			'application/pdf' => 'pdf',
			'application/msword' => 'msword',
			'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'msword',
			'application/vnd.ms-excel' => 'msexcel',
			'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'msexcel'
		];
		
		// (image\/)|(application\/pdf)|(application\/msword)|(application\/vnd.openxmlformats-officedocument.wordprocessingml.document)
		
		if (preg_match('/('.str_replace('/', '\/', implode(')|(', array_keys($mimetype_patterns))).')/', $mimetype, $ret, PREG_OFFSET_CAPTURE) == 1){			
			return (isset($ret[0]) and isset($mimetype_patterns[$ret[0][0]])) ? $mimetype_patterns[$ret[0][0]] : "";
		}else{
			return "";
		}
	}
}
