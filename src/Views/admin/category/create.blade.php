@extends($master)
@section('page', trans('ticketit::admin.category-create-title'))

@section('content')
    @include('ticketit::shared.header')
	<div class="well bs-component">
        {!! CollectiveForm::open(['route'=> $setting->grab('admin_route').'.category.store', 'method' => 'POST', 'class' => 'form-horizontal']) !!}
            <legend>{{ trans('ticketit::admin.category-create-title') }}</legend>
            @include('ticketit::admin.category.form')
        {!! CollectiveForm::close() !!}
    </div>	
	@include('ticketit::admin.category.modal-reason')
	@include('ticketit::admin.category.modal-tag')	

	@include('ticketit::admin.category.scripts-create-edit')
@stop
