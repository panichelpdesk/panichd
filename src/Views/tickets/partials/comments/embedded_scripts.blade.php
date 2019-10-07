<script type="text/javascript">
$(function(){
    // Add a comment with the new ticket
    $('#add_comment').click(function(e){
       e.preventDefault();

       // Notification members
       var a_notifications_note = [{{ implode(',', $a_notifications['note']) }}];
       var a_notifications_reply = [{{ implode(',', $a_notifications['reply']) }}];

       var _cloned = $('#comment_template').clone();
       var _num = $('#comments .comment_block').length + 1;
       _cloned.prop('id', 'comment_' + _num);
       _cloned.find('.input_comment_num').val(_num);
       _cloned.find('.input_response_type').prop('disabled', false).attr('name', 'response_' + _num);
        @if($setting->grab('custom_recipients'))
            $(a_notifications_note).each(function(i,v){
                // Add other notes recipients
                _cloned.find('.note_recipients option[value=' + v + ']').prop('selected', true);
            });
            // Add current agent id
            _cloned.find('.note_recipients option[value=' + $('#agent_id').val() + ']').prop('selected', true);
            
            $(a_notifications_reply).each(function(i,v){
                // Add other reply recipients
                _cloned.find('.reply_recipients option[value=' + v + ']').prop('selected', true);
            });
            // Add current owner
            _cloned.find('.reply_recipients option[value=' + $('#owner_id').val() + ']').prop('selected', true);

            // Activate notification recipients selects
            _cloned.find('.note_recipients').prop('disabled', false).attr('name', 'comment_' + _num + '_recipients[]');
            _cloned.find('.reply_recipients').attr('name', 'comment_' + _num + '_recipients[]');
        @endif

       _cloned.find('.input_comment_text').prop('disabled', false).attr('name', 'comment_' + _num);

        _cloned.find('.btn_attach').attr('data-attach-id', 'comment_' + _num + '_attached');
        _cloned.find('.btn_attach').attr('data-attachments_prefix', 'comment_' + _num + '_');
        _cloned.find('#comment_template_attached').prop('id', 'comment_' + _num + '_attached');

       _cloned.find('.input_comment_notification_text').attr('name', 'comment_' + _num + '_notification_text');
       if ($('input[name=hidden]:checked').val() == 'true'){
           _cloned.find('.switch_response_type').hide();
       }
       _cloned.css('display', 'block');
       _cloned.appendTo('#comments');
       $('#comment_' + _num).find('.note_recipients').select2();
       $('#comment_' + _num).find('.input_comment_text').summernote(summernote_options);
    });

    // Switch between Internal note and Comment
    $('#comments').on('click', '.switch_response_type', function(e){
        e.preventDefault();
        var _block = $(this).closest('.comment_block');
        if ($(this).data('note-text') == $(this).find('.text').text()){
            // Switch to reply
            $(this).find('i.fas').removeClass('fa-comment').addClass('fa-pencil-alt');
            _block.find('.input_response_type').val('reply');
            $(this).find('.text').text($(this).data('comment-text'));
            _block.find('.note_title').hide();
            _block.find('.comment_title').show();
            @if($setting->grab('custom_recipients'))
                _block.find('.note_recipients').prop('disabled', true).select2('destroy').hide();
                _block.find('.reply_recipients').prop('disabled', false).show().select2();
            @else
                _block.find('.input_comment_notification_text').prop('disabled', false).closest('label').show();
            @endif
        }else{
            // Switch to internal note
            $(this).find('i.fas').removeClass('fa-pencil-alt').addClass('fa-comment');
            _block.find('.input_response_type').val('note');
            $(this).find('.text').text($(this).data('note-text'));
            _block.find('.note_title').show();
            _block.find('.comment_title').hide();
            @if($setting->grab('custom_recipients'))
                _block.find('.reply_recipients').prop('disabled', true).select2('destroy').hide();
                _block.find('.note_recipients').prop('disabled', false).show().select2();
            @else
                _block.find('.input_comment_notification_text').prop('disabled', true).closest('label').hide();
            @endif
        }
    });

    // Cancel comment addition
    $('#comments').on('click','.delete_comment', function(e){
        e.preventDefault();
        var _block = $(this).closest('.comment_block');
        _block.find('.input_response_type, .input_comment_text, .input_comment_notification_text').prop('disabled', true);
        _block.slideUp();
    });
});
</script>
