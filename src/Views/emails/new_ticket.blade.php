<?php
	$notification_owner = unserialize($notification_owner);
	$ticket = unserialize($ticket);
	$email_from = unserialize($email_from);
?>
@extends($email)

@section('content')
	@if ($u->currentLevel() > 1)
		<p>{!! trans('ticketit::email/globals.agent_new_ticket', ['agent' => $notification_owner->name]) !!}</p>
	@else
		<p>{!! trans('ticketit::email/globals.user_new_ticket', ['user' => $notification_owner->name]) !!}</p>
	@endif
	
	<ul>
	<li><b>{{ trans('ticketit::lang.owner') . trans('ticketit::lang.colon') }}</b>{{ $ticket->owner->name }}</li>
	@if ($ticket->isComplete())
		<li><b>{{ trans('ticketit::lang.status') . trans('ticketit::lang.colon') }}</b><span style="color: #68c240;">{{ trans('ticketit::lang.complete') }}</span></li>
	@endif
	<li><b>{{ trans('ticketit::lang.category') . trans('ticketit::lang.colon') }}</b><span style="color: {{ $ticket->category->color }};">{{ $ticket->category->name }}</span></li>
	<li><b>{{ trans('ticketit::lang.subject') . trans('ticketit::lang.colon') }}</b>{{ $ticket->subject }}</li>
	</ul>
	@include('panichd::emails.partial.both_html_fields')
@stop