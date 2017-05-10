<script type="text/javascript">
$(function(){
	$('.jquery_tag_category').select2({
	  tags: false,
	  tokenSeparators: [',']
	});
	
	$('.jquery_tag_category').each(function(index){
		if (String($(this).prop('id').replace('jquery_tag_category_',''))!=String(category_id)){
			$(this).next().hide();
		}else{
			$(this).next().show();
		}
	});
});
</script>