@extends($master)

@section('page')
    {{ trans('panichd::lang.index-title') }}
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
		@include('panichd::tickets.partials.priority_popover_form')
	@stop

	@section('footer')
		@include('panichd::tickets.datatable.loader')
		@include('panichd::tickets.datatable.events')
	@append
@endif

@if($ticketList == 'newest' && $setting->grab('newest_list_reload_seconds'))
    @php
        $toast_html = '<div class="alert alert-info">'
            . '<span id="toast_countdown">' . trans('panichd::lang.page-reload-modal-countdown', ['num' => '3', 'num_class' => 'bg-info'])
            . ' <button id="toast_cancel" type="button" class="btn btn-light btn-xs ml-2" data-new-countdown="no">' . trans('panichd::lang.btn-cancel') . '</button></span>'
            . '<span id="toast_reloading">' . trans('panichd::lang.page-reload-modal-reloading') . '</span>'
            . ''
            . '</div>';
    @endphp
    @include('panichd::shared.bottom_toast', ['toast_html' => $toast_html])
    @section('footer')
        <script type="text/javascript">
            var page_reload = "";

            function show_bottom_toast(reload_secs)
            {
                // Show bottom toast
                $('#toast_countdown #counter').text(reload_secs-1);
                $('#toast_countdown').show();
                $('#toast_reloading').hide();
                $('#bottom_toast').addClass('show');

                // Update the count down every 1 second
                page_reload = setInterval(function() {
                    reload_secs = reload_secs - 1;

                    // Output the result in an element with id="demo"
                    $('#toast_countdown #counter').text(reload_secs);

                    // If the count down is over, replace text
                    if (reload_secs == 0) {
                        $('#toast_countdown').hide();
                        $('#toast_reloading').show();

                        clearInterval(page_reload);
                        window.location.reload(false);
                    }
                }, 1000);
            }

            $(function(){
                var toast_countdown = ({{ $setting->grab('newest_list_reload_seconds') }} - 3)*1000;
                var reload_secs = 4;

                setTimeout(function(){ show_bottom_toast(reload_secs) }, toast_countdown);

                $('#toast_cancel').click( function () {
                    // Clear countdown
                    clearInterval(page_reload);
                    $('#bottom_toast').removeClass('show');

                    // If set to create a new countdown
                    if ($(this).data('new-countdown') == 'yes'){
                        page_reload = "";
                        reload_secs = 4;
                        setTimeout(function(){ show_bottom_toast(reload_secs) }, toast_countdown);

                    }
                });
            });
        </script>
    @append
@endif
