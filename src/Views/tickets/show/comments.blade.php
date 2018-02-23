@if(!$comments->isEmpty())
    @foreach($comments as $comment)
        <?php
			$comment_title = trans('panichd::lang.comment-'.$comment->type.'-title');
			
			switch ($comment->type){
				case 'note':
					$glyphicon = "glyphicon-pencil text-info";
					$comment_header = trans('panichd::lang.comment-note-from-agent', ['agent' => $comment->owner->name]);
					break;
				case 'complete': // Simple complete box
				case 'completetx': // Complete with comment text
					$glyphicon = "glyphicon-ok-circle text-success";
					$comment_header = trans('panichd::lang.comment-complete-by', ['owner' => $comment->owner->name]);
					break;
				case 'reopen':
					$glyphicon = "glyphicon-backward text-warning";
					$comment_header = trans('panichd::lang.comment-reopen-by', ['owner' => $comment->owner->name]);
					break;
				case 'hide_0':
					$glyphicon = "glyphicon-eye-open text-success";
					$comment_header = trans('panichd::lang.ticket-hidden-0-comment-title', ['agent' => $comment->owner->name]);
					break;
				case 'hide_1':
					$glyphicon = "glyphicon-eye-close text-warning";
					$comment_header = trans('panichd::lang.ticket-hidden-1-comment-title', ['agent' => $comment->owner->name]);
					break;
				default:
					// Reply
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
		@if (in_array($comment->type, ['complete', 'reopen', 'hide_0', 'hide_1']))
			<div class="row"><div class="col-xs-12"><div style="margin: 0em 0em 1em 0em; padding: 0px 15px;">
				<span class="glyphicons glyphicon {{ $glyphicon }}" aria-hidden="true" style="margin: 0em 0.5em 0em 0em;"></span>{!! $comment_header !!}
				@if ($comment->created_at!=$comment->updated_at)
					<span class="glyphicon glyphicon-pencil" aria-hidden="true" style="color: gray"></span>
				@endif
				{!! $comment->updated_at->diffForHumans() !!}
			</div></div></div>
		@else
		<div class="panel panel-default">
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
						@include('panichd::tickets.show.modal_comment_edit')
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
		@endif
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
	@include('panichd::tickets.show.modal_resend_emails')
	@include('panichd::tickets.show.modal_comment_delete')
@endif