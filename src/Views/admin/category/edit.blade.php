@extends($master)
@section('page', trans('ticketit::admin.category-edit-title', ['name' => ucwords($category->name)]))

@include('ticketit::shared.common')

@section('content')	
	@include('ticketit::shared.colorpicker')
    <style type="text/css">
	#tag-panel .glyphicon {
		color: #777;
	}
	
	#jquery_popup_tag_input {
		border: transparent;
		box-shadow: none;
	}
	</style>
	<div class="well bs-component">
        {!! CollectiveForm::model($category, [
			'route' => [$setting->grab('admin_route').'.category.update', $category->id],
			'method' => 'PATCH',
			'class' => 'form-horizontal'
			]) !!}
        <legend>{{ trans('ticketit::admin.category-edit-title', ['name' => ucwords($category->name)]) }}</legend>
        @include('ticketit::admin.category.form', ['update', true])
		@include('ticketit::admin.category.modal-email')
        {!! CollectiveForm::close() !!}
    </div>
	@include('ticketit::admin.category.modal-reason')
	@include('ticketit::admin.category.modal-tag')
@stop

@section('footer')
	@include('ticketit::admin.category.scripts-create-edit')
	@include('ticketit::admin.category.scripts-edit')
@append
