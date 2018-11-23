<script type="text/javascript">
$(function(){
    // When opening a comment modal,
    $('.comment-modal').on('show.bs.modal', function (e) {
        $('.comment-modal .alert-danger').hide();
        $(this).find('.fieldset-for-comment').show();
        $(this).find('.fieldset-for-attachment').hide();
    });

    $('.comment-modal').on('shown.bs.modal', function (e) {
        $(this).find('.modal-title').text($(e.relatedTarget).text());
        if ($(e.relatedTarget).data('add-comment') == 'no') $(this).find('#comment-type-buttons').hide();

        $(this).find("textarea.modal-summernote-editor").summernote(summernote_options);
    });

    $('.comment-modal').on('hidden.bs.modal', function (e) {
        $(this).find("textarea.modal-summernote-editor").summernote('destroy');
    });

    // Comment form: Click on response type buttons (reply or note)
    $('.response_type').click(function(){
        var type = $(this).attr('data-type');
        $('#modal-comment-new #response_type').val(type);
        $(this).addClass($(this).attr('data-active-class'));

        if (type == 'reply'){
            $('#add_in_user_notification_text, #add_to_intervention').prop('disabled', false);
            $('#add_in_user_notification_text, #add_to_intervention').closest('div').show();
        }else{
            $('#add_in_user_notification_text, #add_to_intervention').prop('disabled', true);
            $('#add_in_user_notification_text, #add_to_intervention').closest('div').hide();
        }

        var alt = type == 'note' ? 'reply' : 'note';
        $('#popup_comment_btn_'+alt).removeClass($('#popup_comment_btn_'+alt).attr('data-active-class'));
    });

    // Highlight related comment when showing related modal
    $('.jquery_panel_hightlight').on('show.bs.modal', function (e) {
        $(e.relatedTarget).closest('div.card').addClass('card-highlight');
    });

    $('.jquery_panel_hightlight').on('hidden.bs.modal', function (e) {
        $('div.card').removeClass('card-highlight');
    });

    $('#new_comment_submit').click(function(e){
        e.preventDefault();

        @if(isset($ticket) && $u->currentLevel() > 1 && !$ticket->intervention_html != "")
            if ($(this).closest('form').find('input[name="add_to_intervention"]').is(':checked') == false && $(this).closest('form').find('input[name="complete_ticket"]').is(':checked')){
                if(!confirm('{!! trans('panichd::lang.add-comment-confirm-blank-intervention') !!}')) return false;
            }
        @endif

        ajax_form_submit($(this));
    });

    // Click "X" to delete comment
    $('#modal-comment-delete').on('show.bs.modal', function (e) {
        if ($('#delete-comment-form').attr('data-default-action') == ''){
            $('#delete-comment-form').attr('data-default-action',$('#delete-comment-form').attr('action'));
        }

        // Add value to form
        $('#delete-comment-form').attr('action',$('#delete-comment-form').attr('action').replace('action_comment_id',$(e.relatedTarget).attr('data-id')));
    });

    // Dismiss comment deletion
    $('#modal-comment-delete').on('hidden.bs.modal', function (e) {
        $('#delete-comment-form').attr('action',$('#delete-comment-form').attr('data-default-action'));
    });

    // Comment confirm delete
    $( "#delete-comment-submit" ).click(function( event ) {
        event.preventDefault();
        $("#delete-comment-form").submit();
    });


    // Comment (reply) notifications resend modal
    $( "#email-resend-modal" ).on('show.bs.modal', function (e) {
        var button = $(e.relatedTarget);
        $(this).find('#owner').text($(button).attr('data-owner'));
        $(this).find('#comment_id').val($(button).attr('data-id'))
    });
});
</script>
