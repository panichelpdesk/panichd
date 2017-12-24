<div class="form-group">
    {!! CollectiveForm::label('name', trans('panichd::admin.status-create-name') . trans('panichd::admin.colon'), ['class' => 'col-lg-2 control-label']) !!}
    <div class="col-lg-10">
        {!! CollectiveForm::text('name', isset($status->name) ? $status->name : null, ['class' => 'form-control']) !!}
    </div>
</div>
<div class="form-group">
    {!! CollectiveForm::label('color', trans('panichd::admin.status-create-color') . trans('panichd::admin.colon'), ['class' => 'col-lg-2 control-label']) !!}
    <div class="col-lg-10">
        @yield('colorpicker_snippet')
    </div>
</div>
<div class="form-group">
    <div class="col-lg-10 col-lg-offset-2">
        {!! link_to_route($setting->grab('admin_route').'.status.index', trans('panichd::admin.btn-back'), null, ['class' => 'btn btn-default']) !!}
        @if(isset($status))
            {!! CollectiveForm::submit(trans('panichd::admin.btn-update'), ['class' => 'btn btn-primary']) !!}
        @else
            {!! CollectiveForm::submit(trans('panichd::admin.btn-submit'), ['class' => 'btn btn-primary']) !!}
        @endif
    </div>
</div>
