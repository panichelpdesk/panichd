@extends($master)

@section('page')
    {{ trans('panichd::admin.status-index-title') }}
@stop

@include('panichd::shared.common')

@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">
            <h2>{{ trans('panichd::admin.status-index-title') }}
                {!! link_to_route(
                                    $setting->grab('admin_route').'.status.create',
                                    trans('panichd::admin.btn-create-new-status'), null,
                                    ['class' => 'btn btn-primary pull-right'])
                !!}
            </h2>
        </div>

        @if ($statuses->isEmpty())
            <h3 class="text-center">{{ trans('panichd::admin.status-index-no-statuses') }}
                {!! link_to_route($setting->grab('admin_route').'.status.create', trans('panichd::admin.status-index-create-new')) !!}
            </h3>
        @else
            <div id="message"></div>
            <table class="table table-hover">
                <thead>
                    <tr>
                        <td>{{ trans('panichd::admin.table-id') }}</td>
                        <td>{{ trans('panichd::admin.table-name') }}</td>
                        <td>{{ trans('panichd::admin.table-num-tickets') }}</td>
                        <td>{{ trans('panichd::admin.table-action') }}</td>
                    </tr>
                </thead>
                <tbody>
                @foreach($statuses as $status)
                    <tr>
                        <td style="vertical-align: middle">
                            {{ $status->id }}
                        </td>
                        <td style="color: {{ $status->color }}; vertical-align: middle">
                            {{ $status->name }}
                        </td>
                        <td>{{ $status->tickets_count }}</td>
						<td>
                            {!! link_to_route(
								$setting->grab('admin_route').'.status.edit', trans('panichd::admin.btn-edit'), $status->id,
								['class' => 'btn btn-default'] )
							!!}

							{!! link_to_route(
								$setting->grab('admin_route').'.status.destroy', trans('panichd::admin.btn-delete'), $status->id,
								[
								'class' => 'btn btn-default deleteit',
								'data-id' => "$status->id",
								"data-node" => $status->name,
								"data-modal-title" => trans('panichd::admin.status-delete-title', ['name' => $status->name])
								])
							!!}
                            {!! CollectiveForm::open([
								'method' => 'DELETE',
								'route' => [
											$setting->grab('admin_route').'.status.destroy',
											$status->id
											],
								'id' => "delete-$status->id"
								]) !!}
							@if ($status->tickets_count > 0)
								{!! CollectiveForm::hidden('tickets_new_status_id', null) !!}
							@endif
							
                            {!! CollectiveForm::close() !!}
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif
    </div>
@stop

@include('panichd::admin.status.partials.modal_delete')

@section('footer')
    <script>
        $( ".deleteit" ).click(function( event ) {
            event.preventDefault();
			
			var form = $.find('#delete-'+$(this).data("id"));
			
			if ($(form).find("input[name='tickets_new_status_id']").length >0){
				
				$('#modal-status-delete').find('.modal-title').text($(this).data('modal-title'));
				$('#modal-status-delete').modal('show');

				
			}else{
				alert('no');
			}
			
            /*if (confirm("{!! trans('panichd::admin.status-index-js-delete') !!}" + $(this).data("node") + " ?"))
            {
                $("#delete-" + $(this).data("form")).submit();
            }*/

        });
    </script>
@append
