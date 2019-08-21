<script type="text/javascript">
$(function(){
    // Category select with $u->maxLevel() > 1 only
    $('#category_change').change(function(){
        // Update agent list
        $('#agent_id').prop('disabled',true);
        var loadpage = "{!! route($setting->grab('main_route').'agentselectlist') !!}/" + $(this).val() + "/"+$('#agent_id').val();
        $('#agent_id').load(loadpage, function(){
            $('#agent_id').prop('disabled',false).show();
        });


        @if ($u->currentLevel() > 1)
            // Get permission level for chosen category
            $.get("{!! route($setting->grab('main_route').'-permissionLevel') !!}/" + $(this).val(),{},function(resp,status){
                if (resp > 1){
                    $('.jquery_level2_class').each(function(elem){
                        $(this).addClass($(this).attr('data-class'));
                    });
                    // Form elements to show / hide
                    $('.jquery_level2_show').show();

                    // Form input to enable / disable
                    $('.jquery_level2_enable').prop('disabled', false);

                }else{
                    $('.jquery_level2_class').each(function(elem){
                        $(this).attr('class','jquery_level2_class');
                    });
                    // Form elements to show / hide
                    $('.jquery_level2_show').hide();

                    // Form input to enable / disable
                    $('.jquery_level2_enable').prop('disabled', true);
                }

                var other = resp == 1 ? 2 : 1;
                $('.level_class').each(function(){
                    $(this).removeClass($(this).attr('data-level-'+other+'-class'));
                    $(this).addClass($(this).attr('data-level-'+resp+'-class'));
                });
            });
        @endif

        // Update tag list
        $('#jquery_select2_container .select2-container').hide();
        $('#jquery_tag_category_'+$(this).val()).next().show();
    });

    // DatetimePicker fields
    $('#start_date input[name="start_date"]').val('');
    $('#start_date').datetimepicker({
        locale: '{{App::getLocale()}}',
        format: '{{ trans('panichd::lang.datetimepicker-format') }}',
        @if (isset($ticket) && $a_current['start_date'] != "")
            defaultDate: "{{ $a_current['start_date'] }}",
        @endif
        keyBinds: { 'delete':null, 'left':null, 'right':null }
    });

    $('#limit_date input[name="limit_date"]').val('');
    $('#limit_date').datetimepicker({
        locale: '{{App::getLocale()}}',
        format: '{{ trans('panichd::lang.datetimepicker-format') }}',
        @if (isset($ticket) && $a_current['limit_date'] != "")
            defaultDate: "{{ $a_current['limit_date'] }}",
        @endif
        keyBinds: { 'delete':null, 'left':null, 'right':null },
        useCurrent: false
        @if (isset($a_current) && $a_current['start_date'] != "")
            , minDate: '{{ $a_current['start_date'] }}'
        @endif
    });

    $('#start_date .btn, #limit_date .btn').click(function(e){
        e.preventDefault();
        $('#' + $(this).closest('.input-group').prop('id')).data("DateTimePicker").toggle();
    });

    $("#start_date").on("dp.change", function (e) {
        $('#limit_date').data("DateTimePicker").minDate(e.date);
    });
    $("#limit_date").on("dp.change", function (e) {
        $('#start_date').data("DateTimePicker").maxDate(e.date);
    });
});
</script>