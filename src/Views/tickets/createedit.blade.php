@extends($master)
@section('page', trans('panichd::lang.create-ticket-title'))

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
	@include('panichd::tickets.partials.comments.new_in_createedit_ticket_scripts')
	@include('panichd::tickets.partials.tags_footer_script')
@append
