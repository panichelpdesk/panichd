<?php 
	$notification_owner = unserialize($notification_owner);
	$comment = unserialize($comment);
	$original_ticket = $ticket = unserialize($ticket);
	$email_from = unserialize($email_from);
?>

@extends($email)

@section('content')
	@if($notification_type == 'reply')
		<p>{!! trans('ticketit::email/globals.added_reply', ['user' => $notification_owner->name]) !!}</p>
	@elseif($notification_type == 'note')
		<p>{!! trans('ticketit::email/globals.added_note', ['user' => $notification_owner->name]) !!}</p>
	@endif

	@if ($recipient->levelInCategory($ticket->category->id) > 1)
		@include('ticketit::emails.partial.common_fields')
		<b>{{ trans('ticketit::email/globals.new_' . $notification_type . '_title') }}</b>
		<table border="0" cellpadding="10" cellspacing="0" style="border: 1px solid #ddd; border-radius: 5px;"><tr>
			<td>@include ('ticketit::emails.templates.html_field', ['html_field' => $comment->html])</td>
		</tr></table><br /><br />
	@endif
@stop
