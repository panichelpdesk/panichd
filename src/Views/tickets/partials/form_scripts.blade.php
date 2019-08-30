<script type="text/javascript">
$(function(){
    // DatetimePicker fields
    $('#start_date input[name="start_date"]').val('');
    $('#start_date').datetimepicker({
        locale: '{{App::getLocale()}}',
        format: '{{ trans('panichd::lang.datetimepicker-format') }}',
        @if (isset($ticket) && $a_current['start_date'] != "")
            defaultDate: "{{ $a_current['start_date'] }}",

        @elseif(isset($search_fields['timestamp_start_date']))
            defaultDate: "{{ $search_fields['timestamp_start_date'] }}",
        @endif
        keyBinds: { 'delete':null, 'left':null, 'right':null }
    });

    $('#limit_date input[name="limit_date"]').val('');
    $('#limit_date').datetimepicker({
        locale: '{{App::getLocale()}}',
        format: '{{ trans('panichd::lang.datetimepicker-format') }}',
        @if (isset($ticket) && $a_current['limit_date'] != "")
            defaultDate: "{{ $a_current['limit_date'] }}",
        
        @elseif(isset($search_fields['timestamp_limit_date']))
            defaultDate: "{{ $search_fields['timestamp_limit_date'] }}",
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