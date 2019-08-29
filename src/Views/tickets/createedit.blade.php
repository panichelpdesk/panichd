@extends($master)
@section('page')
	@if(isset($ticket))
		{{ trans('panichd::lang.edit-ticket') . ' #' . $ticket->id }}
	@else
		{{ trans('panichd::lang.create-new-ticket') }}
	@endif
@endsection

@include('panichd::shared.common')

@section('content')
	@include('panichd::tickets.createedit.form')
@endsection

@include('panichd::tickets.partials.comments.index')

@include('panichd::tickets.partials.modal_attachment_edit')
@include('panichd::shared.photoswipe_files')
@include('panichd::shared.datetimepicker')
@include('panichd::shared.jcrop_files')
@include('panichd::tickets.partials.summernote')

@section('footer')
    @include('panichd::tickets.createedit.scripts')
	@if ($u->currentLevel() > 1)
		@include('panichd::tickets.partials.comments.embedded_scripts')
	@endif
	@include('panichd::tickets.partials.tags_footer_script')
@append
