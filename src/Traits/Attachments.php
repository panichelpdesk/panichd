<?php

namespace PanicHD\PanicHD\Traits;

use Illuminate\Support\Str;
use Intervention\Image\ImageManagerStatic as Image;
use Mews\Purifier\Facades\Purifier;
use PanicHD\PanicHD\Models\Attachment;
use PanicHD\PanicHD\Models\Setting;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Validator;

trait Attachments
{
    /**
     * Generate attachment from any embbeded image within $html that exceeds any of the max dimensions (hardcoded 380 x 300 max).
     *
     * @Param $html string
     *
     * @return string
     */
    protected function createAttachmentsFrom($html, $ticket, $comment = false, $count = 0)
    {
        // Html field without embedded image <img> tags
        if (!preg_match('/src="data:image\/(png|jpeg|gif);base64,/', $html)) {
            return [
                'html'  => $html,
                'count' => 0,
            ];
        }

        $dom = new \DomDocument();

        if (@!$dom->loadHtml(mb_convert_encoding('<div>'.$html.'</div>', 'HTML-ENTITIES', 'UTF-8'), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD)) {
            return [
                'html'  => $html,
                'count' => 0,
            ];
        }

        $container = $dom->getElementsByTagName('div')->item(0);
        $container = $container->parentNode->removeChild($container);
        while ($dom->firstChild) {
            $dom->removeChild($dom->firstChild);
        }

        while ($container->firstChild) {
            $dom->appendChild($container->firstChild);
        }

        // Exit when loadHtml doesn't process correctly
        if (!$dom) {
            \Log::info('ticket '.$ticket->id.' loadHtml error');

            return [
                'html'  => $html,
                'count' => 0,
            ];
        }

        $images = $dom->getElementsByTagName('img');

        $i = 0;
        foreach ($images as $k => $node) {
            $i++;

            if (preg_match('/summernote_embedded_image|summernote_thumbnail_image/', $node->getAttribute('class'))) {
                // Image has been processed before. Don't need to do anything with it
            } elseif (!preg_match('/^data:image\/(png|jpeg|gif);base64,/', $node->getAttribute('src'))) {
                // Image src is not of base64 type
            } else {
                // Image is base64

                // Read image dimensions
                $data = $node->getAttribute('src');
                list($type, $data) = explode(';', $data);
                list(, $data) = explode(',', $data);
                $img = Image::make($data);

                if ($img->width() < 380 and $img->height() < 300) {
                    // Image is small and will not be processed again
                    $node->setAttribute('class', 'summernote_embedded_image');
                } else {
                    // Create filename
                    $original_filename = 'embedded_image_'.($i + $count).'.png';

                    $file_name = $this->makeFilename($original_filename.date('YmdHis', time()), $ticket->id.'_embedded', '');

                    // Attach real file to storage folder
                    $file_path = storage_path(Setting::grab('attachments_path')).DIRECTORY_SEPARATOR.$file_name;
                    file_put_contents($file_path, base64_decode($data));

                    // Create new Attachment
                    $attachment = new Attachment();
                    $attachment->ticket_id = $ticket->id;
                    if ($comment) {
                        $attachment->comment_id = $comment->id;
                        $attachment->uploaded_by_id = $comment->user_id;
                    } else {
                        $attachment->uploaded_by_id = $ticket->user_id;
                    }
                    $attachment->original_filename = $attachment->new_filename = $original_filename;

                    $attachment->bytes = filesize($file_path);
                    $attachment->mimetype = 'image/png';
                    $attachment->image_sizes = $img->width().'x'.$img->height();
                    $attachment->file_path = $file_path;
                    $attachment->original_attachment = $file_name;

                    // Make thumbnail
                    $this->makeThumbnailFromImage($img, $file_name);

                    // Save attachment instance
                    $attachment->save();

                    // Insert image thumbnail
                    $child_img = $dom->createElement('img');
                    $child_img->setAttribute('src', \URL::to('/').'/storage/'.Setting::grab('thumbnails_path').'/'.basename($attachment->file_path));
                    $child_img->setAttribute('class', 'summernote_thumbnail_image');

                    // Create link
                    $link = $dom->createElement('a');
                    $link->setAttribute('href', \URL::route(Setting::grab('main_route').'.view-attachment', [$attachment->id]));
                    $link->setAttribute('class', 'summernote_thumbnail_link tooltip-show');
                    $link->setAttribute('title', $original_filename);

                    // Append thumbnail in link
                    $link->appendChild($child_img);

                    // replace original image with thumbnail link
                    $node->parentNode->replaceChild($link, $node);
                }
            }
        }

        return [
            'html'  => $dom->saveHTML(),
            'count' => $i,
        ];
    }

