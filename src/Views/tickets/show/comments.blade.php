@if(!$comments->isEmpty())
    @foreach($comments as $comment)
        <?php
			$comment_title = trans('panichd::lang.comment-'.$comment->type.'-title');

			switch ($comment->type){
				case 'note':
					$icon_class = "fa fa-pencil-alt text-info";
					$comment_header = trans('panichd::lang.comment-note-from-agent', ['agent' => $comment->owner->name]);
					break;
				case 'complete': // Simple complete box
				case 'completetx': // Complete with comment text
					$icon_class = "fa fa-check-circle text-success";
					$comment_header = trans('panichd::lang.comment-complete-by', ['owner' => $comment->owner->name]);
					break;
				case 'reopen':
					$icon_class = "fa fa-backward text-warning";
					$comment_header = trans('panichd::lang.comment-reopen-by', ['owner' => $comment->owner->name]);
					break;
				case 'hide_0':
					$icon_class = "fa fa-eye text-success";
					$comment_header = trans('panichd::lang.ticket-hidden-0-comment-title', ['agent' => $comment->owner->name]);
					break;
				case 'hide_1':
					$icon_class = "fa fa-eye-slash text-warning";
					$comment_header = trans('panichd::lang.ticket-hidden-1-comment-title', ['agent' => $comment->owner->name]);
					break;
				default:
					// Reply
					$icon_class = "fa fa-comment ";
					if ($comment->owner->levelInCategory($comment->ticket->category->id) >= 2){
						$icon_class .= "text-info";
					}else{
						$icon_class .= "text-warning";
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
				<span class="{{ $icon_class }}" aria-hidden="true" style="margin: 0em 0.5em 0em 0em;"></span>{!! $comment_header !!}
				@if ($comment->created_at!=$comment->updated_at)
					<span class="fa fa-pencil-alt" aria-hidden="true" style="color: gray"></span>
				@endif
				{!! $comment->updated_at->diffForHumans() !!}
			</div></div></div>
		@else
		<div class="card bg-light mb-3">
      <div class="card-header pt-2 pr-3 pb-1 pl-2">
        <h6 class="card-title mb-0">
          <span class="float-right tooltip-info" data-toggle="tooltip" data-placement="top" title="{{ trans('panichd::lang.creation-date', [
          'date' => \Carbon\Carbon::parse($comment->created_at)->format(trans('panichd::lang.datetime-format'))
          ]) }}">
            @if ($comment->created_at!=$comment->updated_at)
            <span class="fa fa-pencil-alt" aria-hidden="true" style="color: gray"></span>
            @endif
            {!! $comment->updated_at->diffForHumans() !!}
            @if ($u->currentLevel() > 1 && $u->canManageTicket($ticket->id) and $comment->type=='note')
            <button type="button" class="btn btn-light btn-sm comment_deleteit"  data-toggle="modal" data-target="#modal-comment-delete" data-id="{{$comment->id}}" data-text="{{$comment->user->name}}" title="{{ trans('panichd::lang.show-ticket-delete-comment') }}">
            <span class="fa fa-times" aria-label="{{ trans('panichd::lang.btn-delete') }}" style="color: gray"></span></button>
            @endif
          </span>
          <span class="tooltip-info" data-toggle="tooltip" data-placement="bottom" title="{{ $comment_title }}"><span class="{{ $icon_class }}" aria-hidden="true"></span> {!! $comment_header !!}</span>

        </h6>
      </div>
            <div class="card-body">
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
