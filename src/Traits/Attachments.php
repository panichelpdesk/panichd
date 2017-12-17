<?php

namespace PanicHD\PanicHD\Traits;

use PanicHD\PanicHD\Models\Attachment;
use PanicHD\PanicHD\Models\Setting;
use Illuminate\Support\Str;
use Intervention\Image\ImageManagerStatic as Image;
use Mews\Purifier\Facades\Purifier;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Validator;

trait Attachments
{
	/**
     * Saves form attached files in name="attachments[]"
     *
     * @param Request $request
	 * @param $ticket instance of PanicHD\PanicHD\Models\Ticket
	 * @param $comment instance of PanicHD\PanicHD\Models\Comment
     *
     * @return string
	 * @return bool
     */
    protected function saveAttachments($request, $a_result_errors, $ticket, $comment = false)
    {
		if (!$request->attachments){			
			return $a_result_errors;
		}
		
		$bytes = $ticket->allAttachments()->sum('bytes');
		$num = $ticket->allAttachments()->count();
		$block = $comment ? $comment->attachments()->count() : $ticket->attachments()->count();
		
		$new_bytes = 0;
		
		$index = 0;
		$a_errors = [];
		
		foreach ($request->attachments as $uploadedFile) {
            /** @var UploadedFile $uploadedFile */
            if (is_null($uploadedFile)) {
                // No files attached
                $a_errors['attachment_block_'.($block+$index)] = trans('ticketit::lang.ticket-error-not-valid-file');
				$index++;
				continue;
            }

            if (!$uploadedFile instanceof UploadedFile) {
				$a_errors['attachment_block_'.($block+$index)] = trans('ticketit::lang.ticket-error-not-valid-object', ['name'=>print_r($uploadedFile, true)]);
				$index++;
				continue;
            }
			
			$original_filename = $uploadedFile->getClientOriginalName() ?: '';
			
			// Denied uploads block process
			if (is_array($request->block_file_names) and in_array($original_filename, $request->block_file_names)){
				$index++;
				continue;
			}			
			
			$new_bytes = $bytes + $uploadedFile->getSize();
			
			if ($new_bytes/1024/1024 > Setting::grab('attachments_ticket_max_size')){
				
				$a_errors['attachment_block_'.($block+$index)] = trans('ticketit::lang.ticket-error-max-size-reached', [
					'name' => $original_filename,
					'available_MB' => round(Setting::grab('attachments_ticket_max_size')-$bytes/1024/1024)
				]);
				$index++;
				continue;
			}			
			$bytes = $new_bytes;						
			
			if ($num + 1 > Setting::grab('attachments_ticket_max_files_num')){
				$a_errors['attachment_block_'.($block+$index)] = trans('ticketit::lang.ticket-error-max-attachments-count-reached', [
					'name' => $original_filename,
					'max_count'=>Setting::grab('attachments_ticket_max_files_num')
				]);
				$index++;
				continue;
			}			
			$num++;

            $attachments_path = Setting::grab('attachments_path');
            $file_name = $this->makeFilename($uploadedFile->getClientOriginalName(), $ticket->id, ($comment ? $comment->id : ''));			
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
			
			// Mimetype
			$validator = Validator::make(['file' => $uploadedFile], [ 'file' => 'mimes:'.Setting::grab('attachments_mimes') ]);

			if($validator->fails()){
				$a_errors['attachment_block_'.($block+$index)] = trans('ticketit::lang.attachment-update-not-valid-mime', ['file' => $original_filename]);
				$index++;
				continue;
			}
			
			// New attachments edited fields
			$a_fields = $a_single_errors = [];
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
			if ($a_fields) $this->updateSingleAttachment($attachment, $a_fields, $a_single_errors, $save);			
			
            $attachment->bytes = $uploadedFile->getSize();
            $attachment->mimetype = $uploadedFile->getMimeType() ?: '';
            $attachment->file_path = $file_directory.DIRECTORY_SEPARATOR.$file_name;
			$attachment->original_attachment = $file_name;
            

			// Thumbnail for valid image types
			$validator = Validator::make(['file' => $uploadedFile], [ 'file' => 'mimes:jpeg,png,gif,wbmp,webp,xbm,xpm' ]);
			
			$is_image = $validator->fails() ? false : true;
			
            // Should be called when you no need anything from this file, otherwise it fails with Exception that file does not exists (old path being used)
            $uploadedFile->move(storage_path($attachments_path), $file_name);
			
			if ($is_image){
				$img = Image::make($attachment->file_path);
				
				// Image sizes field
				$attachment->image_sizes = $img->width()."x".$img->height();
				
				// Image thumbnail
				$this->makeThumbnailFromImage($img, $file_name);
			}
			
			$attachment->save();
			
			$index++;
        }
		
		if ($a_errors){
			$a_error_messages = array_values($a_errors);
			
			$a_result_errors['messages'] = ($a_result_errors and isset($a_result_errors['messages'])) ? array_merge($a_result_errors['messages'], $a_error_messages) : $a_error_messages;
			$a_result_errors['fields'] = ($a_result_errors and isset($a_result_errors['fields'])) ? array_merge($a_result_errors['fields'], $a_errors) : $a_errors;
		}
		
		return $a_result_errors;
    }
	
