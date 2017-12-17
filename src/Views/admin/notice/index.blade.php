@extends($master)

@section('page')
{{ trans('ticketit::admin.config-index-title') }}
@stop

@include('panichd::shared.common')

@section('content')

<div class="panel panel-default">
    <div class="panel-heading">
        <h3>{{ trans('ticketit::admin.notice-index-title') }}
            <div class="panel-nav pull-right" style="margin-top: -7px;">
                <button type="button" class="btn btn-default btn_modal_user" data-route="create">{{ trans('ticketit::admin.btn-create-new-notice') }}</button>
            </div>
        </h3>
    </div>
    <div class="panel-body">
		@if (!session()->exists('status'))
			<div class="alert alert-info alert-dismissable fade in">
				<button type="button" class="close" data-dismiss="alert">×</button>
				<span class="glyphicon glyphicon-info-sign" style="color: #7ED5EC;"></span> {!! trans('ticketit::admin.notice-index-help') !!}
			</div>
			<div class="alert alert-warning alert-dismissable fade in">
				<button type="button" class="close" data-dismiss="alert">×</button>
				<span class="glyphicon glyphicon-warning-sign" style="color: orange;"></span> {!! trans('ticketit::admin.notice-index-owner-alert') !!}
			</div>
		@endif

	@if(!$a_users)
        <div class="well text-center">{{ trans('ticketit::admin.notice-index-empty') }}</div>
    @else
		<div id="message"></div>
            <table class="table table-hover table-striped">
                <thead>
                    <tr>                        
                        <td>{{ trans('ticketit::admin.notice-index-owner') }}</td>
                        <td>{{ trans('ticketit::admin.notice-index-email') }}</td>
						<td>{{ trans('ticketit::admin.notice-index-department') }}</td>
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
                            @if ($d_user->userDepartment)
								<span title="{{ $d_user->userDepartment->title() }}">{{ $d_user->userDepartment->resume(true) }}</span>
							@else
								<span>{{ trans('ticketit::lang.all-depts') }}</span>
							@endif
							
                        </td>
						<td>
                            <button type="button" class="btn btn-info btn_modal_user" data-user_id="{{ $d_user->id }}" data-user_name="{{ $d_user->name }} - {{ $d_user->email }}" data-department_id="{{ $d_user->userDepartment ? $d_user->userDepartment->id : '0' }}" data-route="update" data-form_action="{{ route($setting->grab('admin_route').'.notice.update', ['id' => $d_user->id ]) }}">{{ trans('ticketit::admin.btn-edit') }}</button>
							{!! link_to_route(
							$setting->grab('admin_route').'.notice.destroy', trans('ticketit::admin.btn-delete'), $d_user->id,
							[
							'class' => 'btn btn-danger deleteit',
							'form' => "delete-$d_user->id",
							"user" => $d_user->name
							])
                                !!}
                            {!! CollectiveForm::open([
                                            'method' => 'DELETE',
                                            'route' => [
                                                        $setting->grab('admin_route').'.notice.destroy',
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
</div>
	@include('panichd::admin.notice.modal_user')
@stop
@section('footer')
    <script>
        $(function(){
			$('.btn_modal_user').click(function(e){
			
				if ( $(this).data('route') == 'update'){
					// Form
					$('#modalDepartmentUser form').prop('action',$(this).data('form_action'));
					$("#modalDepartmentUser input[name='_method']").first().val('PATCH');
					
					// Title
					$("#modalDepartmentUser .modal-title").text("{{ trans('ticketit::admin.notice-modal-title-update') }}");
					
					// Selects
					$("#modalDepartmentUser #user_select2 option[value='"+$(this).data('user_id')+"']").prop('selected', true);
					//$("#modalDepartmentUser #user_select2").hide();				
					$("#modalDepartmentUser #modal_user_name").show().text($(this).data('user_name'));
					$("#modalDepartmentUser #department_select2 option[value='"+$(this).data('department_id')+"']").prop('selected', true);
					
					// Select2
					$("#modalDepartmentUser .modal_user_wrap .select2-container").remove();
					$("#modalDepartmentUser #department_select2").select2();
				}else{
					// Form action
					$('#modalDepartmentUser form').prop('action',$('#modalDepartmentUser form').data('route-create'));			
					$("#modalDepartmentUser input[name='_method']").first().val('POST');
					
					// Title
					$("#modalDepartmentUser .modal-title").text("{{ trans('ticketit::admin.notice-modal-title-create') }}");
					
					// Selects
					$("#modalDepartmentUser #modal_user_name").text('').hide();					
					$("#modalDepartmentUser #user_select2, #modalDepartmentUser #department_select2").prop('selectedIndex',0);
					//$("#modalDepartmentUser #user_select2").show();
					$("#modalDepartmentUser #user_select2, #modalDepartmentUser #department_select2").select2();
				}			
				
				
				
				$('#modalDepartmentUser').modal('show');				
				
				e.preventDefault();
			});
			
			
			$( ".deleteit" ).click(function( event ) {
				event.preventDefault();
				if (confirm("{{ trans('ticketit::admin.notice-index-js-delete') }} "))
				{
					var form = $(this).attr("form");
					$("#" + form).submit();
				}

			});
		});
		
    </script>
@append