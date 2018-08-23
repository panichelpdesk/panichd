@section('panichd_assets')
	<link rel="StyleSheet" href="{{asset('vendor/panichd/css/pingcheng-bootstrap4-datetimepicker.min.css')}}" />
@append

@section('footer')
	<script type="text/javascript" src="{{asset('vendor/panichd/js/moment-with-locales-2.22.2.min.js')}}"></script>
	<script type="text/javascript" src="{{asset('vendor/panichd/js/pingcheng-bootstrap4-datetimepicker.min.js')}}"></script>
	<script type="text/javascript">
		$(function () {
            $.extend(true, $.fn.datetimepicker.defaults, {
                icons: {
                    time: 'far fa-clock',
                    date: 'far fa-calendar',
                    up: 'fas fa-arrow-up',
                    down: 'fas fa-arrow-down',
                    previous: 'fas fa-chevron-left',
                    next: 'fas fa-chevron-right',
                    today: 'fas fa-calendar-check',
                    clear: 'far fa-trash-alt',
                    close: 'far fa-times-circle'
                }
            });
        });
	</script>
@append