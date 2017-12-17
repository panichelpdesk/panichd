<?php $notification_owner = unserialize($notification_owner);?>
<?php $original_ticket = unserialize($original_ticket);?>
<?php $ticket = unserialize($ticket);?>

@extends($email)

@section('subject')
	{{ trans('panichd::email/globals.status') }}
@stop

@section('link')
	<a style="color:#ffffff" href="{{ route($setting->grab('main_route').'.show', $ticket->id) }}">
		{{ trans('panichd::email/globals.view-ticket') }}
	</a>
@stop

@section('content')
	{!! trans('panichd::email/status.data', [
	    'name'        =>  $notification_owner->name,
	    'subject'     =>  $ticket->subject,
	    'old_status'  =>  $original_ticket->status->name,
	    'new_status'  =>  $ticket->status->name
	]) !!}
@stop
