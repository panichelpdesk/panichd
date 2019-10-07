<script type="text/javascript">
$(function(){
    /*
     * Click on a configuration delete button
    */
    $('.j_configuration_delete').click(function(e){
        e.preventDefault();

        $('#configuration_delete_form').attr('action', $(this).data('form-action'));
        $('#configuration_delete_form').submit();
    });
});

</script>