@if(!$comments->isEmpty())
    @foreach($comments as $comment)
        <?php
			$glyphicon = "glyphicon-envelope text-warning";
			$title = "";
			switch ($comment->type){
				case 'note':
					$glyphicon = "glyphicon-pencil text-info";
					break;
				case 'complete':
					$glyphicon = "glyphicon-ok-circle text-success";
					break;
				case 'reopen':
					$glyphicon = "glyphicon-backward text-warning";
					break;
			}		
		?>	
		<div class="panel {!! $comment->user->tickets_role ? "panel-info" : "panel-default" !!}">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <span class="tooltip-info" data-toggle="tooltip" data-placement="auto bottom" title="{{ trans('ticketit::lang.ticket-comment-type-'.$comment->type) }}"><span class="glyphicons glyphicon {{ $glyphicon }}" aria-hidden="true"></span> {!! $comment->user->name !!}</span>                   
					
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
                <div class="row">
                    <div class="{{ $setting->grab('ticket_attachments_feature') && $comment->attachments->count() > 0 ? 'col-sm-7' : 'col-sm-12' }}"><p id="jquery_comment_edit_{{$comment->id}}"> {!! $comment->html !!} </p>
					@if ($u->canManageTicket($ticket->id))
						@include('ticketit::tickets.partials.modal_comment_edit')
					@endif
					</div>
					@if($setting->grab('ticket_attachments_feature') && $comment->attachments->count() > 0)
						<div class="col-sm-5 attached_list">
							@foreach($comment->attachments as $attachment)
								@include('ticketit::tickets.partials.attachment', ['attachment' => $attachment])
							@endforeach
						</div>
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