	protected function makeFilename($base, $var, $optvar)
	{
		return auth()->user()->id.'_'.$var.'_'.$optvar.md5(Str::random().$base);
	}
	
	protected function makeThumbnailFromImage($img, $file_name)
	{
		// Thumbnail
		$thumbnail_path = storage_path('app'.DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.'ticketit_thumbnails'.DIRECTORY_SEPARATOR);
		
		// Delete previous thumbnail if present
		if(\File::exists($thumbnail_path.$file_name))			
			\File::delete($thumbnail_path.$file_name);
		
		$img->heighten(50)->widen(50)->encode('png')->resizeCanvas(50, 50)->save($thumbnail_path.$file_name);
		
		// The alternative method below seems to cut borders on non square images. Image loses a lot of quality also
		/*$thumb = Image::canvas(50, 50);
		$thumb->insert($img, 'center')->save($thumbnail_path.$file_name);*/
	}
	
	/**
	 * Updates new_filename and description. Applies to all existent files. Not the ones uploaded in current request
	*/
	protected function updateAttachments($request, $attachments)
	{
		$a_single_errors = [];
		
		foreach($attachments as $att){
			$new_filename = $description = $save = false;
			$a_fields = [];
			
			// New filename
			$field = 'attachment_'.$att->id.'_new_filename';
			if ($request->has($field)){
				$a_fields['new_filename'] = [
					'name' => $field,
					'value' => $request->input($field)
				];
			}
			
			// Description
			$field = 'attachment_'.$att->id.'_description';			
			if ($request->has($field)){
				$a_fields['description'] = [
					'name' => $field,
					'value' => $request->input($field)
				];
			}
			
			// Image cropping
			$field = 'attachment_'.$att->id.'_image_crop';
			if ($request->has($field)){
				$a_fields['image_crop'] = $request->input($field);
			}
			
			if ($a_fields) $this->updateSingleAttachment($att, $a_fields, $a_single_errors, $save);			
			
			if ($save) $att->save();
		}
		
		if ($a_single_errors){
			return implode('. ', $a_single_errors);
		}else
			return false;
	}
	
