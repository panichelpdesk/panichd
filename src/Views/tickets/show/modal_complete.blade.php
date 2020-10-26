<!-- Modal Dialog -->
<div class="modal fade" id="ticket-complete-modal" role="dialog" aria-hidden="true">
  <div class="modal-dialog {{ $u->currentLevel() == 1 ? 'modal-lg' : ''}}">
    <div class="modal-content">
		<div class="modal-header">
			<h4 class="modal-title">{{ trans('panichd::lang.mark-complete') }}</h4>
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">{{ trans('panichd::lang.flash-x') }}</button>
		</div>
		<div class="modal-body">

		{!! CollectiveForm::open([
			'method' => 'PATCH',
			'route' => [
						$setting->grab('main_route').'.complete',
						$ticket->id
						],
			'id' => 'complete-ticket-form'
		]) !!}

		@if ($u->currentLevel() > 1 && $u->canManageTicket($ticket->id))
			<div class="form-group row">
				{!! CollectiveForm::label('status_id', trans('panichd::lang.status') . trans('panichd::lang.colon'), ['class' => 'col-lg-2 col-form-label']) !!}
				<div class="col-lg-10">
					{!! CollectiveForm::select('status_id', $complete_status_list, $setting->grab('default_close_status_id'), [
						'class' => 'form-control'
					]) !!}
				</div>
			</div>

			@if (!$ticket->intervention_html)
				<div class="form-group row">
					<div class="col-lg-10 offset-lg-2">
						<label><input type="checkbox" id="blank_intervention_check" name="blank_intervention" value="yes"> {{ trans('panichd::lang.show-ticket-modal-complete-blank-intervention-check') }}</label>
					</div>
				</div>
			@endif
		@else
			@if ($a_reasons)
				<div class="form-group row">
				{!! CollectiveForm::label(null, trans('panichd::lang.closing-reason') . trans('panichd::lang.colon'), ['class' => 'col-md-3 col-form-label']) !!}

				<div class="col-md-9">
				@foreach ($a_reasons as $id => $reason)
					<label>{!! CollectiveForm::radio('reason_id',$id) !!} {{ $reason }}</label><br />
				@endforeach
				</div>
				</div>
			@else
				{!! CollectiveForm::hidden('reason_id','none') !!}
			@endif


			<div class="form-group row">
                {!! CollectiveForm::label('clarification', trans('panichd::lang.closing-clarifications') . trans('panichd::lang.colon'), ['class' => 'col-md-3 col-form-label']) !!}
                <div class="col-md-9">
                    {!! CollectiveForm::textarea('clarification', null, ['class' => 'form-control summernote-editor', 'rows' => '5']) !!}
                </div>
            </div>
		@endif

		{!! CollectiveForm::close() !!}
		</div>
		<div class="modal-footer d-block text-right">
			@if ($u->currentLevel() > 1)
				<a id="edit-with-values" class="btn btn-default float-left" href="{{ route($setting->grab('main_route').'.edit', ['ticket' => $ticket->id]) . '/complete/yes/status_id/' . $setting->grab('default_close_status_id') }}">{{ trans('panichd::lang.show-ticket-modal-edit-fields') }}</a>
			@endif
			<button type="button" id="complete_form_submit" class="btn btn-danger">{{ trans('panichd::lang.btn-mark-complete') }}</button>
		</div>

</div>
</div>
</div>
