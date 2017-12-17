@extends($master)
@section('page', trans('panichd::admin.priority-edit-title', ['name' => ucwords($priority->name)]))

@include('panichd::shared.common')

@section('content')
    <div class="well bs-component">
        {!! CollectiveForm::model($priority, [
                                    'route' => [$setting->grab('admin_route').'.priority.update', $priority->id],
                                    'method' => 'PATCH',
                                    'class' => 'form-horizontal'
                                    ]) !!}
        <legend>{{ trans('panichd::admin.priority-edit-title', ['name' => ucwords($priority->name)]) }}</legend>
        @include('panichd::admin.priority.form', ['update', true])
        {!! CollectiveForm::close() !!}
    </div>
@stop
