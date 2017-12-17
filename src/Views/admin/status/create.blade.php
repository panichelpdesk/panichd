@extends($master)
@section('page', trans('ticketit::admin.status-create-title'))

@include('panichd::shared.common')

@section('content')
    <div class="well bs-component">
        {!! CollectiveForm::open(['route'=> $setting->grab('admin_route').'.status.store', 'method' => 'POST', 'class' => 'form-horizontal']) !!}
            <legend>{{ trans('ticketit::admin.status-create-title') }}</legend>
            @include('panichd::admin.status.form')
        {!! CollectiveForm::close() !!}
    </div>
@stop
