<?php

namespace Kordy\Ticketit\Traits;

use Kordy\Ticketit\Models\Attachment;
use Kordy\Ticketit\Models\Setting;
use Illuminate\Support\Str;
use Log;
use Mews\Purifier\Facades\Purifier;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Validator;

trait Attachments
{
	/**
     * Saves form attached files in name="attachments[]"
     *
     * @param Request $request
	 * @param $ticket instance of Kordy\Ticketit\Models\Ticket
	 * @param $comment instance of Kordy\Ticketit\Models\Comment
     *
     * @return string
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
		
		$index = 0;
		
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
			
			// Denied uploads block process
			if (is_array($request->block_file_names) and in_array($original_filename, $request->block_file_names)){
				continue;
			}			
			
			$new_bytes = $bytes + $uploadedFile->getSize();
			
			if ($new_bytes/1024/1024 > Setting::grab('attachments_ticket_max_size')){
				
				return trans('ticketit::lang.ticket-error-max-size-reached', [
					'name' => $original_filename,
					'available_MB' => round(Setting::grab('attachments_ticket_max_size')-$bytes/1024/1024)
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
			
			// New attachments edited fields
			$a_fields = $a_errors = [];
			if (isset($request->input('attachment_new_filenames')[$index])){
				$a_fields['new_filename'] = [
					'name' => 'new_attachment_new_filename_'.$index, // Not real request input
					'value' => $request->input('attachment_new_filenames')[$index]
				];
			}else
				$attachment->new_filename = $original_filename;
			
			if (isset($request->input('attachment_descriptions')[$index])){
				$a_fields['description'] = [
					'name' => 'new_attachment_description_'.$index, // Not real request input
					'value' => $request->input('attachment_descriptions')[$index]
				];
			}
			$save = false;
			if ($a_fields) $this->updateSingleAttFields($attachment, $a_fields, $a_errors, $save);			
			
            $attachment->bytes = $uploadedFile->getSize();
            $attachment->mimetype = $uploadedFile->getMimeType() ?: '';
            $attachment->file_path = $file_directory.DIRECTORY_SEPARATOR.$file_name;
            $attachment->save();

            // Should be called when you no need anything from this file, otherwise it fails with Exception that file does not exists (old path being used)
            $uploadedFile->move(storage_path($attachments_path), $file_name);
			
			$index++;
        }
		
		return false;
    }
	
	/**
	 * Updates new_filename and description. Applies to all existent files. Not the ones uploaded in current request
	*/
	protected function updateAttachmentFields($request, $attachments)
	{
		$a_errors = [];
		
		foreach($attachments as $att){
			$new_filename = $description = $save = false;
			$a_fields = [];
			
			$field = 'attachment_'.$att->id.'_new_filename';
			if ($request->has($field)){
				$a_fields['new_filename'] = [
					'name' => $field,
					'value' => $request->input($field)
				];
			}
			
			$field = 'attachment_'.$att->id.'_description';			
			if ($request->has($field)){
				$a_fields['description'] = [
					'name' => $field,
					'value' => $request->input($field)
				];
			}
			
			if ($a_fields) $this->updateSingleAttFields($att, $a_fields, $a_errors, $save);			
			
			if ($save) $att->save();
		}
		
		if ($a_errors){
			return implode('. ', $a_errors);
		}else
			return false;
	}
	
	/**
	 * Updates new_filename and description for an Attachment instance
	*/
	protected function updateSingleAttFields(&$att, $a_fields, &$a_errors, &$save)
	{
		extract($a_fields);
		
		// New Filename
		if (isset($new_filename)){
			$filtered = trim(Purifier::clean($new_filename['value'], ['HTML.Allowed' => '']), chr(0xC2).chr(0xA0)." \t\n\r\0\x0B");
			
			$validator = Validator::make([$new_filename['name'] => $filtered], [ $new_filename['name'] => 'required|min:3' ]);

			if($validator->fails()){
				$a_errors[]= trans('ticketit::lang.attachment-update-not-valid-name', ['file' => $att->original_filename]);
			}elseif ($filtered != $att->new_filename) {
				$att->new_filename = $filtered;
				$save = true;
			}
		}
		
		// Description
		if (isset($description)){
			$filtered = trim(Purifier::clean($description['value'], ['HTML.Allowed' => '']), chr(0xC2).chr(0xA0)." \t\n\r\0\x0B");		

			if ($filtered != "" and $filtered != $att->description){
				$att->description = $filtered;
				$save = true;
			}
		}						
	}
	
	/**
     * Destroys related attachments of $ticket or $comment
     *
     * @param $ticket instance of Kordy\Ticketit\Models\Ticket
	 * @param $comment instance of Kordy\Ticketit\Models\Comment
     *
     * @return string
	 * @return bool
     */
    protected function destroyAttachments($ticket, $comment = false)
    {
		if ($comment){
			$attachments = Attachment::where('comment_id',$comment->id)->get();
		}else{
			$attachments = Attachment::where('ticket_id',$ticket->id)->get();
		}
		
		return $this->destroyAttachmentLoop($attachments);
	}
	
	
	protected function destroyAttachmentIds($a_id)
	{
		$attachments = Attachment::whereIn('id', $a_id)->get();		
		
		return $this->destroyAttachmentLoop($attachments);
	}
	
	/**
	 * Iterates through selected $attachments as instances of Attachment model
	 *
	 * @param $ticket instance of Kordy\Ticketit\Models\Ticket
	 *
     * @return string
	 * @return bool
	*/
	protected function destroyAttachmentLoop($attachments)
	{
		$delete_errors = [];
				
		foreach ($attachments as $attachment){			
			$single = $this->destroyAttachedElement($attachment);
			if ($single) $delete_errors[] = $single;
		}
		
		if ($delete_errors){
			return trans('ticketit::lang.ticket-error-delete-files').trans('ticketit::lang.colon').implode('. ', $delete_errors);
		}else{
			return false;
		}
	}
	
	/**
	 * Destroy for single attachment model instance
	*/
	protected function destroyAttachedElement($attachment)
	{
		if(!\File::exists($attachment->file_path)){
			return trans('ticketit::lang.ticket-error-file-not-found', ['name'=>$attachment->original_filename]);
		}else{
			\File::delete($attachment->file_path);
			
			if(\File::exists($attachment->file_path)){
				return trans('ticketit::lang.ticket-error-file-not-deleted', ['name'=>$attachment->original_filename]);
			}else{
				$attachment->delete();
				return false;
			}
		}
	}
	

}
