<div class="form-group row">
	{!! CollectiveForm::label('original_filename', trans('panichd::lang.attachment-edit-original-filename') . trans('panichd::lang.colon'), ['class' => 'col-md-3 col-form-label']) !!}
	<div class="col-md-9">
		<span id="attachment_form_original_filename"></span>
	</div>
</div>
					
<div class="form-group row">
	{!! CollectiveForm::label('new_filename', trans('panichd::lang.attachment-edit-new-filename') . trans('panichd::lang.colon'), ['class' => 'col-md-3 col-form-label']) !!}
	<div class="col-md-9">
		{!! CollectiveForm::text('new_filename', null , [
			'id' => 'attachment_form_new_filename',
			'class' => 'form-control',
			]) !!} 
	</div>						 
</div>

<div class="form-group row">
	{!! CollectiveForm::label('description', trans('panichd::lang.description') . trans('panichd::lang.colon'), ['class' => 'col-md-3 col-form-label']) !!}
	<div class="col-md-9">
		{!! CollectiveForm::text('description', null , [
			'id' => 'attachment_form_description',
			'class' => 'form-control',
			]) !!} 
	</div>						 
</div>

<div id="attachment_form_image_row" style="display: none">
	<div class="form-group row">
		{!! CollectiveForm::label(null, trans('panichd::lang.crop-image') . trans('panichd::lang.colon'), ['class' => 'col-md-3']) !!}
		<div class="col-md-9">
			<div class="text-muted">{{ trans('panichd::lang.attachment-edit-crop-info') }}</div>
		</div>
	</div>
	
	<div class="row">
	<div class="col-xs-12 " style="text-align: center; margin: 0em 0em 1em 0em;">
	<div class="image_wrap" style="display: inline-block; margin: 0px auto;"></div>
	</div>
	</div>
</div>


{!! CollectiveForm::hidden(null, null, ['id'=>'attachment_form_prefix']) !!}