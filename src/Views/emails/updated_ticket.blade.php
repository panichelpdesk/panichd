<?php 
	$notification_owner = unserialize($notification_owner);
	$ticket = unserialize($ticket);
	$original_ticket = unserialize($original_ticket);
	$email_from = unserialize($email_from);
?>

@extends($email)

@section('content')
	@if($change == 'close')
		<p>{!! trans('ticketit::email/globals.closed_ticket', ['user' => $notification_owner->name]) !!}</p>
	@elseif($change == 'status')
		<p>{!! trans('ticketit::email/globals.updated_status', ['user' => $notification_owner->name]) !!}</p>
	@elseif ($change == 'agent')
		<p>{!! trans('ticketit::email/globals.updated_agent', ['user' => $notification_owner->name]) !!}</p>
	@endif
	
	@include('ticketit::emails.partial.common_fields')
	@include('ticketit::emails.partial.both_html_fields')
@stop