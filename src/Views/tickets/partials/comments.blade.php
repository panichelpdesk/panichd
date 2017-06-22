@if(!$comments->isEmpty())
    @foreach($comments as $comment)
        <div class="panel {!! $comment->user->tickets_role ? "panel-info" : "panel-default" !!}">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <span class="glyphicons {{ $comment->type=='note' ? 'glyphicon glyphicon-pencil text-info' : 'glyphicon glyphicon-envelope text-warning'}}" aria-hidden="true"></span> {!! $comment->user->name !!}                    
					
					<span class="pull-right">
					@if ($comment->created_at!=$comment->updated_at)
						<span class="glyphicon glyphicon-pencil" aria-hidden="true" style="color: gray"></span>
					@endif
					{!! $comment->updated_at->diffForHumans() !!} 
					@if ($u->canManageTicket($ticket->id) and $comment->type=='note')
						<button type="button" class="btn btn-default btn-sm comment_deleteit"  data-toggle="modal" data-target="#modal-comment-delete" data-id="{{$comment->id}}" data-text="{{$comment->user->name}}" title="{{ trans('ticketit::lang.show-ticket-delete-comment') }}">
						<span class="glyphicon glyphicon-remove" aria-label="{{ trans('ticketit::lang.btn-delete') }}" style="color: gray"></span></button>
					@endif
					</span>
                </h3>
            </div>
            <div class="panel-body">
                <div class="content">
                    <p id="jquery_comment_edit_{{$comment->id}}"> {!! $comment->html !!} </p>
					@if ($u->canManageTicket($ticket->id))
						@include('ticketit::tickets.partials.note_edit')
					@endif
                </div>
				@if ($u->canManageTicket($ticket->id))
					@if ($comment->type=='note')
						<button type="button" class="btn btn-default btn-sm" data-toggle="modal" data-target="#comment-modal-edit-{{$comment->id}}">{{ trans('ticketit::lang.show-ticket-edit-comment') }}</button>
					@elseif($comment->type=='reply')
						<button type="button" class="btn btn-default btn-sm" data-toggle="modal" data-target="#email-resend-modal" data-id="{{$comment->id}}" data-owner="{{$ticket->user->name}}">{{ trans('ticketit::lang.show-ticket-email-resend') }}</button>
					@endif
				
				@endif
            </div>
        </div>
    @endforeach
	
	{!! CollectiveForm::open([
		'method' => 'DELETE',
		'route' => [
					$setting->grab('main_route').'-comment.destroy',
					'action_comment_id'
					],
		'data-default-action' => '',
		'id' => "delete-comment-form"
		])
	!!}
	{!! CollectiveForm::close() !!}
	@include('ticketit::tickets.partials.email_resend')
	@include('ticketit::tickets.partials.modal_comment_delete')
@endif