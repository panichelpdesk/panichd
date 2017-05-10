@extends($master)
@section('page', trans('ticketit::admin.category-edit-title', ['name' => ucwords($category->name)]))

@section('content')
    @include('ticketit::shared.header')
    <style type="text/css">
	#tag-panel .btn {
		border: transparent;
	}
	
	#tag-panel .glyphicon {
		color: #777;
	}	
	
	.jquery_tag_group_unchecked .glyphicon-ok, .jquery_tag_group_checked .glyphicon-remove {
		display: none;
	}
	.jquery_tag_group_checked .glyphicon-ok, .jquery_tag_group_unchecked .glyphicon-remove {
		display: inline !important;
	}	

	.jquery_tag_group_checked .jquery_tag_text {
		text-decoration: line-through;
		color: black !important;
		background-color: #ff9999 !important;
	}
	
	#jquery_popup_tag_input {
		border: transparent;
		box-shadow: none;
	}
	</style>
	<div class="well bs-component">
        {!! CollectiveForm::model($category, [
			'route' => [$setting->grab('admin_route').'.category.update', $category->id],
			'method' => 'PATCH',
			'class' => 'form-horizontal'
			]) !!}
        <legend>{{ trans('ticketit::admin.category-edit-title', ['name' => ucwords($category->name)]) }}</legend>
        @include('ticketit::admin.category.form', ['update', true])
        {!! CollectiveForm::close() !!}
    </div>
	<div class="modal fade" id="tag-edit-modal" tabindex="-1" role="dialog" aria-labelledby="tag-edit-modal-Label">
    <div class="modal-dialog model-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">{{ trans('ticketit::lang.flash-x') }}</span></button>
                <h4 class="modal-title" id="tag-edit-modal-Label">Edit tag "<span id="jquery_popup_tag_title"></span>"</h4>
            </div>
            <div class="modal-body">
                <div class="col-sm-12">
                    <div class="form-group">
                        {!! CollectiveForm::text('name', 'Tag name', ['id'=>'jquery_popup_tag_input', 'class' => 'form-control', 'required']) !!}
                    </div> 
				</div>
				
				<div class="clearfix"></div>

				<div class="row">
					<div class="col-sm-6">
						<h4>Background</h4>
						<div id="pick_bg" class="colorpickerplus-embed">
						  <div class="colorpickerplus-container"> </div>
						</div>
					</div>
					<div class="col-sm-6">
						<h4>Text</h4>
						<div id="pick_text" class="colorpickerplus-embed">
						  <div class="colorpickerplus-container"> </div>
						</div>
					</div>
				</div>				

				<div class="modal-footer">					
					{!! CollectiveForm::button(trans('ticketit::lang.btn-submit'), ['id'=>'jquery_popup_tag_submit', 'class' => 'btn btn-primary']) !!}
				</div>
				
			</div>
		</div>
	</div>	
	@include('ticketit::admin.category.tags_script')
	<script type="text/javascript">
	var elem_i="";
	$(function(){
		var catColorPicker = $('#category_color_picker');
		catColorPicker.colorpickerplus();
		catColorPicker.on('changeColor', function(e, color){
			if(color==null) {
				//when select transparent color
				$('.color-fill-icon', $(this)).addClass('colorpicker-color');
				$('#category_color').val('#000000');
			} else {
				$('.color-fill-icon', $(this)).removeClass('colorpicker-color');
				$('.color-fill-icon', $(this)).css('background-color', color);
				$('#category_color').val(color);
			}
		});		
		
		$('#tag-edit-modal').on('show.bs.modal', function (e)
		{
			var button=$(e.relatedTarget);
			
			// Text to modal
			$(this).find('#jquery_popup_tag_title').text(button.data('tag_name'));
			$(this).find('#jquery_popup_tag_input').val(button.data('tag_name'));
			
			// Element identifier to modal
			elem_i=button.data('tag_i');
			
			// Colors to modal
			var a_colors=$('#jquery_tag_color_'+elem_i).val().split("_");
			$('#tag-edit-modal #pick_bg .colorpicker-element').val(a_colors[0]);
			$('#tag-edit-modal #pick_text .colorpicker-element').val(a_colors[1]);
			$('#tag-edit-modal #jquery_popup_tag_input').css('background',a_colors[0]).css('color',a_colors[1]);
			
		});
		$('#jquery_popup_tag_submit').click(function(e)
		{
			// Text change
			var disable=true;
			var modaltext=$('#tag-edit-modal #jquery_popup_tag_input').val();
			if ($('#tag_text_'+elem_i).data('tag_name') != modaltext){
				disable=false;
				$('#jquery_tag_name_'+elem_i).val(modaltext);
				$('#tag_text_'+elem_i).find('.name').text(modaltext);
			} 	
			$('#jquery_tag_name_'+elem_i).prop('disabled', disable);
			
			// Color change
			var bg_color = $('#tag-edit-modal #pick_bg .colorpicker-element').val();
			var text_color = $('#tag-edit-modal #pick_text .colorpicker-element').val();
			$('#tag_text_'+elem_i)
				.css('background-color', bg_color)
				.css('color', text_color);
			
			if ($('#jquery_tag_color_'+elem_i).val()!=bg_color+"_"+text_color){
				$('#jquery_tag_color_'+elem_i).prop('disabled',false);
			}
			$('#jquery_tag_color_'+elem_i).val(bg_color+"_"+text_color);
			
			$('#tag-edit-modal').modal('hide');
		});

		var tagColorPicker = $('#tag-edit-modal .colorpickerplus-embed .colorpickerplus-container');
		  tagColorPicker.colorpickerembed();
		  tagColorPicker.on('changeColor', function(e, color){
			var paintTarget = $(e.target).parent().prop('id') == "pick_bg" ? 'background-color' : 'color';
			if(color==null)
			  $('#tag-edit-modal #jquery_popup_tag_input').css(paintTarget, '#fff');//tranparent
			else
			  $('#tag-edit-modal #jquery_popup_tag_input').css(paintTarget, color);
		  });
	});	
	</script>
@stop