    /**
     * Filter ticket or comment fields and create attachments from big embedded images.
     *
     * @param $ticket instance of PanicHD\PanicHD\Models\Ticket
     * @param $comment instance of PanicHD\PanicHD\Models\Comment
     */
    protected function embedded_images_to_attachments($permission_level, &$ticket, &$comment = false)
    {
        $field = $comment ? $comment->html : $ticket->html;

        // Move emmbedded images in description to attachments
        $output = $this->createAttachmentsFrom($field, $ticket, $comment);
        if ($output['count'] > 0) {
            $field = $output['html'];
        }

        // Move emmbedded images in intervention to attachments
        if (!$comment and $permission_level > 1 and $ticket->intervention_html != '') {
            $output = $this->createAttachmentsFrom($ticket->intervention_html, $ticket, $comment, $output['count']);
            if ($output['count'] > 0) {
                $ticket->intervention_html = $output['html'];
            }
        }

        if ($comment) {
            $comment->html = $field;
            $comment->save();
        } else {
            $ticket->html = $field;
            $ticket->save();
        }
    }

    /**
     * Saves form attached files in name="attachments[]".
     *
     * @param Request $request
     * @param $ticket instance of PanicHD\PanicHD\Models\Ticket
     * @param $comment instance of PanicHD\PanicHD\Models\Comment
     *
     * @return string
     * @return bool
     */
    protected function saveAttachments($info)
    {
        extract($info);

        // Check specific attachments field names
        $r_attachments = (isset($attachments_field) and $attachments_field) ? $request->{$attachments_field} : (isset($attachments_prefix) ? $request->{$attachments_prefix.'attachments'} : $request->attachments);
        $filenames_field = $attachment_filenames_field ?? ($attachments_prefix ?? '').'attachment_new_filenames';
        $descriptions_field = $attachment_descriptions_field ?? ($attachments_prefix ?? '').'attachment_descriptions';
        $attachment_block_name = ($attachments_prefix ?? '').'attachment_block_';

        if (!$r_attachments) {
            return $a_result_errors;
        }

        $bytes = $ticket->allAttachments()->sum('bytes');
        $num = $ticket->allAttachments()->count();
        if (!isset($comment)) {
            $comment = false;
        }
        $block = $comment ? $comment->attachments()->count() : $ticket->attachments()->count();

        $new_bytes = 0;

        $index = 0;
        $a_errors = [];

        foreach ($r_attachments as $uploadedFile) {
            /** @var UploadedFile $uploadedFile */
            if (is_null($uploadedFile)) {
                // No files attached
                $a_errors[$attachment_block_name.($block + $index)] = trans('panichd::lang.ticket-error-not-valid-file');
                $index++;
                continue;
            }

            if (!$uploadedFile instanceof UploadedFile) {
                $a_errors[$attachment_block_name.($block + $index)] = trans('panichd::lang.ticket-error-not-valid-object', ['name'=>print_r($uploadedFile, true)]);
                $index++;
                continue;
            }

            $original_filename = $uploadedFile->getClientOriginalName() ?: '';

            // Denied uploads block process
            if (is_array($request->{($attachments_prefix ?? '').'block_file_names'}) and in_array($original_filename, $request->{($attachments_prefix ?? '').'block_file_names'})) {
                $index++;
                continue;
            }

            $new_bytes = $bytes + $uploadedFile->getSize();

            if ($new_bytes / 1024 / 1024 > Setting::grab('attachments_ticket_max_size')) {
                $a_errors[$attachment_block_name.($block + $index)] = trans('panichd::lang.ticket-error-max-size-reached', [
                    'name'         => $original_filename,
                    'available_MB' => round(Setting::grab('attachments_ticket_max_size') - $bytes / 1024 / 1024),
                ]);
                $index++;
                continue;
            }
            $bytes = $new_bytes;

            if ($num + 1 > Setting::grab('attachments_ticket_max_files_num')) {
                $a_errors[$attachment_block_name.($block + $index)] = trans('panichd::lang.ticket-error-max-attachments-count-reached', [
                    'name'     => $original_filename,
                    'max_count'=> Setting::grab('attachments_ticket_max_files_num'),
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
            if ($comment) {
                $attachment->comment_id = $comment->id;
                $attachment->uploaded_by_id = $comment->user_id;
            } else {
                $attachment->uploaded_by_id = $ticket->user_id;
            }
            $attachment->original_filename = $original_filename;

            // Mimetype
            $validator = Validator::make(['file' => $uploadedFile], ['file' => 'mimes:'.Setting::grab('attachments_mimes')]);

            if ($validator->fails()) {
                $a_errors[$attachment_block_name.($block + $index)] = trans('panichd::lang.attachment-update-not-valid-mime', ['file' => $original_filename]);
                $index++;
                continue;
            }

            // New attachments edited fields
            $a_fields = $a_single_errors = [];
            if (isset($request->input($filenames_field)[$index])) {
                $a_fields['new_filename'] = [
                    'name'  => 'new_attachment_new_filename_'.$index, // Not real request input
                    'value' => $request->input($filenames_field)[$index],
                ];
            } else {
                $attachment->new_filename = $original_filename;
            }

            if (isset($request->input($descriptions_field)[$index])) {
                $a_fields['description'] = [
                    'name'  => 'new_attachment_description_'.$index, // Not real request input
                    'value' => $request->input($descriptions_field)[$index],
                ];
            }

            if ($a_fields) {
                $this->updateSingleAttachment($attachment, $a_fields, $a_single_errors);
            }

            if ($a_single_errors) {
                $a_errors[$attachment_block_name.($block + $index)] = implode('. ', $a_single_errors);
                $index++;
                continue;
            }

            $attachment->bytes = $uploadedFile->getSize();
            $attachment->mimetype = $uploadedFile->getMimeType() ?: '';
            $attachment->file_path = $file_directory.DIRECTORY_SEPARATOR.$file_name;
            $attachment->original_attachment = $file_name;

            // Thumbnail for valid image types
            $validator = Validator::make(['file' => $uploadedFile], ['file' => 'mimes:jpeg,png,gif,wbmp,webp,xbm,xpm']);

            $is_image = $validator->fails() ? false : true;

            // Should be called when you no need anything from this file, otherwise it fails with Exception that file does not exists (old path being used)
            $uploadedFile->move(storage_path($attachments_path), $file_name);

            if ($is_image) {
                $img = Image::make($attachment->file_path);

                // Image sizes field
                $attachment->image_sizes = $img->width().'x'.$img->height();

                // Image thumbnail
                $this->makeThumbnailFromImage($img, $file_name);
            }

            $attachment->save();

            $index++;
        }

        return $this->return_ajax_errors($a_result_errors, $a_errors);
    }

    private function return_ajax_errors($a_result_errors, $a_errors)
    {
        if ($a_errors) {
            $a_messages = array_values($a_errors);

            $a_result_errors['messages'] = ($a_result_errors and isset($a_result_errors['messages'])) ? array_merge($a_result_errors['messages'], $a_messages) : $a_messages;
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
        $thumbnail_path = storage_path('app'.DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.Setting::grab('thumbnails_path').DIRECTORY_SEPARATOR);

        // Delete previous thumbnail if present
        if (\File::exists($thumbnail_path.$file_name)) {
            \File::delete($thumbnail_path.$file_name);
        }

        $img->heighten(50)->widen(50)->encode('png')->resizeCanvas(50, 50)->save($thumbnail_path.$file_name);

        // The alternative method below seems to cut borders on non square images. Image loses a lot of quality also
        /*$thumb = Image::canvas(50, 50);
        $thumb->insert($img, 'center')->save($thumbnail_path.$file_name);*/
    }

    /**
     * Updates new_filename and description. Applies to all existent files. Not the ones uploaded in current request.
     */
    protected function updateAttachments($request, $a_result_errors, $attachments)
    {
        $a_errors = [];

        $index = 0;
        foreach ($attachments as $att) {
            // Don't update files marked for deletion
            if (is_array($request->delete_files) and in_array($att->id, $request->delete_files)) {
                $index++;
                continue;
            }

            $new_filename = $description = $save = false;
            $a_fields = $a_single_errors = [];

            // New filename
            $field = 'attachment_'.$att->id.'_new_filename';
            if ($request->input($field) != '') {
                $a_fields['new_filename'] = [
                    'name'  => $field,
                    'value' => $request->input($field),
                ];
            }

            // Description
            $field = 'attachment_'.$att->id.'_description';
            if ($request->input($field) != '') {
                $a_fields['description'] = [
                    'name'  => $field,
                    'value' => $request->input($field),
                ];
            }

            // Image cropping
            $field = 'attachment_'.$att->id.'_image_crop';
            if ($request->input($field) != '') {
                $a_fields['image_crop'] = $request->input($field);
                $original_filename = basename($att->file_path);
            }

            if ($a_fields) {
                $this->updateSingleAttachment($att, $a_fields, $a_single_errors);
            }

            if ($a_single_errors) {
                $a_errors['attachment_block_'.$index] = implode('. ', $a_single_errors);
            } else {
                // Save attachment
                $att->save();

                if ($a_fields and isset($a_fields['image_crop'])) {
                    // Update related Ticket or comment Html field
                    $model = $att->comment_id == '' ? $att->ticket : $att->comment;
                    $model->html = str_replace($original_filename, basename($att->file_path), $model->html);
                    if ($att->comment_id == '') {
                        $model->intervention_html = str_replace($original_filename, basename($att->file_path), $model->intervention_html);
                    }
                    $model->save();
                }
            }
            $index++;
        }

        return $this->return_ajax_errors($a_result_errors, $a_errors);
    }

    /**
     * Updates new_filename and description for an Attachment instance.
     */
    protected function updateSingleAttachment(&$att, $a_fields, &$a_single_errors)
    {
        extract($a_fields);

        // New Filename
        if (isset($new_filename)) {
            $filtered = trim(Purifier::clean($new_filename['value'], ['HTML.Allowed' => '']), chr(0xC2).chr(0xA0)." \t\n\r\0\x0B");

            $validator = Validator::make([$new_filename['name'] => $filtered], [$new_filename['name'] => 'required|min:3']);

            if ($validator->fails()) {
                $a_single_errors[] = trans('panichd::lang.attachment-update-not-valid-name', ['file' => $att->original_filename]);
            } elseif ($filtered != $att->new_filename) {
                $att->new_filename = $filtered;
            }
        }

        // Description
        if (isset($description)) {
            $filtered = trim(Purifier::clean($description['value'], ['HTML.Allowed' => '']), chr(0xC2).chr(0xA0)." \t\n\r\0\x0B");

            if ($filtered != '') {
                if ($filtered == $att->new_filename) {
                    $a_single_errors[] = trans('panichd::lang.attachment-error-equal-name', ['file' => $att->original_filename]);
                } else {
                    $att->description = $filtered;
                }
            }
        }

        // Image crop
        if (!$a_single_errors and isset($image_crop)) {
            $coords = explode(',', $image_crop);
            if (count($coords) != 4 or ctype_digit(str_replace(',', '', str_replace('.', '', $coords)))) {
                $a_single_errors[] = trans('panichd::lang.attachment-update-crop-error');
            } else {
                $img = Image::make($att->file_path);

                // New filename
                $new_filename = $this->makeFilename($att->new_filename.date('YmdHis', time()), $att->ticket_id, ($att->comment_id ? $att->comment_id : ''));
                $new_file_path = storage_path(Setting::grab('attachments_path')).DIRECTORY_SEPARATOR.$new_filename;

                // Resize and save image
                $img->crop(intval($coords[2] - $coords[0]), intval($coords[3] - $coords[1]), intval($coords[0]), intval($coords[1]))->save($new_file_path);
                $att->image_sizes = $img->width().'x'.$img->height();

                // Create new thumbnail
                $this->makeThumbnailFromImage($img, $new_filename);

                // Delete image if it's not the original one
                if ($att->original_attachment != basename($att->file_path)) {
                    // Delete image
                    $error = $this->deleteAttachmentFile($att->file_path, $att->original_filename);
                    if ($error) {
                        $a_single_errors[] = $error;
                    }
                }

                // Delete old thumbnail
                $this->deleteThumbnail(basename($att->file_path));

                // Updated fields
                $att->file_path = $new_file_path;
            }
        }
    }

    protected function destroyAttachmentIds($a_id)
    {
        $attachments = Attachment::whereIn('id', $a_id)->get();

        return $this->destroyAttachmentsLoop($attachments);
    }

    /**
     * Iterates through selected $attachments as instances of Attachment model.
     *
     * @param $ticket instance of PanicHD\PanicHD\Models\Ticket
     *
     * @return string
     * @return bool
     */
    protected function destroyAttachmentsLoop($attachments)
    {
        $delete_errors = [];

        foreach ($attachments as $attachment) {
            $error = $attachment->delete();

            if ($error and $error != 1) {
                $delete_errors[] = $error;
            }
        }

        if ($delete_errors) {
            return trans('panichd::lang.ticket-error-delete-files').trans('panichd::lang.colon').implode('. ', $delete_errors);
        } else {
            return false;
        }
    }

    /**
     * Destroy a single attachment files and model instance.
     */
    protected function destroyAttachedElement($att)
    {
        // Delete attachment file
        $error = $this->deleteAttachmentFile($att->file_path, $att->original_filename);
        if ($error) {
            return $error;
        }

        // Delete original file (if exists)
        if ($att->original_attachment != basename($att->file_path)) {
            $original_path = pathinfo($att->file_path, PATHINFO_DIRNAME).DIRECTORY_SEPARATOR.$att->original_attachment;
            $error = $this->deleteAttachmentFile($original_path, $att->original_filename);
            if ($error) {
                return $error;
            }
        }

        // Delete thumbnail
        $this->deleteThumbnail(basename($att->file_path));

        // Remove thumbnail link from any html field
        $model = $att->comment_id == '' ? $att->ticket : $att->comment;
        $model->html = preg_replace('/<a[^>]*summernote_thumbnail_link[^>]*?><img[^>]*'.basename($att->file_path).'[^>]*><\/a>/', '', $model->html);

        if ($att->comment_id == '') {
            $model->intervention_html = preg_replace('/<a[^>]*summernote_thumbnail_link[^>]*?><img[^>]*'.basename($att->file_path).'[^>]*><\/a>/', '', $model->intervention_html);
        }
        $model->save();

        return false;
    }

    // Delete attachment file
    protected function deleteAttachmentFile($file_path, $filename)
    {
        $error = false;

        if (!\File::exists($file_path)) {
            \Log::info(trans('panichd::lang.ticket-error-file-not-found', ['name'=>$filename]));
        } else {
            \File::delete($file_path);

            if (\File::exists($file_path)) {
                $error = trans('panichd::lang.ticket-error-file-not-deleted', ['name'=>$filename]);
            }
        }

        return $error;
    }

    // Delete thumbnail file
    protected function deleteThumbnail($file_name)
    {
        $thumbnail_path = storage_path('app'.DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.Setting::grab('thumbnails_path').DIRECTORY_SEPARATOR);

        if (\File::exists($thumbnail_path.$file_name)) {
            \File::delete($thumbnail_path.$file_name);
        }

        return \File::exists($thumbnail_path.$file_name) ? false : true;
    }
}
