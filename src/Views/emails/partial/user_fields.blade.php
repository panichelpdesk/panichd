@if (is_object($original_ticket) && is_object($original_ticket->owner) && is_object($ticket) && is_object($ticket->owner))
	<ul><li>
		<b>{{ trans('panichd::lang.list') . trans('panichd::lang.colon') }}</b>
		@if($original_ticket->isComplete() != $ticket->isComplete())
			<strike>{{ trans('panichd::lang.'.($original_ticket->isComplete() ? 'complete-tickets-adjective' : 'active-tickets-adjective')) }}</strike> 
		@endif
		<span>{{ trans('panichd::lang.'.($ticket->isComplete() ? 'complete-tickets-adjective' : 'active-tickets-adjective')) }}</span>
	</li><li>
		<b>{{ trans('panichd::lang.status') . trans('panichd::lang.colon') }}</b>
		@if($original_ticket->status->id != $ticket->status->id)
			<strike>{{ $original_ticket->status->name }}</strike> 
		@endif
		<span style="color: {{ $ticket->status->color }};">{{ $ticket->status->name }}</span>
	</li></ul>
@endif