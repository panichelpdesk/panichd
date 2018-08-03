<?php
	$notification_owner = unserialize($notification_owner);
	$ticket = unserialize($ticket);
	$email_from = unserialize($email_from);
?>
@extends($email)

@section('content')
	@if ($current_level > 1)
		<p>{!! trans('panichd::email/globals.agent_new_ticket', ['agent' => $notification_owner->name]) !!}</p>
	@else
		<p>{!! trans('panichd::email/globals.user_new_ticket', ['user' => $notification_owner->name]) !!}</p>
	@endif
	
	<ul>
	<li><b>{{ trans('panichd::lang.owner') . trans('panichd::lang.colon') }}</b>{{ $ticket->owner->name }}</li>
	@if ($ticket->isComplete())
		<li><b>{{ trans('panichd::lang.status') . trans('panichd::lang.colon') }}</b><span style="color: #68c240;">{{ trans('panichd::lang.complete') }}</span></li>
	@endif
	<li><b>{{ trans('panichd::lang.category') . trans('panichd::lang.colon') }}</b><span style="color: {{ $ticket->category->color }};">{{ $ticket->category->name }}</span></li>
	<li><b>{{ trans('panichd::lang.subject') . trans('panichd::lang.colon') }}</b>{{ $ticket->subject }}</li>
	</ul>
	@include('panichd::emails.partial.both_html_fields')
@stop