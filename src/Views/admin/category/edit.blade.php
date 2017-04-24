@extends($master)
@section('page', trans('ticketit::admin.category-edit-title', ['name' => ucwords($category->name)]))

@section('content')
    @include('ticketit::shared.header')
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
	<script type="text/javascript">
	$(function(){
		$('#admin-select2-tags').select2({
		  tags: true,
		  tokenSeparators: [',']
		});
		
		$('.jquery_button_delete_tag').click(function(e){
			var i=$(this).prop('id').replace('tag_delete_','');
			$(this).hide();
			$('#tag_text_'+i).css('text-decoration','line-through').css('background-color','#ff9999');
			$('#jquery_delete_tag_'+i).prop('disabled',false);
			
			$('#tag_keep_'+i).show();
			
			e.preventDefault();
		});
		
		$('.jquery_button_keep_tag').click(function(e){
			var i=$(this).prop('id').replace('tag_keep_','');
			$(this).hide();
			$('#tag_text_'+i).css('text-decoration','').css('background-color','');
			$('#jquery_delete_tag_'+i).prop('disabled',true);
			
			$('#tag_delete_'+i).show();
			
			e.preventDefault();
		});
	});
	</script>
@stop
