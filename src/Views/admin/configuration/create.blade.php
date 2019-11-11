@extends($master)

@section('page')
    {{ trans('panichd::admin.config-create-subtitle') }}
@stop

@include('panichd::shared.common')

@section('content')
     <div class="card bg-light">
      <div class="card-header">
        <h3>{{ trans('panichd::admin.config-create-title') }}
          <div class="panel-nav float-right" style="margin-top: -7px;">
              {!! link_to_route(
                  $setting->grab('admin_route').'.configuration.index',
                  trans('panichd::admin.btn-back'), null,
                  ['class' => 'btn btn-light'])
              !!}
          </div>
        </h3>
      </div>
      <div class="card-body">
            {!! CollectiveForm::open(['route' => $setting->grab('admin_route').'.configuration.store']) !!}

            <!-- Slug Field -->
            <div class="form-group row">
                {!! CollectiveForm::label('slug', trans('panichd::admin.config-edit-slug') . trans('panichd::admin.colon'), ['class' => 'col-sm-2 col-form-label']) !!}
                <div class="col-sm-9">
                    {!! CollectiveForm::text('slug', null, ['class' => 'form-control']) !!}
                </div>
            </div>

            <!-- Default Field -->
            <div class="form-group row">
                {!! CollectiveForm::label('default', trans('panichd::admin.config-edit-default') . trans('panichd::admin.colon'), ['class' => 'col-sm-2 col-form-label']) !!}
                <div class="col-sm-9">
                    {!! CollectiveForm::text('default', null, ['class' => 'form-control']) !!}
                </div>
            </div>

            <!-- Value Field -->
            <div class="form-group row">
                {!! CollectiveForm::label('value', trans('panichd::admin.config-edit-value') . trans('panichd::admin.colon'), ['class' => 'col-sm-2 col-form-label']) !!}
                <div class="col-sm-9">
                    {!! CollectiveForm::text('value', null, ['class' => 'form-control']) !!}
                </div>
            </div>

            <!-- Lang Field -->
            <div class="form-group row">
                {!! CollectiveForm::label('lang', trans('panichd::admin.config-edit-language') . trans('panichd::admin.colon'), ['class' => 'col-sm-2 col-form-label']) !!}
                <div class="col-sm-9">
                    {!! CollectiveForm::text('lang', null, ['class' => 'form-control']) !!}

                </div>
            </div>

            <!-- Submit Field -->
            <div class="form-group row">
                <div class="col-sm-10 offset-sm-2">
                  {!! CollectiveForm::submit(trans('panichd::lang.btn-add'), ['class' => 'btn btn-primary']) !!}
                </div>
            </div>

          {!! CollectiveForm::close() !!}
      </div>
      <div class="card-footer">
      </div>
    </div>

<script>
  $(document).ready(function() {
    $("#slug").bind('change', function() {
      var slugger = $('#slug').val();
          slugger = slugger
          .replace(/\W/g, '.')
          .toLowerCase();
      $("#slug").val(slugger);
    });

    $("#default").bind('keyup blur keypress change', function() {
      var duplicate = $('#default').val();
      $("#value").val(duplicate);
    });
  });
</script>

@stop
