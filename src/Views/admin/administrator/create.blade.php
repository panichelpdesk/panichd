@extends($master)
@section('page', trans('panichd::admin.administrator-create-title'))

@include('panichd::shared.common')

@section('content')    
    <div class="panel panel-default">
        <div class="panel-heading">
            <h2>{{ trans('panichd::admin.administrator-create-title') }}</h2>
        </div>
        @if ($users->isEmpty())
            <h3 class="text-center">{{ trans('panichd::admin.administrator-create-no-users') }}</h3>
        @else
            {!! CollectiveForm::open(['route'=> $setting->grab('admin_route').'.administrator.store', 'method' => 'POST', 'class' => 'form-horizontal']) !!}
            <div class="panel-body">
                {{ trans('panichd::admin.administrator-create-select-user') }}
            </div>
            <table class="table table-hover">
                <tfoot>
                    <tr>
                        <td class="text-center">
                            {!! link_to_route($setting->grab('admin_route').'.administrator.index', trans('panichd::admin.btn-back'), null, ['class' => 'btn btn-default']) !!}
                            {!! CollectiveForm::submit(trans('panichd::admin.btn-submit'), ['class' => 'btn btn-primary']) !!}
                        </td>
                    </tr>
                <tbody>
                @foreach($users as $user)
                    <tr>
                        <td>
                            <div class="checkbox">
                                <label>
                                    <input name="administrators[]" type="checkbox" value="{{ $user->id }}" {!! $user->panichd_admin ? "checked" : "" !!}> {{ $user->name }}
                                </label>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            {!! CollectiveForm::close() !!}
        @endif
    </div>
    {!! $users->render() !!}
@stop
