@if (isset($attach_id) && $attach_id != "")
	<button type="button" class="btn btn-default btn_attach" data-attach-id="{{ $attach_id }}" style="margin: 0em 0em 1em 0em;"><span class="glyphicon glyphicon-file" aria-hidden="true"></span> {{ trans('ticketit::lang.attach-files') }}</span></button>
@endif