@extends($master)

@section('page')
{{ trans('panichd::admin.config-index-title') }}
@stop

@include('panichd::shared.common')

@section('content')
        <!-- configuration -->
<div class="card bg-light">
    <div class="card-header">
        <div class="panel-nav float-right">
            {!! link_to_route(
                $setting->grab('admin_route').'.configuration.index',
                trans('panichd::admin.btn-back'), null,
                ['class' => 'btn btn-light'])
            !!}
            {!! link_to_route(
                $setting->grab('admin_route').'.configuration.create',
                trans('panichd::admin.btn-create-new-config'), null,
                ['class' => 'btn btn-primary'])
            !!}
        </div>
        <h3>{{ trans('panichd::admin.config-index-title') }}

        </h3>
    </div>
    @if($configurations->isEmpty())
        <div class="card bg-light">
            <div class="card-body text-center">{{ trans('panichd::admin.config-index-no-settings') }}</div>
        </div>
    @else
        <ul class="nav nav-tabs nav-justified" role="tablist">
            <li class="nav-item"><a class="nav-link active" data-toggle="tab" role="tab" href="#init-configs">{{ trans('panichd::admin.config-index-initial') }}</a></li>
            <li class="nav-item"><a class="nav-link" data-toggle="tab" role="tab" href="#features-configs">{{ trans('panichd::admin.config-index-features') }}</a></li>
            <li class="nav-item"><a class="nav-link" data-toggle="tab" role="tab" href="#table-configs">{{ trans('panichd::admin.config-index-table') }}</a></li>
            <li class="nav-item"><a class="nav-link" data-toggle="tab" role="tab" href="#tickets-configs">{{ trans('panichd::admin.config-index-tickets') }}</a></li>
            <li class="nav-item"><a class="nav-link" data-toggle="tab" role="tab" href="#email-configs">{{ trans('panichd::admin.config-index-notifications') }}</a></li>
            <li class="nav-item"><a class="nav-link" data-toggle="tab" role="tab" href="#perms-configs">{{ trans('panichd::admin.config-index-permissions') }}</a></li>
            <li class="nav-item"><a class="nav-link" data-toggle="tab" role="tab" href="#editor-configs">{{ trans('panichd::admin.config-index-editor') }}</a></li>
            <li class="nav-item"><a class="nav-link" data-toggle="tab" role="tab" href="#other-configs">{{ trans('panichd::admin.config-index-other') }}</a></li>
        </ul>
    <br />
        <div class="tab-content">
            @foreach($configurations_by_sections as $section => $a_configurations)
                <div id="{{ $section }}-configs" class="tab-pane fade @if($section == "init") show active @endif">
                    @include('panichd::admin.configuration.partials.tab_table', ['section_configurations' => $a_configurations])
                </div>
            @endforeach
        </div>
    @endif
    {{--@include('panichd::admin.configuration.common.paginate', ['records' => $configurations])--}}
</div>
<!-- // Configuration -->

@endsection
