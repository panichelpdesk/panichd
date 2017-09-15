<script type="text/javascript">
	$(function(){
		// General Grouped Check List check / uncheck
		$('.grouped_check_list').on('click', '.check_button', function(e)
		{	
			var delete_id = $(this).data('delete_id');
					
			if ($(this).closest('.check_parent').hasClass('unchecked')){
				// Check tag to delete it
				$(this).closest('.check_parent').removeClass('unchecked').addClass('checked');
				
				$('#'+delete_id).prop('disabled',false);
			}else{
				// Uncheck tag to keep it
				$(this).closest('.check_parent').removeClass('checked').addClass('unchecked');
				
				$('#'+delete_id).prop('disabled',true);
			}
			
			e.preventDefault();			
		});
	});
</script>