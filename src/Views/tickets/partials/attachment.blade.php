<div class="panel panel-default text-default check_parent unchecked check_related_bg">
	<div class="panel-body">
		<div class="media">
		    <div class="media-left">
		        <a href="{{ URL::route($setting->grab('main_route').'.download-attachment', [$attachment->id]) }}" title="{{ trans('ticketit::lang.btn-download') . " " . $attachment->new_filename }}">
					<i class="glyphicon glyphicon-paperclip"></i>
				</a>
				
		    </div>
		    <div class="media-body check_related_text">
		        <div>
				<span id="attachment_{{ $attachment->id }}_display_new_filename">{{ $attachment->new_filename }}</span>
				@if (isset($template) && $template == "createedit")
					<s id="attachment_{{ $attachment->id }}_display_original_filename">
					@if ($attachment->original_filename != $attachment->new_filename)
						 - {{ $attachment->original_filename }}
					@endif
					</s>
				@endif
				@if($u->currentLevel() > 1 && isset($template) && $template == "createedit")
					<button type="button" role="button" class="btn btn-default btn-xs
					@if (isset($edit_div) && isset($back_div))
						 edit_attachment" data-edit-div="{{ $edit_div }}" data-back-div="{{ $back_div }}"
					@else
						" data-toggle="modal" data-target="#modal-attachment-edit"
					@endif
					data-original_filename="{{ $attachment->original_filename }}" data-prefix="attachment_{{ $attachment->id }}_" style="margin: 0em 0em 0em 1em;">{{ trans('ticketit::lang.btn-edit') }}</button>
					<input type="hidden" id="attachment_{{ $attachment->id }}_new_filename" name="attachment_{{ $attachment->id }}_new_filename" value="{{ $attachment->new_filename }}">
					<input type="hidden" id="attachment_{{ $attachment->id }}_description" name="attachment_{{ $attachment->id }}_description" value="{{ $attachment->description }}">
				@endif
				</div>
		
		        
		        <span class="text-muted">
					{{ number_format($attachment->bytes/1024) }} KB - 
					<span id="attachment_{{ $attachment->id }}_display_description" data-mimetype="{{ $attachment->mimetype }}">
					@if($attachment->description == "")
						{{ $attachment->mimetype }}
					@else
						{{ $attachment->description }}
					@endif
					</span>
		        </span>
		    </div>
			@if (isset($template) && $template == "createedit")
				<div class="media-right media-middle">
					<a href="#" class="check_button" data-delete_id="delete_attached_check_{{ $loop->index }}"><span class="media-object pull-right glyphicon glyphicon-remove" aria-hidden="true"></span><span class="media-object  pull-right glyphicon glyphicon-ok" aria-hidden="true" style="display: none"></span></a>
					<input type="checkbox" id="delete_attached_check_{{ $loop->index }}" name="delete_files[]" value="{{ $attachment->id }}" checked="checked" style="display: none" disabled="disabled">
				</div>
			@endif
		</div>
	</div>
</div>
