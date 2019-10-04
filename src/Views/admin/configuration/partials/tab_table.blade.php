<div class="table-responsive">
    <table class="table table-striped table-bordered table-hover">
        <thead>
        <th class="text-center">{{ trans('panichd::admin.table-hash') }}</th>
        <th>{{ trans('panichd::admin.table-slug') }}</th>
        <th>{{ trans('panichd::admin.table-default') }}</th>
        <th>{{ trans('panichd::admin.table-value') }}</th>
        <th class="text-center">{{ trans('panichd::admin.table-lang') }}</th>
        <th class="text-center">{{ trans('panichd::admin.table-description') }}</th>
        <th class="text-center">{{ trans('panichd::admin.table-action') }}</th>
        </thead>
        <tbody>
        @foreach($section_configurations as $configuration)
            <tr>
                <td class="text-center">{!! $configuration->id !!}</td>
                <td>{!! $configuration->slug !!}</td>
                <td>{!! $configuration->default !!}</td>
                <td><a href="{!! route($setting->grab('admin_route').'.configuration.edit', [$configuration->id]) !!}" title="{{ trans('panichd::admin.table-edit').' '.$configuration->slug }}" data-toggle="tooltip">{!! $configuration->value !!}</a></td>
                <td class="text-center">{!! $configuration->lang !!}</td>
                <td>@if(array_key_exists($configuration->slug, trans('panichd::settings'))){!! trans('panichd::settings.' . $configuration->slug) !!}@endif</td>
                <td class="text-center">
                    {!! link_to_route(
                        $setting->grab('admin_route').'.configuration.edit', trans('panichd::admin.btn-edit'), [$configuration->id],
                        ['class' => 'btn btn-default', 'title' => trans('panichd::admin.table-edit').' '.$configuration->slug,  'data-toggle' => 'tooltip'] )
                    !!}
                    @if($section_name == 'other')
                        <button class="btn btn-default j_configuration_delete" data-form-action="{{ route($setting->grab('admin_route').'.configuration.destroy', $configuration->id) }}">
                            {{ trans('panichd::admin.btn-delete') }}
                        </button>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    @if($section_name == 'other')
        <form id="configuration_delete_form" action="" method="POST" style="display: none">
            <input type="hidden" name="_method" value="DELETE">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
        </form>
    @endif
</div>