	/**
	 * Updates new_filename and description for an Attachment instance
	*/
	protected function updateSingleAttachment(&$att, $a_fields, &$a_single_errors, &$save)
	{
		extract($a_fields);
		
		// New Filename
		if (isset($new_filename)){
			$filtered = trim(Purifier::clean($new_filename['value'], ['HTML.Allowed' => '']), chr(0xC2).chr(0xA0)." \t\n\r\0\x0B");
			
			$validator = Validator::make([$new_filename['name'] => $filtered], [ $new_filename['name'] => 'required|min:3' ]);

			if($validator->fails()){
				$a_single_errors[]= trans('ticketit::lang.attachment-update-not-valid-name', ['file' => $att->original_filename]);
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
		
		// Image crop
		if (isset($image_crop)){
			$coords = explode(',', $image_crop);
			if (count($coords) != 4 or ctype_digit(str_replace(",", "", str_replace(".", "", $coords)))){
				$a_single_errors[] = trans('ticketit::lang.attachment-update-crop-error');
			}else{
				$img = Image::make($att->file_path);				
								
				// New filename
				$new_filename = $this->makeFilename($att->new_filename.date('YmdHis', time()), $att->ticket_id, ($att->comment_id?$att->comment_id:''));
				$new_file_path = storage_path(Setting::grab('attachments_path')).DIRECTORY_SEPARATOR.$new_filename;
				
				// Resize and save image				
				$img->crop(intval($coords[2]-$coords[0]), intval($coords[3]-$coords[1]), intval($coords[0]), intval($coords[1]))->save($new_file_path);
				
				// Create new thumbnail
				$this->makeThumbnailFromImage($img, $new_filename);
				
				// Delete image if it's not the original one
				if ($att->original_attachment != basename($att->file_path)){
					// Delete image
					$error = $this->deleteAttachmentFile($att->file_path, $att->original_filename);
					if ($error) $a_single_errors[] = $error;
				}
				
				// Delete old thumbnail
				$this->deleteThumbnail(basename($att->file_path));

				// Updated fields
				$att->image_sizes = $img->width()."x".$img->height();
				$att->file_path = $new_file_path;

				$save = true;
			}
		}
	}
	
	/**
     * Destroys related attachments of $ticket or $comment
     *
     * @param $ticket instance of PanicHD\PanicHD\Models\Ticket
	 * @param $comment instance of PanicHD\PanicHD\Models\Comment
     *
     * @return string
	 * @return bool
     */
    protected function destroyAttachmentsFrom($ticket, $comment = false)
    {
		if ($comment){
			$attachments = Attachment::where('comment_id',$comment->id)->get();
		}else{
			$attachments = Attachment::where('ticket_id',$ticket->id)->get();
		}
		
		return $this->destroyAttachmentsLoop($attachments);
	}
	
	
	protected function destroyAttachmentIds($a_id)
	{
		$attachments = Attachment::whereIn('id', $a_id)->get();		
		
		return $this->destroyAttachmentsLoop($attachments);
	}
	
	/**
	 * Iterates through selected $attachments as instances of Attachment model
	 *
	 * @param $ticket instance of PanicHD\PanicHD\Models\Ticket
	 *
     * @return string
	 * @return bool
	*/
	protected function destroyAttachmentsLoop($attachments)
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
	 * Destroy a single attachment files and model instance
	*/
	protected function destroyAttachedElement($attachment, $delete_instance = true)
	{
		// Delete attachment file
		$error = $this->deleteAttachmentFile($attachment->file_path, $attachment->original_filename);
		if ($error)	return $error;
		
		// Delete original file (if exists)
		if ($attachment->original_attachment != basename($attachment->file_path)){
			$original_path = pathinfo($attachment->file_path, PATHINFO_DIRNAME).DIRECTORY_SEPARATOR.$attachment->original_attachment;
			$error = $this->deleteAttachmentFile($original_path, $attachment->original_filename);
			if ($error)	return $error;
		}		
		
		// Delete thumbnail
		$this->deleteThumbnail(basename($attachment->file_path));
		
		// Delete ticketit attachment instance				
		if ($delete_instance) $attachment->delete();
		return false;		
	}
	
	// Delete attachment file
	protected function deleteAttachmentFile($file_path, $filename)
	{
		if(!\File::exists($file_path)){
			return trans('ticketit::lang.ticket-error-file-not-found', ['name'=>$filename]);
		}else{
			\File::delete($file_path);
			
			if(\File::exists($file_path)){
				return trans('ticketit::lang.ticket-error-file-not-deleted', ['name'=>$filename]);
			}else
				return false;
		}
	}
	
	
	// Delete thumbnail file
	protected function deleteThumbnail ($file_name)
	{		
		$thumbnail_path = storage_path('app'.DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.'ticketit_thumbnails'.DIRECTORY_SEPARATOR);
		
		if (\File::exists($thumbnail_path.$file_name)){
			\File::delete($thumbnail_path.$file_name);
		}
		
		return \File::exists($thumbnail_path.$file_name) ? false : true;
	}
}