<script type="text/javascript">
$(function(){
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

        }else{
            // Hide visible agents
            $('#select_visible_agent').prop('disabled',true).hide();
            
            // Update agent list
            var loadpage = "{!! route($setting->grab('main_route').'agentselectlist') !!}/" + $(this).val() + "/"+$('#select_category_agent').val();
            $('#select_category_agent').load(loadpage, function(){
                $('#select_category_agent').prop('disabled',false).show();
            });

            // Update tag list
            $('#jquery_select2_container .select2-container').hide();
            $('#jquery_tag_category_'+$(this).val()).next().show();
        }
    });
});
</script>