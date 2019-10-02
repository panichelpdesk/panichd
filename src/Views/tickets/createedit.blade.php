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
@include('panichd::shared.colorpicker', ['include_colorpickerplus_script' => true])

@section('footer')
	@include('panichd::tickets.createedit.scripts')
	@include('panichd::tickets.partials.form_scripts')
	@if ($u->currentLevel() > 1)
		@include('panichd::tickets.partials.comments.embedded_scripts')
	@endif

	@include('panichd::tickets.partials.tags_footer_script', ['new_tags_allowed' => true, 'category_id' => $a_current['cat_id']])
@append

@if($u->isAdmin())
	@include('panichd::shared.tag_create_edit')
@endif