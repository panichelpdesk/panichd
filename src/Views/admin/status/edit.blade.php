@extends($master)
@section('page', trans('ticketit::admin.status-edit-title', ['name' => ucwords($status->name)]))

@include('ticketit::shared.common')

@section('content')
    <div class="well bs-component">
        {!! CollectiveForm::model($status, [
                                    'route' => [$setting->grab('admin_route').'.status.update', $status->id],
                                    'method' => 'PATCH',
                                    'class' => 'form-horizontal'
                                    ]) !!}
        <legend>{{ trans('ticketit::admin.status-edit-title', ['name' => ucwords($status->name)]) }}</legend>
        @include('ticketit::admin.status.form', ['update', true])
        {!! CollectiveForm::close() !!}
    </div>
@stop
