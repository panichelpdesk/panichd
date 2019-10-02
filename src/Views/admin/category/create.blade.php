@extends($master)
@section('page', trans('panichd::admin.category-create-title'))

@include('panichd::shared.common')
@include('panichd::shared.colorpicker', ['include_colorpickerplus_script' => true])

@section('content')
    <div class="card bg-light">
        <div class="card-body">
            {!! CollectiveForm::open(['route'=> $setting->grab('admin_route').'.category.store', 'method' => 'POST']) !!}
                <legend>{{ trans('panichd::admin.category-create-title') }}</legend>
                @include('panichd::admin.category.form')
                @include('panichd::admin.category.modal-email')
            {!! CollectiveForm::close() !!}
        </div>
    </div>
	@include('panichd::admin.category.modal-reason')
@stop

@section('footer')
    @include('panichd::admin.category.scripts-create-edit')
    @include('panichd::shared.grouped_check_list')
@append

@include('panichd::shared.tag_create_edit')