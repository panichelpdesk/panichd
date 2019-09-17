@if($u->isAgent())
    @include('panichd::shared.bottom_toast')
    @section('footer')
        <script type="text/javascript">
            var last_update = "";
            var check_secs = {{ $setting->grab('check_last_update_seconds') }};
            var check_interval = "";
            var toast_interval = "";

           // Check last updated ticket for current list
            function check_last_update()
            {
                $.ajax({
                    url: '{{ route($setting->grab('main_route').'.last_update', $ticketList) }}',
                    data: {},
                    success: function( response ) {
                        if (response.result == 'ok'){
                            if(last_update == ""){
                                last_update = response.message;

                                // Restart check interval
                                init_check_last_update();

                            }else{
                                if(response.message != last_update){
                                    // New ticket update
                                    show_last_update_toast(response.message);
                                
                                }else{
                                    // Restart check interval
                                    init_check_last_update();
                                }
                            }
                        }
                    }
                });
            }

            function init_check_last_update()
            {
                clearInterval(check_interval);

                if (check_secs != '0'){
                    check_interval = setInterval(function(){ check_last_update() }, check_secs*1000);
                }
            }

            // When a new ticket has been detected, reload 
            function show_last_update_toast(new_message)
            {
                clearInterval(check_interval);
                clearInterval(toast_interval);
                
                var reload_secs = 6;

                // Show bottom toast
                $('#bottom_toast').empty().append('<div class="alert alert-info">'
                    + '<span id="toast_countdown">{!! trans('panichd::lang.reload-countdown', ['num' => '3', 'num_class' => 'bg-info']) !!}'
                    + ' <button id="toast_cancel" type="button" class="btn btn-light btn-xs ml-2">{{ trans('panichd::lang.btn-cancel') }}</button></span>'
                    + '<span id="toast_reloading">{{ trans('panichd::lang.reload-reloading') }}</span>'
                    + '</div>');
                
                $('#toast_countdown #counter').text(reload_secs-3);
                $('#toast_countdown').show();
                $('#toast_reloading').hide();
                $('#bottom_toast').addClass('show');

                // Update the count down every 1 second
                toast_interval = setInterval(function() {
                    reload_secs = reload_secs - 1;
                    
                    // Output the result in an element with id="demo"
                    $('#toast_countdown #counter').text(reload_secs-2);

                    // If the count down is over, replace text
                    if (reload_secs == 2) {
                        $('#toast_countdown').hide();
                        $('#toast_reloading').show();
                    }

                    // At the coundown end, hide toast
                    if (reload_secs == 0){
                        $('#bottom_toast').slideDown().removeClass('show');

                        // Apply new last update refference
                        last_update = new_message;

                        // Restart check interval
                        init_check_last_update();

                        // Hide any existent popover
                        $(".jquery_popover").popover('hide');

                        // Reload datatable
                        datatable.ajax.reload();
                    }

                }, 1000);
            }

            $(function(){
                // Get last update first value
                check_last_update();

                $('#toast_cancel').click( function () {
                    // Clear countdown
                    clearInterval(toast_interval);
                    clearInterval(check_interval);
                    $('#bottom_toast').removeClass('show');
                });
            });
        </script>
    @append
@endif