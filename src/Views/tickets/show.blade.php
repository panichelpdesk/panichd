@extends($master)

@section('page', trans('panichd::lang.show-ticket-title') . trans('panichd::lang.colon') . $ticket->subject)

@include('panichd::shared.common')

@section('content')
        @include('panichd::tickets.show.body')
		@if($u->isAdmin())
			@include('panichd::tickets.show.modal_delete')
		@endif
		@include('panichd::tickets.show.modal_complete')
		
		@if($u->canCommentTicket($ticket->id) || ( !$comments->isEmpty() && $ticket->comments->forLevel(1)->count() ) )
			<div style="margin-top: 2em;">        	
				<h3 style="margin-top: 0em;">{{ trans('panichd::lang.comments') }}
					@if ($u->canCommentTicket($ticket->id))
						<button type="button" class="btn btn-light btn-default" data-toggle="modal" data-target="#modal-comment-new" data-add-comment="{{ $ticket->hidden ? 'no' : 'yes' }}">{{ $ticket->hidden ? trans('panichd::lang.show-ticket-add-note') : trans('panichd::lang.show-ticket-add-comment') }}</button>
					@endif
				</h3>
			</div>
		@endif
        @if(!$comments->isEmpty())
			@include('panichd::tickets.show.comments')
        @endif
		{!! $comments->render() !!}
        @include('panichd::tickets.show.modal_comment_new')
		@if ($setting->grab('ticket_attachments_feature'))
			@include('panichd::shared.attach_files_script')
		@endif
@endsection

@include('panichd::shared.photoswipe_files')
@include('panichd::shared.jcrop_files')
@include('panichd::tickets.partials.summernote')

@section('footer')
    @include('panichd::tickets.show.script')

	@include('panichd::tickets.partials.tags_footer_script')
@append