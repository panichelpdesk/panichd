@extends($master)

@section('page', trans('panichd::lang.show-ticket-title') . trans('panichd::lang.colon') . $ticket->subject)

@include('panichd::shared.common')

@section('content')
        @include('panichd::tickets.show.body')
		@if($u->isAdmin())
			@include('panichd::tickets.show.modal_delete')
		@endif
		@include('panichd::tickets.show.modal_complete')

		@if ($setting->grab('ticket_attachments_feature'))
			@include('panichd::shared.attach_files_script')
		@endif
@endsection

@include('panichd::tickets.partials.comments.index', ['new_comment_modal' => true])

@include('panichd::shared.photoswipe_files')
@include('panichd::shared.jcrop_files')
@include('panichd::tickets.partials.summernote')

@section('footer')
    @include('panichd::tickets.show.scripts')
@append
