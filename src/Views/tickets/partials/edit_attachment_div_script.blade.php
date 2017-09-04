@section('footer')
	<script type="text/javascript">
	$(function(){
		// Edit attachment button when already in modal window (can't create another one)
		$(document).on('click', '.edit_attachment', function(e){
			var editdiv = '#'+$(this).data('edit-div');
			var prefix = $(this).data('prefix');			
			
			$(editdiv).find('#attachment_form_original_filename').text($(this).data('original_filename'));
			$(editdiv).find('#attachment_form_new_filename').val($('#'+prefix+'new_filename').val());
			$(editdiv).find('#attachment_form_description').val($('#'+prefix+'description').val());
			$(editdiv).find('#attachment_form_prefix').val(prefix);			
			
			
			$(editdiv).show();
			$('#'+$(this).data('back-div')).hide();
			e.preventDefault();
		});
		
		// Discard attachment modifications in div
		$(document).on('click', '.div-discard-attachment-update', function(e){
			$('#'+$(this).data('edit-div')).hide();
			$('#'+$(this).data('back-div')).show();
		});		
	});
	</script>
@append