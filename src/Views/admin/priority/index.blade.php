@extends($master)

@section('page')
    {{ trans('panichd::admin.priority-index-title') }}
@stop

@include('panichd::shared.common')

@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">
            <h2>{{ trans('panichd::admin.priority-index-title') }}
                {!! link_to_route(
                                    $setting->grab('admin_route').'.priority.create',
                                    trans('panichd::admin.btn-create-new-priority'), null,
                                    ['class' => 'btn btn-primary pull-right'])
                !!}
            </h2>
        </div>

        @if ($priorities->isEmpty())
            <h3 class="text-center">{{ trans('panichd::admin.priority-index-no-priorities') }}
                {!! link_to_route($setting->grab('admin_route').'.priority.create', trans('panichd::admin.priority-index-create-new')) !!}
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
                @foreach($priorities as $priority)
                    <tr>
                        <td style="vertical-align: middle">
                            {{ $priority->id }}
                        </td>
                        <td style="color: {{ $priority->color }}; vertical-align: middle">
                            {{ $priority->name }}
                        </td>
                        <td>{{ $priority->tickets_count }}</td>
						<td>
                            {!! link_to_route(
								$setting->grab('admin_route').'.priority.edit', trans('panichd::admin.btn-edit'), $priority->id,
								['class' => 'btn btn-default'] )
							!!}

							{!! link_to_route(
								$setting->grab('admin_route').'.priority.destroy', trans('panichd::admin.btn-delete'), $priority->id,
								[
								'class' => 'btn btn-default deleteit',
								'form' => "delete-$priority->id",
								"node" => $priority->name,
								'data-id' => "$priority->id",
								"data-name" => $priority->name,
								"data-tickets-count" => $priority->tickets_count,
								"data-modal-title" => trans('panichd::admin.priority-delete-title', ['name' => $priority->name])
								])
							!!}
                            {!! CollectiveForm::open([
								'method' => 'DELETE',
								'route' => [
											$setting->grab('admin_route').'.priority.destroy',
											$priority->id
											],
								'id' => "delete-$priority->id"
								])
                            !!}
                            @if ($priority->tickets_count > 0)
								{!! CollectiveForm::hidden('tickets_new_priority_id', null) !!}
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

@include('panichd::admin.priority.partials.modal_delete')

@section('footer')
    <script>
		$( ".deleteit" ).click(function( event ) {
            event.preventDefault();
			
			var form = $.find('#delete-'+$(this).data("id"));
			
			if ($(form).find("input[name='tickets_new_priority_id']").length >0){
				
				$('#modal-priority-delete').find('.modal-title').text($(this).data('modal-title'));
				$('#modal-priority-delete').find('.modal-tickets-count').text($(this).data('tickets-count'));
				$('#modal-priority-delete').find('.modal-priority-select').hide();
				$('#modal-priority-delete').find('#select_priority_without_'+$(this).data("id")).show();
				$('#modal-priority-delete').find("input[name='modal-priority-id']").val($(this).data("id"));
				$('#modal-priority-delete').modal('show');
			}else{
				if (confirm("{!! trans('panichd::admin.priority-index-js-delete') !!}" + $(this).data("name") + " ?"))
				{
					$("#delete-" + $(this).data("id")).submit();
				}
			}
        });
		
		$('#submit_priority_delete_modal').click(function(e){
			e.preventDefault();
			var modal = $(this).closest('#modal-priority-delete');
			var priority_id = modal.find("input[name='modal-priority-id']").val();
			
			$('#delete-'+priority_id).find("input[name='tickets_new_priority_id']").val(modal.find('#select_priority_without_'+priority_id).val());
			$('#delete-'+priority_id).submit();
		});
    </script>
@append
