@if(!$comments->isEmpty())
    @foreach($comments as $comment)
        <?php
			$comment_title = trans('panichd::lang.comment-'.$comment->type.'-title');
			
			switch ($comment->type){
				case 'note':
					$glyphicon = "glyphicon-pencil text-info";
					$comment_header = trans('panichd::lang.comment-note-from-agent', ['agent' => $comment->owner->name]);
					break;
				case 'complete':
					$glyphicon = "glyphicon-ok-circle text-success";
					$comment_header = trans('panichd::lang.comment-complete-by', ['owner' => $comment->owner->name]);
					break;
				case 'reopen':
					$glyphicon = "glyphicon-backward text-warning";
					$comment_header = trans('panichd::lang.comment-reopen-by', ['owner' => $comment->owner->name]);
					break;
				default:
					$glyphicon = "glyphicon-envelope ";
					if ($comment->owner->levelInCategory($comment->ticket->category->id) >= 2){
						$glyphicon .= "text-info";						
					}else{
						$glyphicon .= "text-warning";
					}
					if ($ticket->owner->id == $comment->owner->id){
						$comment_header = trans('panichd::lang.comment-reply-from-owner', ['owner' => $comment->owner->name]);
					}else{
						$comment_header = trans('panichd::lang.reply-from-owner-to-owner', [
							'owner1' => $comment->owner->name,
							'owner2' => $ticket->owner->name
						]);
					}					
					break;
			}		
		?>	
		<div class="panel {!! $comment->user->tickets_role ? "panel-info" : "panel-default" !!}">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <span class="tooltip-info" data-toggle="tooltip" data-placement="auto bottom" title="{{ $comment_title }}"><span class="glyphicons glyphicon {{ $glyphicon }}" aria-hidden="true"></span> {!! $comment_header !!}</span>
					<span class="pull-right">
					@if ($comment->created_at!=$comment->updated_at)
						<span class="glyphicon glyphicon-pencil" aria-hidden="true" style="color: gray"></span>
					@endif
					{!! $comment->updated_at->diffForHumans() !!} 
					@if ($u->currentLevel() > 1 && $u->canManageTicket($ticket->id) and $comment->type=='note')
						<button type="button" class="btn btn-default btn-sm comment_deleteit"  data-toggle="modal" data-target="#modal-comment-delete" data-id="{{$comment->id}}" data-text="{{$comment->user->name}}" title="{{ trans('panichd::lang.show-ticket-delete-comment') }}">
						<span class="glyphicon glyphicon-remove" aria-label="{{ trans('panichd::lang.btn-delete') }}" style="color: gray"></span></button>
					@endif
					</span>
                </h3>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="{{ $setting->grab('ticket_attachments_feature') && $comment->attachments->count() > 0 ? 'col-sm-7' : 'col-sm-12' }}"><div id="jquery_comment_edit_{{$comment->id}}" class="summernote-text-wrapper"> {!! $comment->html !!} </div>
					@if ($u->currentLevel() > 1 && $u->canManageTicket($ticket->id))
						@include('panichd::tickets.partials.modal_comment_edit')
					@endif
					</div>
					@if($setting->grab('ticket_attachments_feature') && $comment->attachments->count() > 0)
						<div class="col-sm-5 attached_list">
							@foreach($comment->attachments as $attachment)
								@include('panichd::tickets.partials.attachment', ['attachment' => $attachment])
							@endforeach
						</div>
					@endif
                </div>
				@if ($u->currentLevel() > 1 && $u->canManageTicket($ticket->id))
					@if ($comment->type=='note')
						<button type="button" class="btn btn-default btn-sm" data-toggle="modal" data-target="#comment-modal-edit-{{$comment->id}}">{{ trans('panichd::lang.btn-edit') }}</button>
					@elseif($comment->type=='reply')
						<button type="button" class="btn btn-default btn-sm" data-toggle="modal" data-target="#email-resend-modal" data-id="{{$comment->id}}" data-owner="{{$ticket->user->name}}">{{ trans('panichd::lang.show-ticket-email-resend') }}</button>
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
	@include('panichd::tickets.partials.email_resend')
	@include('panichd::tickets.partials.modal_comment_delete')
@endif