@if (is_object($original_ticket) && is_object($original_ticket->owner) && is_object($ticket) && is_object($ticket->owner))
	<ul>	
	<li>
		<b>{{ trans('ticketit::lang.owner') . trans('ticketit::lang.colon') }}</b>
		@if ($original_ticket->owner->id != $ticket->owner->id)
			<strike>{{ $original_ticket->owner->name }}</strike> 
		@endif
		{{ $ticket->owner->name }}
	</li><li>
		<b>{{ trans('ticketit::lang.status') . trans('ticketit::lang.colon') }}</b>
		@if ($original_ticket->status->id != $ticket->status->id)
			<strike>{{ $original_ticket->status->name }}</strike> 
		@endif
		<span style="color: {{ $ticket->status->color }};">{{ $ticket->status->name }}</span>
	</li><li>
		<b>{{ trans('ticketit::lang.priority') . trans('ticketit::lang.colon') }}</b>
		@if ($original_ticket->priority->id != $ticket->priority->id)
			<strike>{{ $original_ticket->priority->name }}</strike> 
		@endif
		<span style="color: {{ $ticket->priority->color }};">{{ $ticket->priority->name }}</span>
	</li><li>
		<b>{{ trans('ticketit::lang.category') . trans('ticketit::lang.colon') }}</b>
		@if ($original_ticket->category->id != $ticket->category->id)
			<strike>{{ $original_ticket->category->name }}</strike> 
		@endif
		<span style="color: {{ $ticket->category->color }}">{{ $ticket->category->name }}</span>
	</li><li>
		<b>{{ trans('ticketit::lang.agent') . trans('ticketit::lang.colon') }}</b>
		@if ($original_ticket->agent->id != $ticket->agent->id)
			<strike>{{ $original_ticket->agent->name }}</strike> 
		@endif
		{{ $ticket->agent->name }}
	</li><li>
		<b>{{ trans('ticketit::lang.subject') . trans('ticketit::lang.colon') }}</b>{{ $ticket->subject }}
	</li>
	</ul>
@endif