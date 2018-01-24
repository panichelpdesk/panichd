<?php 
	$notification_owner = unserialize($notification_owner);
	$ticket = unserialize($ticket);
	$original_ticket = unserialize($original_ticket);
	$email_from = unserialize($email_from);
?>

@extends($email)

@section('content')
	<p>{!! trans('panichd::email/globals.closed_ticket', ['user' => $notification_owner->name]) !!}</p>
	
	@if ($recipient->levelInCategory($ticket->category->id) > 1)
		@include('panichd::emails.partial.common_fields')
		@include('panichd::emails.partial.both_html_fields')
	@endif
@stop