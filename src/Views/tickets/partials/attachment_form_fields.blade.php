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

<div class="row" id="attachment_form_image_row" style="display: none">
<div class="col-xs-12"><b style="display: block; margin: 0em 0em 0.5em 0em;">{{ trans('ticketit::lang.cut-image') }}</b></div>
<div class="col-xs-12 " style="text-align: center; margin: 0em 0em 1em 0em;">
<div class="image_wrap" style="display: inline-block; margin: 0px auto;"></div>
</div>
</div>


{!! CollectiveForm::hidden(null, null, ['id'=>'attachment_form_prefix']) !!}