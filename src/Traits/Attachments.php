<?php

namespace Kordy\Ticketit\Traits;

use Kordy\Ticketit\Models\Attachment;
use Kordy\Ticketit\Models\Setting;
use Illuminate\Support\Str;
use Log;
use Symfony\Component\HttpFoundation\File\UploadedFile;

trait Attachments
{
    /**
     * Saves form attached files in name="attachments[]"
     *
     * @param string $rawHtml
     *
     * @return bool
     */
    protected function saveAttachments($request, $ticket, $comment = false)
    {
		if (!$request->attachments){			
			return false;
		}
				
		foreach ($request->attachments as $uploadedFile) {
            /** @var UploadedFile $uploadedFile */
            if (is_null($uploadedFile)) {
                // No files attached
                break;
            }

            if (!$uploadedFile instanceof UploadedFile) {
                Log::error('File object expected, given: '.print_r($uploadedFile, true));
                throw new InvalidArgumentException();
				break;
            }

            $attachments_path = Setting::grab('attachments_path');
            $file_name = auth()->user()->id.'_'.$ticket->id.'_'.($comment ? $comment->id : '').md5(Str::random().$uploadedFile->getClientOriginalName());
            $file_directory = storage_path($attachments_path);

            $attachment = new Attachment();
            $attachment->ticket_id = $ticket->id;
			if ($comment){
				$attachment->comment_id = $comment->id;
				$attachment->uploaded_by_id = $comment->user_id;
			}else{
				$attachment->uploaded_by_id = $ticket->user_id;
			}            
            $attachment->original_filename = $uploadedFile->getClientOriginalName() ?: '';
            $attachment->bytes = $uploadedFile->getSize();
            $attachment->mimetype = $uploadedFile->getMimeType() ?: '';
            $attachment->file_path = $file_directory.DIRECTORY_SEPARATOR.$file_name;
            $attachment->save();

            // Should be called when you no need anything from this file, otherwise it fails with Exception that file does not exists (old path being used)
            $uploadedFile->move(storage_path($attachments_path), $file_name);
        }
		
		return true;
    }
}
