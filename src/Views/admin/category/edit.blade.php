@extends($master)
@section('page', trans('ticketit::admin.category-edit-title', ['name' => ucwords($category->name)]))

@section('content')
    @include('ticketit::shared.header')
    <style type="text/css">
	.jquery_tag_group_unchecked .glyphicon-ok, .jquery_tag_group_checked .glyphicon-remove {
		display: none;
	}
	.jquery_tag_group_checked .glyphicon-ok, .jquery_tag_group_unchecked .glyphicon-remove {
		display: inline !important;
	}	
	.jquery_tag_group_unchecked .jquery_tag_text {
		text-decoration: none;
		background-color: white;
	}
	.jquery_tag_group_checked .jquery_tag_text {
		text-decoration: line-through;
		background-color: #ff9999;
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
		$('#tag-edit-modal').on('show.bs.modal', function (e) {
			var button=$(e.relatedTarget);
			$(this).find('#jquery_popup_tag_title').text(button.data('tag_name'));
			$(this).find('#jquery_popup_tag_input').val(button.data('tag_name'));
			elem_i=button.data('tag_i');
		});
		$('#jquery_popup_tag_submit').click(function(e)
		{
			//alert($('#tag-edit-modal #jquery_popup_tag_input').val());
			var disable=true;
			var modaltext=$('#tag-edit-modal #jquery_popup_tag_input').val();
			if ($('#tag_text_'+elem_i).data('tag_name') != modaltext){
				disable=false;
				$('#jquery_tag_name_'+elem_i).val(modaltext);
				$('#tag_text_'+elem_i).find('.name').text(modaltext);
			} 	
			$('#jquery_tag_name_'+elem_i).prop('disabled', disable);
			
			$('#tag-edit-modal').modal('hide');
		});		
	});	
	</script>
@stop
