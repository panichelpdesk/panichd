<div class="form-group row">
    {!! CollectiveForm::label('name', trans('panichd::admin.status-create-name') . trans('panichd::admin.colon'), ['class' => 'col-lg-2 col-form-label']) !!}
    <div class="col-lg-10">
        {!! CollectiveForm::text('name', isset($status->name) ? $status->name : null, ['class' => 'form-control']) !!}
    </div>
</div>
<div class="form-group row">
    {!! CollectiveForm::label('color', trans('panichd::admin.status-create-color') . trans('panichd::admin.colon'), ['class' => 'col-lg-2 col-form-label']) !!}
    <div class="col-lg-10">
        @yield('colorpicker_snippet')
    </div>
</div>
<div class="form-group row">
    <div class="col-lg-10 offset-lg-2">
        @if(isset($status))
            {!! CollectiveForm::submit(trans('panichd::admin.btn-update'), ['class' => 'btn btn-primary']) !!}
        @else
            {!! CollectiveForm::submit(trans('panichd::lang.btn-add'), ['class' => 'btn btn-primary']) !!}
        @endif
    </div>
</div>
