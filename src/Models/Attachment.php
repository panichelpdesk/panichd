<?php

namespace PanicHD\PanicHD\Models;

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

    protected $table = 'panichd_attachments';

    /**
     * All of the relationships to be touched.
     *
     * @var array
     */
    protected $touches = ['ticket', 'comment'];

    private $image_mimetypes = [
        'image/gif'  => 'image',
        'image/ico'  => 'image',
        'image/jpeg' => 'image',
        'image/png'  => 'image',
    ];

    /**
     * Delete Attachment instance and related files at server storage folder.
     */
    public function delete()
    {
        $error = $this->destroyAttachedElement($this);
        if ($error) {
            return $error;
        } else {
            parent::delete();
        }
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
        return $this->belongsTo(\PanicHDMember::class, 'uploaded_by_id');
    }

    public function scopeImages($query)
    {
        $a_mimes = array_keys($this->image_mimetypes);

        $query->where(function ($q1) use ($a_mimes) {
            $q1->where('mimetype', 'like', current($a_mimes));

            foreach (array_slice($a_mimes, 1) as $mime) {
                $q1->orWhere('mimetype', 'like', $mime);
            }
        });
    }

    public function scopeNotImages($query)
    {
        $a_mimes = array_keys($this->image_mimetypes);

        $query->where(function ($q1) use ($a_mimes) {
            foreach ($a_mimes as $mime) {
                $q1->where('mimetype', 'not like', $mime);
            }
        });
    }

    public function getShorthandMime($mimetype)
    {
        $file_mimetypes = [
            // Pdf
            'application/pdf' => 'pdf',

            // MS Word like document
            'application/msword'                                                      => 'msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'msword',
            'text/rtf'                                                                => 'msword',

            // Spreadsheet
            'application/vnd.ms-excel'                                          => 'msexcel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'msexcel',

            // Compressed file
            'application/zip' => 'compressed',
        ];

        $mimetype_patterns = $this->image_mimetypes + $file_mimetypes;

        if (preg_match('/('.str_replace('/', '\/', implode(')|(', array_keys($mimetype_patterns))).')/', $mimetype, $ret, PREG_OFFSET_CAPTURE) == 1
            and isset($ret[0]) and isset($mimetype_patterns[$ret[0][0]])) {
            return $mimetype_patterns[$ret[0][0]];
        } else {
            return 'default';
        }
    }
}
