@extends($master)
@section('page', trans('panichd::admin.priority-edit-title', ['name' => ucwords($priority->name)]))

@include('panichd::shared.common')
@include('panichd::shared.colorpicker', [
	'include_colorpickerplus_script' => true,
	'input_color' => $priority->color
])

@section('content')
    <div class="card bg-light">
        <div class="card-body">
            {!! CollectiveForm::model($priority, [
                'route' => [$setting->grab('admin_route').'.priority.update', $priority->id],
                'method' => 'PATCH'
            ]) !!}
            <legend>{{ trans('panichd::admin.priority-edit-title', ['name' => ucwords($priority->name)]) }}</legend>
            @include('panichd::admin.priority.form', ['update', true])
            {!! CollectiveForm::close() !!}
        </div>
    </div>
@stop