@extends($master)

@section('page')
    {{ trans('panichd::admin.administrator-index-title') }}
@stop

@include('panichd::shared.common')

@section('content')
    <div class="card bg-light">
        <div class="card-header">
            <h2>{{ trans('panichd::admin.administrator-index-title') }}
                {!! link_to_route(
                                    $setting->grab('admin_route').'.administrator.create',
                                    trans('panichd::admin.btn-create-new-administrator'), null,
                                    ['class' => 'btn btn-primary float-right'])
                !!}
            </h2>
        </div>

        @if ($administrators->isEmpty())
            <h3 class="text-center">{{ trans('panichd::admin.administrator-index-no-administrators') }}
                {!! link_to_route($setting->grab('admin_route').'.administrator.create', trans('panichd::admin.administrator-index-create-new')) !!}
            </h3>
        @else
            <div id="message"></div>
            <table class="table table-hover">
                <thead>
                <tr>
                    <td>{{ trans('panichd::admin.table-id') }}</td>
                    <td>{{ trans('panichd::admin.table-name') }}</td>
                    <td>{{ trans('panichd::admin.table-remove-administrator') }}</td>
                </tr>
                </thead>
                <tbody>
                @foreach($administrators as $administrator)
                    <tr>
                        <td>
                            {{ $administrator->id }}
                        </td>
                        <td>
                            {{ $administrator->name }}
                        </td>
                        <td>
                            {!! CollectiveForm::open([
                            'method' => 'DELETE',
                            'route' => [
                                        $setting->grab('admin_route').'.administrator.destroy',
                                        $administrator->id
                                        ],
                            'id' => "delete-$administrator->id"
                            ]) !!}
                            {!! CollectiveForm::submit(trans('panichd::admin.btn-remove'), ['class' => 'btn btn-danger']) !!}
                            {!! CollectiveForm::close() !!}
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

        @endif
    </div>
@stop
