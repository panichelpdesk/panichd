@if (isset($attach_id) && $attach_id != "")
	<button type="button" class="btn btn-light btn-default btn_attach" data-attach-id="{{ $attach_id }}" data-attachments_prefix="{{ $attachments_prefix ?? '' }}" style="margin: 0em 0em 1em 0em;">
		<span class="fa fa-file" aria-hidden="true"></span> {{ trans('panichd::lang.attach-files') }}</span>
	</button>
@endif