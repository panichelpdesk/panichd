@extends($master)

@section('page')
{{ trans('ticketit::admin.config-index-title') }}
@stop

@section('content')
@include('ticketit::shared.header')

<div class="panel panel-default">
    <div class="panel-heading">
        <h3>{{ trans('ticketit::admin.deptsuser-index-title') }}
            <div class="panel-nav pull-right" style="margin-top: -7px;">
                <button type="button" class="btn btn-default">{{ trans('ticketit::admin.btn-create-new-deptsuser') }}</button>
            </div>
        </h3>
    </div>
    @if(!$a_users)
        <div class="well text-center">{{ trans('ticketit::admin.deptsuser-index-empty') }}</div>
    @else
        <div id="message"></div>
            <table class="table table-hover">
                <thead>
                    <tr>                        
                        <td>{{ trans('ticketit::admin.deptsuser-index-user') }}</td>
                        <td>{{ trans('ticketit::admin.deptsuser-index-email') }}</td>
						<td>{{ trans('ticketit::admin.deptsuser-index-department') }}</td>
						<td>{{ trans('ticketit::admin.table-action') }}</td>
                    </tr>
                </thead>
                <tbody>
                @foreach($a_users as $d_user)
                    <tr>
                        <td style="vertical-align: middle">
                            {{ $d_user->name }}
                        </td>
                        <td style="vertical-align: middle">
                            {{ $d_user->email }}
                        </td>
						<td style="vertical-align: middle">
                            {{-- $d_user->userDepartment()->first()->resume(true) --}}
							<span title="{{ $d_user->userDepartment->title() }}">{{ $d_user->userDepartment->resume(true) }}</span>
                        </td>
						<td>
                            <button type="button" class="btn btn-info">{{ trans('ticketit::admin.btn-edit') }}</button>
							{!! link_to_route(
							$setting->grab('admin_route').'.category.destroy', trans('ticketit::admin.btn-delete'), $d_user->id,
							[
							'class' => 'btn btn-danger deleteit',
							'form' => "delete-$d_user->id",
							"node" => $d_user->name
							])
                                !!}
                            {!! CollectiveForm::open([
                                            'method' => 'DELETE',
                                            'route' => [
                                                        $setting->grab('admin_route').'.category.destroy',
                                                        $d_user->id
                                                        ],
                                            'id' => "delete-$d_user->id"
                                            ])
                            !!}
                            {!! CollectiveForm::close() !!}
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif
    </div>
@stop
@section('footer')
    <script>
        $( ".deleteit" ).click(function( event ) {
            event.preventDefault();
            if (confirm("{!! trans('ticketit::admin.category-index-js-delete') !!}" + $(this).attr("node") + " ?"))
            {
                var form = $(this).attr("form");
                $("#" + form).submit();
            }

        });
    </script>
@append