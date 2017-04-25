<script type="text/javascript">
$(function(){
	$('.jquery_button_uncheck_tag').click(function(e){
		var i=$(this).prop('id').replace('tag_uncheck_','');
		$(this).hide();
		$('#btn-group-'+i).removeClass('checked').addClass('unchecked');
		$('#jquery_tag_id_'+i).prop('disabled',true);
		
		$('#tag_check_'+i).show();
		
		e.preventDefault();
	});
	
	$('.jquery_button_check_tag').click(function(e){
		var i=$(this).prop('id').replace('tag_check_','');
		$(this).hide();
		$('#btn-group-'+i).removeClass('unchecked').addClass('checked');
		$('#jquery_tag_id_'+i).prop('disabled',false);
		
		$('#tag_uncheck_'+i).show();
		
		e.preventDefault();
	});
});
</script>