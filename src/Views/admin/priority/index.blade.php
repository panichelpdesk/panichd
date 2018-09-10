@extends($master)

@section('page')
    {{ trans('panichd::admin.priority-index-title') }}
@stop

@include('panichd::shared.common')

@section('content')
    <div class="card bg-light">
        <div class="card-header">
          {!! link_to_route(
            $setting->grab('admin_route').'.priority.create',
            trans('panichd::admin.btn-create-new-priority'), null,
            ['class' => 'btn btn-primary float-right']
          ) !!}
          <h2>{{ trans('panichd::admin.priority-index-title') }}</h2>
        </div>

        @if ($priorities->isEmpty())
            <h3 class="text-center">{{ trans('panichd::admin.priority-index-no-priorities') }}
                {!! link_to_route($setting->grab('admin_route').'.priority.create', trans('panichd::admin.priority-index-create-new')) !!}
            </h3>
        @else
        <div class="card-body">
			<div class="alert alert-info alert-dismissable fade show">
				<button type="button" class="close" data-dismiss="alert">×</button>
				<span class="fa fa-info-circle" style="color: #7ED5EC;"></span> {!! trans('panichd::admin.priority-index-help') !!}
			</div>

            <table id="priority_table" class="table table-hover">
                <thead>
                    <tr>
                        <td style="width: 1em; vertical-align: bottom;"></td>
						<td>{{ trans('panichd::admin.table-magnitude') }}</td>
                        <td>{{ trans('panichd::admin.table-name') }}</td>
						<td>{{ trans('panichd::admin.table-num-tickets') }}</td>
                        <td>{{ trans('panichd::admin.table-action') }}</td>
                    </tr>
                </thead>
                <tbody>
                @foreach($priorities as $priority)
                    <tr data-id="{{ $priority->id }}">
                        <td style="vertical-align: center"><span class="fa fa-ellipsis-v" style="color: #aaa"></span></td>
						<td class="magnitude">{{ $priority->magnitude }}</td>
                        <td class="name" data-color="{{ $priority->color }}" style="color: {{ $priority->color }}; vertical-align: middle">
                            {{ $priority->name }}
                        </td>
                        <td>{{ $priority->tickets_count }}</td>
						<td>
                            {!! link_to_route(
								$setting->grab('admin_route').'.priority.edit', trans('panichd::admin.btn-edit'), $priority->id,
								['class' => 'btn btn-light btn-default'] )
							!!}

							{!! link_to_route(
								$setting->grab('admin_route').'.priority.destroy', trans('panichd::admin.btn-delete'), $priority->id,
								[
								'class' => 'btn btn-light btn-default deleteit',
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
			</div>
        @endif
    </div>
@stop

@include('panichd::admin.priority.partials.modal_delete')

@section('footer')
    <script>
		$('#priority_table tbody').sortable({
			cursor: 'n-resize',
			helper: function(index, elem){
				return '<div style="height: 3em; width: 30em; border: 1px solid #bbb; background: #fff; padding: 0.7em;">Priority: <span style="color: '+elem.find('.name').data('color')+'">'+elem.find('.name').text()+'</span></span>';
			},
			beforeStop: function( event, ui ) {
				var a_order = [];
				$.each($('#priority_table tbody tr'), function (index, value){
					if ($(this).attr('data-id')) a_order.push($(this).data('id'));
				});

				var max_magnitude = a_order.length;

				$('#priority_table .magnitude').text('...');

				// Do AJAX POST when sorting priorities
				$.ajax({
					type: "POST",
					url: '{{ route($setting->grab('admin_route').'.priority.reorder') }}',
					data: {
						_token: "{{ csrf_token() }}", // Si està dins un fitxer BLADE
						priorities: a_order.join(', ')
					},

					success: function( response ) {
						if (response.result == 'ok'){
							$('#priority_table .magnitude').each(function(index, row){
								$(this).text(max_magnitude-index);
							});
						}else{

						}
					}
				});
			}
		});

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
