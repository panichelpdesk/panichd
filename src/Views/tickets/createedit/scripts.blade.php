<script type="text/javascript">
// PhotoSwipe items array (load before jQuery .pwsp_gallery_link click selector)
var pswpItems = [
    @if(isset($ticket))
    @foreach($ticket->allAttachments()->images()->get() as $attachment)
        @if($attachment->image_sizes != "")
            <?php
                $sizes = explode('x', $attachment->image_sizes);
            ?>
            {
                src: '{{ URL::route($setting->grab('main_route').'.view-attachment', [$attachment->id]) }}',
                w: {{ $sizes[0] }},
                h: {{ $sizes[1] }},
                pid: {{ $attachment->id }},
                title: '{{ $attachment->new_filename  . ($attachment->description == "" ? '' : trans('panichd::lang.colon').$attachment->description) }}'
            },
        @endif
    @endforeach
    @endif
];

var category_id=<?=$a_current['cat_id'];?>;

$(function(){
    // Change in List affects current status
    $('.jquery_ticket_list').click(function(){
        var new_status = "";

        if ($(this).data('default_status_id') != ""){
            if ($(this).data('list') == 'newest'){
                new_status = $(this).data('default_status_id');
            }else if($('#last_list').data('last_list_default_status_id') == $('#select_status').val()){
                new_status = $(this).data('default_status_id');
            }

            if (new_status != ""){
                $('#select_status').val(new_status).effect('highlight');
            }

            $('#last_list').data('last_list_default_status_id', $(this).data('default_status_id'));
        }
    });

    @if($setting->grab('use_default_status_id'))
        // Change in status affects the List only changing from or to default_status_id
        $('#select_status').change(function(){
            if ($(this).val() == '{{ $setting->grab('default_status_id') }}'){
                if (!$('#radio_newest_list').is(':checked')) $('#radio_newest_list').prop('checked', true).parent().effect('highlight');
            }else{
                if ($('#radio_newest_list').is(':checked')) $('#radio_active_list').prop('checked', true).parent().effect('highlight');
            }
        });
    @endif

    // Category select with $u->maxLevel() > 1 only
    $('#category_change').change(function(){
        // Update agent list
        $('#agent_id').prop('disabled',true);
        var loadpage = "{!! route($setting->grab('main_route').'agentselectlist') !!}/" + $(this).val() + "/"+$('#agent_id').val();
        $('#agent_id').load(loadpage, function(){
            $('#agent_id').prop('disabled',false);
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
        @if ($a_current['start_date'] != "")
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
