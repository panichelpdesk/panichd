@section('panichd_assets')
	@include('ticketit::shared.assets')
@append

@section('panichd_nav')
	@include('ticketit::shared.nav')
@stop

@section('panichd_errors')
	@include('ticketit::shared.errors')
@stop

@section('footer')
	<script type="text/javascript">
	$(function(){
		// Tooltips
		$('.tooltip-info, .tooltip-show').tooltip();
		
		// Default Select2
		$('.generate_default_select2').select2();
	});
	</script>
@append