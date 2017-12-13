@section('header')
	@include('ticketit::shared.assets')
@append

@section('ticketit_nav')
	@include('ticketit::shared.nav')
@stop

@section('ticketit_errors')
	@include('ticketit::shared.errors')
@stop

@section('footer')
	<script type="text/javascript">
	$(function(){
		// Tooltips
		$('.tooltip-info, .tooltip-show').tooltip();
		
		// Default Select2
		$('.generate_default_select2').select2();
		
		// jQuery general form AJAX POST
		$('.ajax_form_submit').click(function(e){
			var form = $(this).closest('form');
			var formData = new FormData(form[0]);
			var errors_div = $(this).data('errors_div');
			
			e.preventDefault();
			
			$.ajax({
				type: "POST",
				url: form.prop('action'),
				contentType: false,
				processData: false,
				data: formData,
				success: function( response ) {
					$('#'+errors_div).find('ul li').remove();
					
					if (response.result != 'ok'){
						$.each(response.messages,function(index, value){
							$('#'+errors_div).find('ul').append('<li>'+value+'</li>');
						});
						
						$('#'+errors_div).show();
						document.body.scrollTop = 0;
						document.documentElement.scrollTop = 0;
					}else{
						$('#'+errors_div).hide();
						if (response.url != ""){
							window.location.href=response.url;
						}			
					}
				}
			});
		});
	});
	
	
	function common_ajax_post(form, formData, errors_div){
		
	}
	</script>
@append