<div class=""><!-- for jquery_error -->
<div class="jquery_attachment_block card bg-default text-default check_parent unchecked check_related_bg">
	<div class="card-body">
		<div class="media">
		    <div class="media-left mr-3">
				<?php
					$mime = $attachment->getShorthandMime($attachment->mimetype);
				?>

				@if (in_array($mime, ['image','pdf']))
					<a href="{{ URL::route($setting->grab('main_route').'.view-attachment', [$attachment->id]) }}" title="{{ $attachment->new_filename }}"
					@if($mime == 'image')
						class="{{ $mime }} pwsp_gallery_link" data-pwsp-pid="{{ $attachment->id }}"
					@else
						class="{{ $mime }}"
					@endif
					>
				@else
					<a href="{{ URL::route($setting->grab('main_route').'.download-attachment', [$attachment->id]) }}" title="{{ trans('panichd::lang.btn-download') . " " . $attachment->new_filename }}" class="{{ $mime }}">
				@endif

				@if ($mime == 'image')
					@if (\File::exists(storage_path('app'.DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.$setting->grab('thumbnails_path').DIRECTORY_SEPARATOR).basename($attachment->file_path)))
						<img width="40px" height="40px" src="{{ URL::to('/').'/storage/'.$setting->grab('thumbnails_path').'/'.basename($attachment->file_path) }}">
					@else
						<i class="fa fa-file-image fa-2x" aria-hidden="true"></i>
					@endif
				@elseif ($mime == 'pdf')
					<i class="fa fa-file-pdf fa-2x" aria-hidden="true"></i>
				@elseif ($mime == 'msword')
					<i class="fa fa-file-word fa-2x" aria-hidden="true"></i>
				@elseif ($mime == 'msexcel')
					<i class="fa fa-file-excel fa-2x" aria-hidden="true"></i>
				@elseif ($mime == 'compressed')
					<i class="fa fa-file-archive fa-2x" aria-hidden="true"></i>
				@else
					<i class="fa fa-file fa-2x" aria-hidden="true"></i>
				@endif
				</a>
		    </div>
		    <div class="media-body check_related_text">
		        <div>
				<span id="attachment_{{ $attachment->id }}_display_new_filename">{{ $attachment->new_filename }}</span>
				@if (isset($template) && $template == "createedit")
					<s id="attachment_{{ $attachment->id }}_display_original_filename" class="jquery_scissors_previous">
					@if ($attachment->original_filename != $attachment->new_filename)
						 - {{ $attachment->original_filename }}
					@endif
					</s>
				@endif
				@if($u->currentLevel() > 1 && isset($template) && $template == "createedit")
					<button type="button" role="button" class="btn btn-light btn-xs edit_attachment"
					@if (isset($edit_div) && isset($back_div))
						data-edit-div="{{ $edit_div }}" data-back-div="{{ $back_div }}"
					@else
						data-modal-id="modal-attachment-edit"
					@endif
					@if($mime == 'image')
						data-image-url="{{ URL::route($setting->grab('main_route').'.view-attachment', [$attachment->id]) }}" data-image-sizes="{{ $attachment->image_sizes }}"
					@endif
					data-original_filename="{{ $attachment->original_filename }}" data-prefix="attachment_{{ $attachment->id }}_" style="margin: 0em 0em 0em 1em;">{{ trans('panichd::lang.btn-edit') }}</button>
					<input type="hidden" id="attachment_{{ $attachment->id }}_new_filename" name="attachment_{{ $attachment->id }}_new_filename" value="{{ $attachment->new_filename }}">
					<input type="hidden" id="attachment_{{ $attachment->id }}_description" name="attachment_{{ $attachment->id }}_description" value="{{ $attachment->description }}">
					@if($mime == 'image')
					<input type="hidden" id="attachment_{{ $attachment->id }}_image_crop" name="attachment_{{ $attachment->id }}_image_crop" value="">
					@endif
				@endif
				</div>


		        <span class="text-muted">
					<?php
						$size = $attachment->bytes/1024;
						$size = $size < 1024 ? number_format($size)." KB" : number_format($size/1024, 1)." MB";
					?>
					{{ $size }} -
					<span id="attachment_{{ $attachment->id }}_display_description" data-mimetype="{{ $attachment->mimetype }}">{{ $attachment->description }}</span>
		        </span>
		    </div>
			@if (isset($template) && $template == "createedit")
				<div class="media-right media-middle">
					<a href="#" class="check_button" data-delete_id="delete_attached_check_{{ $loop->index }}"><span class="media-object fa fa-times" aria-hidden="true"></span><span class="media-object fa fa-check" aria-hidden="true" style="display: none"></span></a>
					<input type="checkbox" id="delete_attached_check_{{ $loop->index }}" name="delete_files[]" value="{{ $attachment->id }}" checked="checked" style="display: none" disabled="disabled">
				</div>
			@endif
		</div>
	</div>
</div>
<div class="jquery_error_text"></div>
</div>
