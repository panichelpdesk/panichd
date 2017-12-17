@extends($master)
@section('page', trans('ticketit::admin.priority-create-title'))

@include('panichd::shared.common')

@section('content')
    <div class="well bs-component">
        {!! CollectiveForm::open(['route'=> $setting->grab('admin_route').'.priority.store', 'method' => 'POST', 'class' => 'form-horizontal']) !!}
            <legend>{{ trans('ticketit::admin.priority-create-title') }}</legend>
            @include('panichd::admin.priority.form')
        {!! CollectiveForm::close() !!}
    </div>
@stop
