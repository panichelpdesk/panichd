<div class="panel panel-default check_parent unchecked check_related_bg">
	<div class="panel-body">
		<div class="media">
		    <div class="media-left">
		        <i class="glyphicon glyphicon-paperclip"></i>
		    </div>
		    <div class="media-body check_related_text">
		        <h4 class="media-heading">{{ $attachment->original_filename }}</h4>
		
		        {!! link_to_route($setting->grab('main_route').'.download-attachment', trans('ticketit::lang.btn-download'), [$attachment->id]) !!}
		        <span class="text-muted">
		            {{ number_format($attachment->bytes/1024) }} Kb,
		            {{ $attachment->mimetype }}
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
