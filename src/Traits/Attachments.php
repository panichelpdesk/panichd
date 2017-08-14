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
		
		$bytes = $ticket->allAttachments()->sum('bytes');
		$num = $ticket->allAttachments()->count();
		
		$new_bytes = 0;
				
		foreach ($request->attachments as $uploadedFile) {
            /** @var UploadedFile $uploadedFile */
            if (is_null($uploadedFile)) {
                // No files attached
                return trans('ticketit::lang.ticket-error-not-valid-file');
            }

            if (!$uploadedFile instanceof UploadedFile) {
                Log::error('File object expected, given: '.print_r($uploadedFile, true));
                return trans('ticketit::lang.ticket-error-not-valid-object', ['name'=>print_r($uploadedFile, true)]);
            }
			
			$original_filename = $uploadedFile->getClientOriginalName() ?: '';
			$new_bytes = $bytes + $uploadedFile->getSize();
			
			if ($new_bytes/1024/1024 > Setting::grab('attachments_ticket_max_size')){
				
				return trans('ticketit::lang.ticket-error-max-size-reached', [
					'name' => $original_filename,
					'available_kb' => round(Setting::grab('attachments_ticket_max_size')*1024-$bytes/1024)
				]);
			}			
			$bytes = $new_bytes;						
			
			if ($num + 1 > Setting::grab('attachments_ticket_max_files_num')){
				return trans('ticketit::lang.ticket-error-max-attachments-count-reached', [
					'name' => $original_filename,
					'max_count'=>Setting::grab('attachments_ticket_max_files_num')
				]);
			}			
			$num++;

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
            $attachment->original_filename = $original_filename;
            $attachment->bytes = $uploadedFile->getSize();
            $attachment->mimetype = $uploadedFile->getMimeType() ?: '';
            $attachment->file_path = $file_directory.DIRECTORY_SEPARATOR.$file_name;
            $attachment->save();

            // Should be called when you no need anything from this file, otherwise it fails with Exception that file does not exists (old path being used)
            $uploadedFile->move(storage_path($attachments_path), $file_name);
        }
		
		return false;
    }
}
