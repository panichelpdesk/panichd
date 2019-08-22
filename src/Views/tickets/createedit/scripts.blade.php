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

$(function(){
    // Change visibility affects embedded comments
    $('input[name=hidden]').click(function(e){
        if ($(this).val() == 'true'){
            $('.input_response_type[value=reply]:enabled').closest('.comment_block').find('.switch_response_type').trigger('click');
            $('.input_response_type:enabled').closest('.comment_block').find('.switch_response_type').hide();
        }else{
            $('.input_response_type:enabled').closest('.comment_block').find('.switch_response_type').show();
        }
    });

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

    /* Category change:
       - checks for permissions in new category
       - updates agent list
       - updates tag list
    */
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
        $('#tag_list_container .select2-container').hide();
        $('#jquery_tag_category_'+$(this).val()).next().show();
    });
});
</script>
