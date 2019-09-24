@section('content')
    <div class="modal fade" id="tag-modal" tabindex="-1" role="dialog" aria-labelledby="tag-modal-Label">
        <div class="modal-dialog model-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="tag-modal-Label">{{ trans('panichd::admin.category-edit-tag') . trans('panichd::admin.colon') }} "<span id="jquery_popup_tag_title"></span>"</h4>
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
    $(function(){

        $('#tag-modal').on('show.bs.modal', function (e)
        {
            var button=$(e.relatedTarget);
            
            // Text to modal
            $(this).find('#jquery_popup_tag_title').text(button.data('tag_name'));
            $(this).find('#jquery_popup_tag_input').val(button.data('tag_name'));
            
            // Element identifier to modal
            elem_i=button.data('i');
            
            // Colors to modal
            var a_colors=$('#jquery_tag_color_'+elem_i).val().split("_");
            $('#tag-modal #pick_bg .colorpicker-element').val(a_colors[0]);
            $('#tag-modal #pick_text .colorpicker-element').val(a_colors[1]);
            $('#tag-modal #jquery_popup_tag_input').css('background',a_colors[0]).css('color',a_colors[1]);
            
        });
        
        $('#jquery_popup_tag_submit').click(function(e)
        {
            // Text change
            var disable=true;
            var modaltext=$('#tag-modal #jquery_popup_tag_input').val();
            if ($('#tag_text_'+elem_i).data('tag_name') != modaltext){
                disable=false;
                $('#jquery_tag_name_'+elem_i).val(modaltext);
                $('#tag_text_'+elem_i).find('.name').text(modaltext);
            } 	
            $('#jquery_tag_name_'+elem_i).prop('disabled', disable);
            
            // Color change
            var bg_color = $('#tag-modal #pick_bg .colorpicker-element').val();
            var text_color = $('#tag-modal #pick_text .colorpicker-element').val();
            $('#tag_text_'+elem_i)
                .css('background-color', bg_color)
                .css('color', text_color);
            
            if ($('#jquery_tag_color_'+elem_i).val()!=bg_color+"_"+text_color){
                $('#jquery_tag_color_'+elem_i).prop('disabled',false);
            }
            $('#jquery_tag_color_'+elem_i).val(bg_color+"_"+text_color);
            
            $('#tag-modal').modal('hide');
        });
        
        // Tag POPUP color Picker
        var tagColorPicker = $('#tag-modal .colorpickerplus-embed .colorpickerplus-container');
        tagColorPicker.colorpickerembed();
        tagColorPicker.on('changeColor', function(e, color){
        var paintTarget = $(e.target).parent().prop('id') == "pick_bg" ? 'background-color' : 'color';
        if(color==null)
        $('#tag-modal #jquery_popup_tag_input').css(paintTarget, '#fff');//tranparent
        else
        $('#tag-modal #jquery_popup_tag_input').css(paintTarget, color);
        });

    });		
    </script>
@append