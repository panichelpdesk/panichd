<script type="text/javascript">
var elem_i="";
$(function(){	
	// Make reason list orderable by mouse drag and drop
	$('#reason_list').sortable();
	
	// Category notifications email edition
	$('#email-edit-modal').on('show.bs.modal', function (e)
	{
		if ($('#email-edit-modal #email_scope_default input').is(':checked')){
			$('#email_replies_0 input, #email_replies_1 input').prop('disabled', true);
		}
	});
	
	$('#email-edit-modal #email_scope_default').click(function(e){
		$('.jquery_email').prop('disabled', true);
		$('#email_replies_0 input').prop('checked', true);
		$('#email_replies_0 input, #email_replies_1 input').prop('disabled', true);
		$(this).find('input').prop('checked', true);
		e.preventDefault(); // Avoid event executed twice
	});
	$('#email-edit-modal #email_scope_category').click(function(e){
		$('.jquery_email').prop('disabled', false);
		$('#email_replies_0 input, #email_replies_1 input').prop('disabled', false);
		$(this).find('input').prop('checked', true);
		e.preventDefault(); // Avoid event executed twice
	});
	
	$('#jquery_popup_email_submit').click(function(e){
		$('#email-edit-modal').modal('hide');
		e.preventDefault();
	});

	
	// Click on existing reason text
	$('#reason-edit-modal').on('show.bs.modal', function (e)
		{
			var button=$(e.relatedTarget);
			
			// Element identifier to modal
			elem_i=button.data('i');
			
			// Text to modal
			$(this).find('#jquery_popup_reason_text').val($('#jquery_reason_text_'+elem_i).val());
			$(this).find('#jquery_popup_select_status').val($('#jquery_reason_status_id_'+elem_i).val());						
		});
	
	// Click inside reason modal on submit button
	$('#jquery_popup_reason_submit').click(function(e)
	{
		var modal_text=$('#reason-edit-modal #jquery_popup_reason_text').val();
		var modal_status_id = $('#reason-edit-modal #jquery_popup_select_status').val();
		var modal_status_text = $('#reason-edit-modal #jquery_popup_select_status option[value=\''+modal_status_id+'\']').text();
		
		if (modal_text != ""){
			if (elem_i == 'new'){
				
				new_i = $('#reasons_count').val();
				$('#reasons_count').val(parseInt(new_i)+1);
				
				newreason = $('#reason_template').clone()
					.attr('id','reason_wrap_'+new_i).css('display','block');
				
				// Button element
				newreason.find('#reason_tempnum')
					.attr('id','reason_'+new_i)						
					.attr('data-i', new_i)
					.attr('data-text', modal_text);
				
				// Text element
				newreason.find('.reason_text').text(modal_text);
				newreason.find('.reason_status').text(modal_status_text);
				
				// Hidden inputs
				newreason.find('input:hidden').each(function(elem){
					$(this).attr('id',$(this).attr('id').replace('tempnum',new_i));
					$(this).attr('name',$(this).attr('name').replace('tempnum',new_i));
					$(this).prop('disabled', false);
				});
				newreason.find('#jquery_delete_reason_'+new_i).val(new_i).prop('disabled', true);
				newreason.find('#jquery_reason_ordering_'+new_i).val(new_i);
				newreason.find('#jquery_reason_text_'+new_i).val(modal_text);
				newreason.find('#jquery_reason_status_id_'+new_i).val(modal_status_id);
				
				// Append to DOM
				$('#reason_list').append(newreason);

			}else{
				// Text change
				var disable=true;				
				
				if ($('#reason_'+elem_i).data('reason_text') != modal_text){
					disable=false;
					$('#jquery_reason_text_'+elem_i).val(modal_text);
					$('#reason_'+elem_i).find('.reason_text').text(modal_text);
				} 	
				$('#jquery_reason_text_'+elem_i).prop('disabled', disable);
				
				// Status change		
				if ($('#jquery_reason_status_id_'+elem_i).val()!=modal_status_id){
					$('#jquery_reason_status_id_'+elem_i).val(modal_status_id);
					$('#reason_'+elem_i).find('.reason_status').text(modal_status_text);
				
					$('#jquery_reason_status_id_'+elem_i).prop('disabled',false);
				}
				
			}
		}		
		
		$('#reason-edit-modal').modal('hide');
	});	
});
</script>