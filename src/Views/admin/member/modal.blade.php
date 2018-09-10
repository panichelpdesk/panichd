<!-- Modal Dialog -->
<div class="modal fade" id="MemberModal" role="dialog" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
		<div class="modal-header">
			<h4 class="modal-title">{{ trans('panichd::admin.member-modal-create-title') }}</h4>
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">{{ trans('panichd::lang.flash-x') }}</button>
		</div>
		<div class="modal-body">
		{!! CollectiveForm::open([
			'route' => [$setting->grab('admin_route').'.member.store'],
			'method' => 'PATCH',
			'data-route-create' => route($setting->grab('admin_route').'.member.store')
			]) !!}
		{!! CollectiveForm::hidden('id', null, ['id' => 'id_input']) !!}

		<div class="form-group row">
			{!! CollectiveForm::label('name', trans('panichd::lang.name') . trans('panichd::lang.colon'), [
				'class' => 'col-form-label col-lg-3',
			]) !!}

			<div class="col-lg-9">
				{!! CollectiveForm::text('name', null , [
					'id' => 'name_input',
					'class' => 'form-control',
				]) !!}
			</div>
		</div>

		<div class="form-group row">
			{!! CollectiveForm::label('email', trans('panichd::lang.email') . trans('panichd::lang.colon'), [
				'class' => 'col-form-label col-lg-3',
			]) !!}

			<div class="col-lg-9">
				{!! CollectiveForm::text('email', null , [
					'id' => 'email_input',
					'class' => 'form-control',
				]) !!}
			</div>
		</div>

		<div class="form-group row">
			{!! CollectiveForm::label('password', trans('panichd::admin.member-password-label') . trans('panichd::lang.colon'), [
				'id' => 'password_label',
				'class' => 'col-form-label col-lg-3',
			]) !!}

			<div class="col-lg-9">
				<input type="password" name="password" value="" id="password_input" class="form-control">
			</div>
		</div>

		<div class="form-group row">
			{!! CollectiveForm::label('password_confirmation', trans('panichd::admin.member-password-repeat-label') . trans('panichd::lang.colon'), [
				'class' => 'col-form-label col-lg-3',
			]) !!}

			<div class="col-lg-9">
				<input type="password" name="password_confirmation" value="" id="password_confirmation_input" class="form-control">
			</div>
		</div>


		</div>
		<div class="modal-footer">
		<button type="submit" class="btn btn-danger">{{ trans('panichd::lang.btn-add') }}</button>
		</div>
		{!! CollectiveForm::close() !!}
    </div>
  </div>
</div>
