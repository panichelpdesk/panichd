<script type="text/javascript">
// After registering search fields
function success_ajax_callback(response) {
    // Hide search form
    $('#search_form').hide();

    // Clean search form previous filled fields
    $('#search_form .form-group.row').removeClass('bg-info');

    // Highlight filled fields
    $.each(response.search_fields,function(index, value){
        $('label[for=' + index + ']').closest('.form-group.row').addClass('bg-info');
    });

    // Update link with Search URL
    $('#copy_search_URL').attr('href', response.search_URL);

    // Load datatable with the new search fields
    datatable.ajax.reload();

    // Show datatable container
    $('#search_results').show();
}

$(function(){
    var tag_filters_without_tags = ['no_filter', 'has_not_tags', 'has_any_tag'];

    /* Category change:
       - checks for permissions in new category
       - updates agent list
       - updates tag list
    */
    $('#select_category').change(function(){
        
        $('#select_category_agent').prop('disabled',true);

        if ($(this).val() == ""){
            // Show visible agents
            $('#select_visible_agent').prop('disabled', false).show();

            // Hide agent list
            $('#select_category_agent').hide();

            // Hide tag selection
            $('#tag_list_container').hide();
            
            // Hide category tag rules
            $('#category_tags_rules').hide();

            if (tag_filters_without_tags.indexOf($('input[name=tags_type]:checked').val()) == -1){
                $('#tags_no_filter').click();
            }

        }else{
            // Hide visible agents
            $('#select_visible_agent').prop('disabled',true).hide();
            
            // Update agent list
            var loadpage = "{!! route($setting->grab('main_route').'agentselectlist') !!}/" + $(this).val() + "/"+$('#select_category_agent').val();
            $('#select_category_agent').load(loadpage, function(){
                // Show agent select
                $('#select_category_agent').prop('disabled',false).show();
                
                // Default agent has no value
                $('#select_category_agent option[value=auto]').val('').text('- none -');
            });

            // Update tag list
            $('#tag_list_container .select2-container').hide();
            $('#jquery_tag_category_'+$(this).val()).next().show();

            if (tag_filters_without_tags.indexOf($('input[name=tags_type]:checked').val()) == -1){
                // Show tag selection
                $('#tag_list_container').show();
            }

            // Hide category tag rules
            $('#category_tags_rules').show();
        }
    });

    @if(isset($search_fields['category_id']))
        // Show active category tags
        $('#jquery_tag_category_{{ $search_fields['category_id'] }}').next().show();
    @endif

    $('input[name=tags_type]').click(function(e){
        if ($(this).val() == 'no_filter' || $(this).val() == 'has_not_tags' || $(this).val() == 'has_any_tag'){
            $('#tag_list_container').slideUp();
        }else{
            $('#tag_list_container').slideDown();
        }
    });

    // Extra date fields with datetimepicker
    @foreach(['created_at', 'completed_at', 'updated_at'] as $date_field)
        $('#{{ $date_field }}').datetimepicker({
            locale: '{{App::getLocale()}}',
            format: '{{ trans('panichd::lang.datetimepicker-format') }}',
            keyBinds: { 'delete':null, 'left':null, 'right':null },
            useCurrent: false
        });

        $('#{{ $date_field }} .btn').click(function(e){
            e.preventDefault();
            $('#' + $(this).closest('.input-group').prop('id')).data("DateTimePicker").toggle();
        });
    @endforeach

    // Edit search button
    $('#edit_search').click(function(e){
        e.preventDefault();

        $('#search_results').hide();
        $('#search_form').slideDown();
    });
});
</script>