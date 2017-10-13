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
	});
	</script>
@append