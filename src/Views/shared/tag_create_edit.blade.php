@section('content')
    <div class="modal fade" id="tag-modal" tabindex="-1" role="dialog" aria-labelledby="tag-modal-Label">
        <div class="modal-dialog model-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="tag-modal-Label"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">{{ trans('panichd::lang.flash-x') }}</span></button>
                </div>
                <div class="modal-body">
                    <div class="col-sm-12">
                        <div class="form-group row">
                            {!! CollectiveForm::text('name', null, ['id'=>'jquery_popup_tag_input', 'class' => 'form-control', 'required']) !!}
                        </div> 
                    </div>
                    
                    <div class="clearfix"></div>

                    <div class="row">
                        <div class="col-sm-6">
                            <h4>{{ trans('panichd::admin.category-edit-tag-background') }}</h4>
                            <div id="pick_bg" class="colorpickerplus-embed">
                            <div class="colorpickerplus-container"> </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <h4>{{ trans('panichd::admin.category-edit-tag-text') }}</h4>
                            <div id="pick_text" class="colorpickerplus-embed">
                            <div class="colorpickerplus-container"> </div>
                            </div>
                        </div>
                    </div>				

                    <div class="modal-footer">					
                        {!! CollectiveForm::button(trans('panichd::lang.btn-change'), ['id'=>'jquery_popup_tag_submit', 'class' => 'btn btn-primary']) !!}
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
@append

@section('footer')
    <script type="text/javascript">
    
    /*
     * Count current page tags and generate new one HTML
    */
    function embed_new_tag_html()
    {
        // Set new element refference number
        elem_i = $('.btn-tag').length;

        // Generate new tag HTML
        $('#new_tags_container').append('<div class="btn-group check_parent unchecked">'
                +'<a href="#" role="button" id="jquery_tag_check_' + elem_i + '" class="btn btn-light check_button" data-delete_id="jquery_delete_tag_' + elem_i + '" title="Delete tag" aria-label="Delete tag"><span class="fa fa-times" aria-hidden="true"></span><span class="fa fa-check" aria-hidden="true" style="display: none"></span></a>'
                +'<a href="#" role="button" id="tag_text_' + elem_i + '" class="btn btn-light btn-tag check_info" data-toggle="modal" data-target="#tag-modal" data-tag_name="" data-i="' + elem_i + '"><span class="name"></span></a>'
                +'<input type="hidden" id="jquery_delete_tag_' + elem_i + '" name="jquery_delete_tag_' + elem_i + '" value="" disabled="disabled">'
                +'<input type="hidden" id="jquery_tag_id_' + elem_i + '" name="jquery_tag_id_' + elem_i + '" value="">'
                +'<input type="hidden" id="jquery_tag_name_' + elem_i + '" name="jquery_tag_name_' + elem_i + '" value="" disabled="disabled">'
                +'<input type="hidden" id="jquery_tag_color_' + elem_i + '" name="jquery_tag_color_' + elem_i + '" value="" disabled="disabled">'

                +'<input type="hidden" name="new_tags[]" value="' + elem_i + '">'
            +'</div>');

        return elem_i;
    }

    /*
     * Update tag properties in DOM
    */
    function update_tag_properties(elem_i, params)
    {
        // Text change
        var disable=true;

        if ($('#tag_text_'+elem_i).data('tag_name') != params.text){
            disable=false;
            $('#jquery_tag_name_'+elem_i).val(params.text);
            $('#tag_text_'+elem_i).find('.name').text(params.text);
        } 	
        $('#jquery_tag_name_'+elem_i).prop('disabled', disable);
        
        // Color change
        $('#tag_text_'+elem_i)
            .css('background-color', params.bg_color)
            .css('color', params.text_color);
        
        if ($('#jquery_tag_color_'+elem_i).val()!=params.bg_color+"_"+params.text_color){
            $('#jquery_tag_color_'+elem_i).prop('disabled',false);
        }
        $('#jquery_tag_color_'+elem_i).val(params.bg_color+"_"+params.text_color);
    }
    
    $(function(){
        // Click on create tag button
        $('#btn_tag_create').click(function(e){
            e.preventDefault();
        });
        
        // When showing tag create / edit modal window
        $('#tag-modal').on('show.bs.modal', function (e)
        {
            var button=$(e.relatedTarget);

            if (button.prop('id') == 'btn_tag_create'){
                // Create a new tag
                $(this).find('.modal-title').text('{{ trans('panichd::admin.category-edit-new-tag-title') }}');
                $(this).find('#jquery_popup_tag_input').val('{{ trans('panichd::admin.category-edit-new-tag-default') }}');
                var a_colors = ['#c9daf8', '#ffffff'];
                $(this).find('#jquery_popup_tag_submit').text('{{ trans('panichd::lang.btn-add') }}');

            }else{
                // Element identifier to modal
                elem_i=button.data('i');
                
                // Text to modal
                $(this).find('.modal-title').text('{{ trans('panichd::admin.category-edit-tag') . trans('panichd::admin.colon') }} "' + $('#jquery_tag_name_' + elem_i).val() + '"');
                $(this).find('#jquery_popup_tag_input').val($('#jquery_tag_name_' + elem_i).val());

                // Take tag colors from HTML element
                var a_colors=$('#jquery_tag_color_'+elem_i).val().split("_");

                // Submit text
                $(this).find('#jquery_popup_tag_submit').text('{{ trans('panichd::lang.btn-change') }}');
            }
            
            // Apply tag colors to modal
            $('#tag-modal #pick_bg .colorpicker-element').val(a_colors[0]);
            $('#tag-modal #pick_text .colorpicker-element').val(a_colors[1]);
            $('#tag-modal #jquery_popup_tag_input').css('background',a_colors[0]).css('color',a_colors[1]);
        });
        
        // Submit tag properties from modal
        $('#jquery_popup_tag_submit').click(function(e)
        {
            if ($(this).text() == '{{ trans('panichd::lang.btn-add') }}'){
                elem_i = embed_new_tag_html();
            }
            
            update_tag_properties(elem_i, {
                'text' : $('#tag-modal #jquery_popup_tag_input').val(),
                'bg_color' : $('#tag-modal #pick_bg .colorpicker-element').val(),
                'text_color' : $('#tag-modal #pick_text .colorpicker-element').val()
            });
            
            $('#tag-modal').modal('hide');
        });
        
        // Color Picker
        var tagColorPicker = $('#tag-modal .colorpickerplus-embed .colorpickerplus-container');
        tagColorPicker.colorpickerembed();
        tagColorPicker.on('changeColor', function(e, color){
            var paintTarget = $(e.target).parent().prop('id') == "pick_bg" ? 'background-color' : 'color';
            if(color == null){
                $('#tag-modal #jquery_popup_tag_input').css(paintTarget, '#fff');//tranparent
            }else{
                $('#tag-modal #jquery_popup_tag_input').css(paintTarget, color);
            }

        });

    });		
    </script>
@append