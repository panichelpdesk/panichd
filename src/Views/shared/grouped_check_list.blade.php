<script type="text/javascript">
	$(function(){
		/*
		 * General Grouped Check List check / uncheck
		 * 
		 * Used in:
		 *     - Category edition (for Closing reasons, Tag list)
	     *     - Ticket / comment edition (for Attachments)
		 */
		$(document).on('click', '.grouped_check_list .check_button', function(e)
		{
			var delete_id = $(this).data('delete_id');
					
			if ($(this).closest('.check_parent').hasClass('unchecked')){
				// Check element to delete it
				$(this).closest('.check_parent').removeClass('unchecked').addClass('checked');
				
				$('#'+delete_id).prop('disabled',false);
			}else{
				// Uncheck element to keep it
				$(this).closest('.check_parent').removeClass('checked').addClass('unchecked');
				
				$('#'+delete_id).prop('disabled',true);
			}
			
			e.preventDefault();			
		});
	});
</script>