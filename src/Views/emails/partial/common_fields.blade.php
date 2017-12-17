@if (is_object($original_ticket) && is_object($original_ticket->owner) && is_object($ticket) && is_object($ticket->owner))
	<ul>
	<li>
		<b>{{ trans('panichd::lang.owner') . trans('panichd::lang.colon') }}</b>
		@if ($original_ticket->owner->id != $ticket->owner->id)
			<strike>{{ $original_ticket->owner->name }}</strike> 
		@endif
		{{ $ticket->owner->name }}
	</li><li>
		<b>{{ trans('panichd::lang.list') . trans('panichd::lang.colon') }}</b>
		@if($original_ticket->isComplete() != $ticket->isComplete())
			<strike>{{ trans('panichd::lang.'.($original_ticket->isComplete() ? 'complete-tickets-adjective' : 'active-tickets-adjective')) }}</strike> 
		@endif
		<span>{{ trans('panichd::lang.'.($ticket->isComplete() ? 'complete-tickets-adjective' : 'active-tickets-adjective')) }}</span>
	</li><li>
		<b>{{ trans('panichd::lang.status') . trans('panichd::lang.colon') }}</b>
		@if ($original_ticket->status->id != $ticket->status->id)
			<strike>{{ $original_ticket->status->name }}</strike> 
		@endif
		<span style="color: {{ $ticket->status->color }};">{{ $ticket->status->name }}</span>
	</li><li>
		<b>{{ trans('panichd::lang.priority') . trans('panichd::lang.colon') }}</b>
		@if ($original_ticket->priority->id != $ticket->priority->id)
			<strike>{{ $original_ticket->priority->name }}</strike> 
		@endif
		<span style="color: {{ $ticket->priority->color }};">{{ $ticket->priority->name }}</span>
	</li><li>
		<b>{{ trans('panichd::lang.category') . trans('panichd::lang.colon') }}</b>
		@if ($original_ticket->category->id != $ticket->category->id)
			<strike>{{ $original_ticket->category->name }}</strike> 
		@endif
		<span style="color: {{ $ticket->category->color }}">{{ $ticket->category->name }}</span>
	</li><li>
		<b>{{ trans('panichd::lang.agent') . trans('panichd::lang.colon') }}</b>
		@if ($original_ticket->agent->id != $ticket->agent->id)
			<strike>{{ $original_ticket->agent->name }}</strike> 
		@endif
		{{ $ticket->agent->name }}
	</li><li>
		<b>{{ trans('panichd::lang.subject') . trans('panichd::lang.colon') }}</b>{{ $ticket->subject }}
	</li>
	</ul>
@endif