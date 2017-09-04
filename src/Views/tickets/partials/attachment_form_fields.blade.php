<div class="form-group">
	{!! CollectiveForm::label('original_filename', trans('ticketit::lang.attachment-edit-original-filename') . trans('ticketit::lang.colon'), ['class' => 'col-md-3 control-label']) !!}
	<div class="col-md-9">
		<span id="attachment_form_original_filename"></span>
	</div>
</div>
					
<div class="form-group">
	{!! CollectiveForm::label('new_filename', trans('ticketit::lang.attachment-edit-new-filename') . trans('ticketit::lang.colon'), ['class' => 'col-md-3 control-label']) !!}
	<div class="col-md-9">
		{!! CollectiveForm::text('new_filename', null , [
			'id' => 'attachment_form_new_filename',
			'class' => 'form-control',
			]) !!} 
	</div>						 
</div>

<div class="form-group">
	{!! CollectiveForm::label('description', trans('ticketit::lang.description') . trans('ticketit::lang.colon'), ['class' => 'col-md-3 control-label']) !!}
	<div class="col-md-9">
		{!! CollectiveForm::text('description', null , [
			'id' => 'attachment_form_description',
			'class' => 'form-control',
			]) !!} 
	</div>						 
</div>

{!! CollectiveForm::hidden(null, null, ['id'=>'attachment_form_prefix']) !!}