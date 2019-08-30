@section('panichd_assets')
	@include('panichd::shared.assets')
@append

@section('panichd_nav')
	<ul class="nav navbar-nav">
		@include('panichd::shared.nav')
	</ul>
@stop

@include('panichd::shared.errors')

@section('footer')
	<script type="text/javascript">
	
	function success_ajax_callback(response) {
		// You may define a specific callback in any form to do additional actions on success
	}

	$(function(){
		// Tooltips
		$('.tooltip-info, .tooltip-show').tooltip();
		
		// Default Select2
		$('.generate_default_select2').select2();
		
		// jQuery general form AJAX POST
		$('.ajax_form_submit').click(function(e){
			
			e.preventDefault();
			
			ajax_form_submit($(this));
		});
		
		// Make modal windows draggable
		$(".modal-dialog").draggable({
			handle: ".modal-header"
		});
	});
	
	function ajax_form_submit(_this)
	{
		// Disable submit button until AJAX response has come
		_this.prop('disabled', true);
		setTimeout(function(){ _this.prop("disabled", false);}, 3000);
		
		var form = _this.closest('form');
		var formData = new FormData(form[0]);
		var errors_div = _this.data('errors_div');
		
		$.ajax({
			type: "POST",
			url: form.prop('action'),
			contentType: false,
			processData: false,
			data: formData,
			success: function( response ) {
				// Reset error messages
				$('#'+errors_div).find('ul li').remove();
				form.find('.jquery_error .jquery_error_text').text('').hide();
				form.find('.jquery_error').removeClass('jquery_error');
				
				if (response.result != 'ok'){
					if (response.hasOwnProperty('redirect') && response.redirect != ""){
						window.location.href=response.redirect;
						return false;
					}
					
					// Add error panel messages
					$.each(response.messages,function(index, value){
						$('#'+errors_div).find('ul').append('<li>'+value+'</li>');
					});
					$('#'+errors_div).show();
					document.body.scrollTop = 0;
					document.documentElement.scrollTop = 0;
					
					// Add field attached errors
					$.each(response.fields,function(field, error){
						if (field.replace('attachment_block_', '') != field){
							// Attachment blocks
							form.find('#' + field + ':not(.comment_block)').parent('div').addClass('jquery_error');
							form.find('#' + field + ':not(.comment_block)').parent('div').find('.jquery_error_text').text(error).show();
						
						}else{
							// Form controls
							form.find('.form-control[name='+field+']').closest('div').addClass('jquery_error');
							form.find('.form-control[name='+field+']').closest('div').find('.jquery_error_text:first').text(error).show();
						}
					});
				
				}else{
					$('#'+errors_div).hide();
					if (response.hasOwnProperty('url') && response.url != ""){
						window.location.href=response.url;
						return false;
					}

					// Custom version in search form own script
					success_ajax_callback(response);		
				}
			}
		});
	}
	</script>
@append