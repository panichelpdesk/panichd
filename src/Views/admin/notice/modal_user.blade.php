<!-- Modal Dialog -->
<div class="modal fade" id="modalDepartmentUser" role="dialog" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
		<div class="modal-header">
			<h4 class="modal-title">{{ trans('panichd::admin.btn-create-new-notice') }}</h4>
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">{{ trans('panichd::lang.flash-x') }}</button>
		</div>
		<div class="modal-body">
		{!! CollectiveForm::open([
			'route' => [$setting->grab('admin_route').'.notice.store'],
			'method' => 'PATCH',
			'data-route-create' => route($setting->grab('admin_route').'.notice.store')
			]) !!}
		<input type="hidden" id="user_input" name="user_id" value="" disabled="disabled">
	
		<div class="form-group row">
			{!! CollectiveForm::label('user_id', trans('panichd::lang.owner') . trans('panichd::lang.colon'), [
				'class' => 'col-form-label col-lg-3',
			]) !!}
			
			<div class="col-lg-9 modal_user_wrap">
				<div id="modal_user_name"></div>
				<select name="user_id" id="user_select2" class="form_select2 form-control" style="display: none; width: 100%">
				@foreach (\PanicHDMember::whereNull('ticketit_department')->orderBy('name')->get() as $user)
					<option value="{{ $user->id }}">{{ $user->name }} - {{ $user->email }}</option>
				@endforeach
				</select>
			</div>
		</div>
		
		<div class="form-group row">
			{!! CollectiveForm::label('department_id', trans('panichd::lang.department') . trans('panichd::lang.colon'), [
				'class' => 'col-form-label col-lg-3',
			]) !!}
		
			<div class="col-lg-9">
				<select name="department_id" id="department_select2" class="form_select2 form-control" style="display: none; width: 100%">
				<option value="0">{{ trans('panichd::lang.all-depts') }}</option>
				@foreach ($departments as $ancestor)
					<optgroup label="{{ $ancestor->name }}">
						{{ $ancestor->name }}
					</optgroup>
					@foreach($ancestor->descendants as $descendant)
						<option value="{{ $descendant->id }}">{{ $descendant->getShortName() }}</option>
					@endforeach
				@endforeach
				</select>
			</div>
		</div>
		 
		</div>
		<div class="modal-footer">
		<button type="submit" class="btn btn-danger">{{ trans('panichd::lang.btn-submit') }}</button>
		</div>
		{!! CollectiveForm::close() !!}
    </div>
  </div>
</div>
