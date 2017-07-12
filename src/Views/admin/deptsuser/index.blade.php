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
                <button type="button" class="btn btn-default btn_modal_user" data-route="create">{{ trans('ticketit::admin.btn-create-new-deptsuser') }}</button>
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
                            <button type="button" class="btn btn-info btn_modal_user" data-user_id="{{ $d_user->id }}" data-department_id="{{ $d_user->userDepartment->id }}" data-route="update">{{ trans('ticketit::admin.btn-edit') }}</button>
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
	@include('ticketit::admin.deptsuser.modal_user')
@stop
@section('footer')
    <script>
        $(function(){
			$('.btn_modal_user').click(function(e){
			
				if ( $(this).data('route') == 'update'){
					// Form
					$('#modalDepartmentUser form').prop('action',$('#modalDepartmentUser form').data('route-'+$(this).data('route'))+'/'+$(this).data('user_id'));
					$("#modalDepartmentUser input[name='_method']").first().val('PATCH');
					
					// Title
					$("#modalDepartmentUser .modal-title").text("{{ trans('ticketit::admin.deptuser-modal-title-update') }}");
					
					// Selects
					$("#modalDepartmentUser #user_select2 option[value='"+$(this).data('user_id')+"']").prop('selected', true);
					$("#modalDepartmentUser #department_select2 option[value='"+$(this).data('department_id')+"']").prop('selected', true);
				}else{
					// Form
					$('#modalDepartmentUser form').prop('action',$('#modalDepartmentUser form').data('route-'+$(this).data('route')));
					$("#modalDepartmentUser input[name='_method']").first().val('POST');
					
					// Title
					$("#modalDepartmentUser .modal-title").text("{{ trans('ticketit::admin.deptuser-modal-title-create') }}");
					
					// Selects
					$("#modalDepartmentUser #user_select2, #modalDepartmentUser #department_select2").prop('selectedIndex',0);
				}			
							
				$('#modalDepartmentUser').modal('show');				
				
				e.preventDefault();
			});
			
			
			$( ".deleteit" ).click(function( event ) {
				event.preventDefault();
				if (confirm("{!! trans('ticketit::admin.category-index-js-delete') !!}" + $(this).attr("node") + " ?"))
				{
					var form = $(this).attr("form");
					$("#" + form).submit();
				}

			});
		});
		
    </script>
@append