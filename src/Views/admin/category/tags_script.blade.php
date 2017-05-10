<script type="text/javascript">
	$(function()
	{
		$('#admin-select2-tags').select2({
		  tags: true,
		  tokenSeparators: [',']
		});
		
		$('.jquery_tag_check').click(function(e)
		{
			var i=$(this).prop('id').replace('jquery_tag_check_','');
			
			if ($(this).parent('.btn-group').hasClass('jquery_tag_group_unchecked')){
				// Check tag to delete it
				$(this).parent('.btn-group').removeClass('jquery_tag_group_unchecked').addClass('jquery_tag_group_checked');
				
				$('#jquery_delete_tag_'+i).prop('disabled',false);
			}else{
				// Uncheck tag to keep it
				$(this).parent('.btn-group').removeClass('jquery_tag_group_checked').addClass('jquery_tag_group_unchecked');
				
				$('#jquery_delete_tag_'+i).prop('disabled',true);
			}
			
			e.preventDefault();			
		});		
	});
</script>