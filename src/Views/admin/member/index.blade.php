@extends($master)

@section('page')
{{ trans('panichd::admin.config-index-title') }}
@stop

@include('panichd::shared.common')

@section('content')
<div class="card bg-light">
    <div class="card-header">
      <button type="button" class="btn btn-primary float-right btn_member_modal" data-route="create">{{ trans('panichd::admin.btn-add-new') }}</button>
      <h3>{{ trans('panichd::admin.member-index-title') }}</h3>
    </div>
    <div class="card-body">
	@if (!session()->exists('status'))
		<div class="alert alert-info alert-dismissable fade show">
			<button type="button" class="close" data-dismiss="alert">×</button>
			<span class="fa fa-info-circle" style="color: #7ED5EC;"></span> {!! trans('panichd::admin.member-index-help') !!}
		</div>
	@endif
	@if(!$a_members)
		<div class="card bg-light">
			<div class="card-body text-center">{{ trans('panichd::admin.member-index-empty') }}</div>
		</div>
    @else
		<div id="message"></div>
            <table id="dataTable" class="table table-hover table-striped">
                <thead>
                    <tr>
                        <td>{{ trans('panichd::admin.table-name') }}</td>
						<td>{{ trans('panichd::admin.table-email') }}</td>
						<td>{{ trans('panichd::admin.role') }}</td>
						<td>{{ trans('panichd::admin.member-table-own-tickets') }}</td>
						<td>{{ trans('panichd::admin.member-table-assigned-tickets') }}</td>
						<td>{{ trans('panichd::admin.table-action') }}</td>
                    </tr>
                </thead>
                <tbody>
                @foreach($a_members as $member)
                    <tr>
                        <td>{{ $member->name }}</td>
                        <td>{{ $member->email }}</td>
						<td>
						@if ($member->panichd_admin == '1')
							<button type="button" disabled="disabled" class="btn btn-danger btn-xs">{{ trans('panichd::admin.admin') }}</button>
						@elseif ($member->panichd_agent == '1')
							<a href="{{ route($setting->grab('admin_route') . '.agent.index')}}" class="btn btn-warning btn-xs">{{ trans('panichd::lang.agent') }}</button>
						@else
							<button type="button" disabled="disabled" class="btn btn-light btn-default btn-xs">{{ trans('panichd::lang.user') }}</button>
						@endif
						</td>
						<td>{{ $member->tickets_as_owner_count }}</td>
						<td>{{ $member->tickets_as_agent_count }}</td>
						<td>
                            <button type="button" class="btn btn-light btn-default btn_member_modal" data-member_id="{{ $member->id }}" data-member_name="{{ $member->name }}" data-member_email="{{ $member->email }}" data-route="update" data-form_action="{{ route($setting->grab('admin_route').'.member.update', ['id' => $member->id ]) }}">{{ trans('panichd::admin.btn-edit') }}</button>
							@if ($member->panichd_admin != '1')
								@if ($member->tickets_as_owner_count != 0 || $member->tickets_as_agent_count != 0)
									<button type="button" class="btn btn-light btn-default"  disabled="disabled" title="{{ trans('panichd::admin.member-with-tickets-delete') }}"><strike>{{ trans('panichd::admin.btn-delete') }}</strike></button>

								@elseif($member->panichd_agent == '1')
									<button type="button" class="btn btn-light btn-default"  disabled="disabled" title="{{ trans('panichd::admin.member-delete-agent') }}"><strike>{{ trans('panichd::admin.btn-delete') }}</strike></button>

								@else
									{!! link_to_route(
										$setting->grab('admin_route').'.member.destroy', trans('panichd::admin.btn-delete'), $member->id,
										[
										'class' => 'btn btn-light btn-default deleteit',
										'form' => "delete-$member->id",
										"user" => $member->name
									]) !!}

									{!! CollectiveForm::open([
										'method' => 'DELETE',
										'route' => [
													$setting->grab('admin_route').'.member.destroy',
													$member->id
													],
										'id' => "delete-$member->id"
									]) !!}
									{!! CollectiveForm::close() !!}

								@endif
							@endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif
	</div>
</div>
	@include('panichd::admin.member.modal')
@stop
@section('footer')
    <script>
        $(function(){
			$('.btn_member_modal').click(function(e){

				if ( $(this).data('route') == 'update'){
					// Form
					$('#MemberModal form').prop('action',$(this).data('form_action'));
					$("#MemberModal input[name='_method']").first().val('PATCH');

					// Title
					$("#MemberModal .modal-title").text("{{ trans('panichd::admin.member-modal-update-title') }}");

					// Input
					$("#MemberModal #id_input").val($(this).data('member_id'));
					$("#MemberModal #name_input").val($(this).data('member_name'));
					$("#MemberModal #email_input").val($(this).data('member_email'));
					$('#MemberModal #password_label').text("{{ trans('panichd::admin.member-new-password-label') . trans('panichd::lang.colon') }}");

				}else{
					// Form action
					$('#MemberModal form').prop('action',$('#MemberModal form').data('route-create'));
					$("#MemberModal input[name='_method']").first().val('POST');

					// Title
					$("#MemberModal .modal-title").text("{{ trans('panichd::admin.member-modal-create-title') }}");

					// Input
					$("#MemberModal #id_input").val('');
					$("#MemberModal #name_input").val('{{ old("name") }}');
					$("#MemberModal #email_input").val('{{ old("email") }}');
					$('#MemberModal #password_label').text("{{ trans('panichd::admin.member-password-label') . trans('panichd::lang.colon') }}");
				}

				$('#password_input, #password_confirmation_input').val('');

				$('#MemberModal').modal('show');

				e.preventDefault();
			});


			$( ".deleteit" ).click(function( event ) {
				event.preventDefault();
				if (confirm("{{ trans('panichd::admin.member-delete-confirmation') }} "))
				{
					var form = $(this).attr("form");
					$("#" + form).submit();
				}

			});
		});

    </script>
@append
