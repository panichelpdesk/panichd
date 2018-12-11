<?php
	$notification_owner = unserialize($notification_owner);
	$comment = unserialize($comment);
	$original_comment = unserialize($original_comment);
	$original_ticket = $ticket = unserialize($ticket);
	$email_from = unserialize($email_from);
?>

@extends($email)

@section('content')
	@if($comment->owner->id == $notification_owner->id)
		<p>{!! trans('panichd::email/globals.updated_agent_note', ['user' => $notification_owner->name]) !!}</p>
	@elseif($recipient->id == $comment->owner->id)
		<p>{!! trans('panichd::email/globals.updated_your_note', ['user' => $notification_owner->name]) !!}</p>
	@else
		<p>{!! trans('panichd::email/globals.updated_other_note', ['user' => $notification_owner->name, 'other' => $comment->owner->name]) !!}</p>
	@endif
	@if ($recipient->levelInCategory($ticket->category->id) > 1)
		@include('panichd::emails.partial.common_fields')
	@endif
	<b>{{ trans('panichd::email/globals.original_note_title') }}</b>
	<table border="0" cellpadding="10" cellspacing="0" style="border: 1px solid #ddd; border-radius: 5px;"><tr>
		<td>@include('panichd::emails.partial.html_field', ['html_field' => $original_comment->html])</td>
	</tr></table><br /><br />
	<b>{{ trans('panichd::email/globals.updated_note_title') }}</b>
	<table border="0" cellpadding="10" cellspacing="0" style="border: 1px solid #ddd; border-radius: 5px;"><tr>
		<td>@include('panichd::emails.partial.html_field', ['html_field' => $comment->html])</td>
	</tr></table><br /><br />

@stop
