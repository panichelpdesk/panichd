<script type="text/javascript">
$(function(){
    // Add a comment with the new ticket
    $('#add_comment').click(function(e){
       e.preventDefault();

       var _cloned = $('#comment_template').clone();
       var _num = $('#comments .comment_block').length + 1;
       _cloned.prop('id', 'comment_' + _num);
       _cloned.find('.input_comment_num').val(_num);
       _cloned.find('.input_response_type').prop('disabled', false).attr('name', 'response_' + _num);
       _cloned.find('.input_comment_text').prop('disabled', false).attr('name', 'comment_' + _num);
       _cloned.find('.input_comment_notification_text').prop('disabled', false).attr('name', 'comment_' + _num + '_notification_text');
       if ($('input[name=hidden]:checked').val() == 'true'){
           _cloned.find('.switch_response_type').hide();
       }
       _cloned.css('display', 'block');
       _cloned.appendTo('#comments');
       $('#comment_' + _num).find('.input_comment_text').summernote(summernote_options);
    });

    // Switch between Internal note and Comment
    $('#comments').on('click', '.switch_response_type', function(e){
        e.preventDefault();
        var _block = $(this).closest('.comment_block');
        if ($(this).data('note-text') == $(this).find('.text').text()){
            // Switch to comment
            $(this).find('i.fas').removeClass('fa-comment').addClass('fa-pencil-alt');
            _block.find('.input_response_type').val('reply');
            $(this).find('.text').text($(this).data('comment-text'));
            _block.find('.note_title').hide();
            _block.find('.comment_title').show();
            _block.find('.input_comment_notification_text').closest('label').show();
        }else{
            // Switch to internal note
            $(this).find('i.fas').removeClass('fa-pencil-alt').addClass('fa-comment');
            _block.find('.input_response_type').val('note');
            $(this).find('.text').text($(this).data('note-text'));
            _block.find('.note_title').show();
            _block.find('.comment_title').hide();
            _block.find('.input_comment_notification_text').closest('label').hide();
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
