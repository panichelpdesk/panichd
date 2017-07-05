<div class="modal fade" id="tag-edit-modal" tabindex="-1" role="dialog" aria-labelledby="tag-edit-modal-Label">
    <div class="modal-dialog model-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">{{ trans('ticketit::lang.flash-x') }}</span></button>
                <h4 class="modal-title" id="tag-edit-modal-Label">{{ trans('ticketit::admin.category-edit-tag') . trans('ticketit::admin.colon') }} "<span id="jquery_popup_tag_title"></span>"</h4>
            </div>
            <div class="modal-body">
                <div class="col-sm-12">
                    <div class="form-group">
                        {!! CollectiveForm::text('name', null, ['id'=>'jquery_popup_tag_input', 'class' => 'form-control', 'required']) !!}
                    </div> 
				</div>
				
				<div class="clearfix"></div>

				<div class="row">
					<div class="col-sm-6">
						<h4>{{ trans('ticketit::admin.category-edit-tag-background') }}</h4>
						<div id="pick_bg" class="colorpickerplus-embed">
						  <div class="colorpickerplus-container"> </div>
						</div>
					</div>
					<div class="col-sm-6">
						<h4>{{ trans('ticketit::admin.category-edit-tag-text') }}</h4>
						<div id="pick_text" class="colorpickerplus-embed">
						  <div class="colorpickerplus-container"> </div>
						</div>
					</div>
				</div>				

				<div class="modal-footer">					
					{!! CollectiveForm::button(trans('ticketit::lang.btn-change'), ['id'=>'jquery_popup_tag_submit', 'class' => 'btn btn-primary']) !!}
				</div>
				
			</div>
		</div>
	</div>
</div>