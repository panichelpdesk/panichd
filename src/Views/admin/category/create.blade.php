@extends($master)
@section('page', trans('ticketit::admin.category-create-title'))

@include('ticketit::shared.common')
@include('ticketit::shared.colorpicker')

@section('content')
	<div class="well bs-component">
        {!! CollectiveForm::open(['route'=> $setting->grab('admin_route').'.category.store', 'method' => 'POST', 'class' => 'form-horizontal']) !!}
            <legend>{{ trans('ticketit::admin.category-create-title') }}</legend>
            @include('ticketit::admin.category.form')
			@include('ticketit::admin.category.modal-email')
        {!! CollectiveForm::close() !!}
    </div>
	@include('ticketit::admin.category.modal-reason')
	@include('ticketit::admin.category.modal-tag')	
@stop

@section('footer')
	@include('ticketit::admin.category.scripts-create-edit')
@append
