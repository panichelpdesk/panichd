@extends($master)

@section('page')
    @if($ticketList == 'newest')
        {{ trans('panichd::lang.nav-new-tickets-title') }}
    @elseif($ticketList == 'complete')
        {{ trans('panichd::lang.nav-completed-tickets-title') }}
    @else
        {{ trans('panichd::lang.nav-active-tickets-title') }}
    @endif
@stop

@include('panichd::shared.common')

@include('panichd::tickets.datatable.assets')

@if (PanicHD\PanicHD\Models\Ticket::count() == 0)
	@section('content')
		<div class="card bg-light">
			<div class="card-body">
				{{ trans('panichd::lang.no-tickets-yet') }}
			</div>
		</div>
	@stop
@else
	@section('content')
		@include('panichd::tickets.partials.filter_panel')
		<div class="card bg-light">
			<div class="card-body">
				<div id="message"></div>
				@include('panichd::tickets.datatable.header')
			</div>
		</div>
		@include('panichd::tickets.partials.modal_agent')
	@stop

	@include('panichd::tickets.partials.data_reload')

	@section('footer')
		@include('panichd::tickets.datatable.loader')
		@include('panichd::tickets.datatable.events')
	@append
@endif

