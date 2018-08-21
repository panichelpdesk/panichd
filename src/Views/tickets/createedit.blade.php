@extends($master)
@section('page', trans('panichd::lang.create-ticket-title'))

@include('panichd::shared.common')

@section('content')
	
	@if (!isset($ticket) && $u->currentLevel() == 1 && $setting->grab('departments_notices_feature') && $n_notices > 0)
		<div class="row">
		<div class="col-lg-6 col-lg-push-6">
			@include('panichd::notices.widget')
		</div>
		<div class="col-lg-6 col-lg-pull-6">
	@endif
	
	<div class="well bs-component">
        @if (isset($ticket))
			{!! CollectiveForm::model($ticket, [
				 'route' => [$setting->grab('main_route').'.update', $ticket->id],
				 'method' => 'PATCH',
				 'id' => 'ticket_form',
				 'class' => 'form-horizontal',
				 'enctype' => 'multipart/form-data'
			 ]) !!}
		@else
			{!! CollectiveForm::open([
				'route'=>$setting->grab('main_route').'.store',
				'method' => 'POST',
				'id' => 'ticket_form',
				'class' => 'form-horizontal',
				'enctype' => 'multipart/form-data'
			]) !!}
		@endif		
		
            <legend>{!! isset($ticket) ? trans('panichd::lang.edit-ticket') . ' #'.$ticket->id : trans('panichd::lang.create-new-ticket') !!}</legend>
            
			@if ($u->currentLevel() > 1)
				<div class="jquery_level2_class row" data-class="row"><div class="jquery_level2_class col-md-4" data-class="col-md-4"><!--</div></div>-->
			@endif
			
			<div class="form-group"><!-- SUBJECT -->
                {!! CollectiveForm::label('subject', '*' . trans('panichd::lang.subject') . trans('panichd::lang.colon'), [
					'class' => ($u->currentLevel()==1 ? 'col-lg-2' : 'col-lg-3').' control-label level_class',
					'data-level-1-class' => 'col-lg-2',
					'data-level-2-class' => 'col-lg-3'
				]) !!}
                <div class="{{ $u->currentLevel()==1 ? 'col-lg-10' : 'col-lg-9' }} level_class" data-level-1-class="col-lg-10" data-level-2-class="col-lg-9">
                    {!! CollectiveForm::text('subject', isset($ticket) ? $ticket->subject : null , ['class' => 'form-control', 'required' => 'required', 'placeholder' => trans('panichd::lang.create-ticket-brief-issue')]) !!}
					<div class="jquery_error_text"></div>
                </div>
            </div>
			
			<div class="form-group"><!-- OWNER -->
                
				<label for="owner_id" class="{{ $u->currentLevel()==1 ? 'col-lg-2' : 'col-lg-3' }} level_class control-label tooltip-info" data-level-1-class="col-lg-2" data-level-2-class="col-lg-3" title="{{ trans('panichd::lang.create-ticket-owner-help') }}"> *{{trans('panichd::lang.owner')}}{{trans('panichd::lang.colon')}} <span class="fa fa-question-circle" style="color: #bbb"></span></label>

                <div class="{{ $u->currentLevel()==1 ? 'col-lg-10' : 'col-lg-9' }} level_class" data-level-1-class="col-lg-10" data-level-2-class="col-lg-9">
                    <select name="owner_id" class="generate_default_select2 form-control" style="display: none; width: 100%">
					@foreach ($a_owners as $owner)
						<option value="{{ $owner->id }}" {{ $owner->id == $ticket_owner_id ? 'selected="selected"' : '' }}>{{ $owner->name . ($owner->email == "" ? ' ' . trans('panichd::lang.ticket-owner-no-email') : ' - ' . $owner->email) }}
						@if ($setting->grab('departments_notices_feature'))
							@if ($owner->ticketit_department == '0')
								{{ ' - ' . trans('panichd::lang.create-ticket-notices') . ' ' . trans('panichd::lang.all-depts')}}
							@elseif ($owner->ticketit_department != "")						
								{{ ' - ' . trans('panichd::lang.create-ticket-notices') . ' ' . $owner->userDepartment->getFullName() }}
							@endif
						@endif						
						</option>
					@endforeach
					</select>            
                </div>
            </div>
			
			@if ($u->currentLevel() > 1)
			<div class="jquery_level2_show">
				<div class="form-group">
					<label class="col-lg-3 control-label tooltip-info" title="{{ trans('panichd::lang.create-ticket-visible-help') }}">{{ trans('panichd::lang.create-ticket-visible') . trans('panichd::lang.colon') }} <span class="fa fa-question-circle" style="color: #bbb"></span></label>
					
					<div class="col-lg-9" style="padding-top: 7px;">
						<label><input type="radio" name="hidden" value="false" {{ (!isset($ticket) || (isset($ticket) && !$ticket->hidden)) ? 'checked' : '' }}> {{ trans('panichd::lang.yes') }}</label><label style="margin: 0em 0em 0em 1em;"><input type="radio" name="hidden" value="true" {{ (isset($ticket) && $ticket->hidden) ? 'checked' : ''}}> {{ trans('panichd::lang.no') }}</label>
					</div>
			
				</div>
				
				<div class="form-group" style="margin-bottom: 3em"><!-- TICKET LIST -->
					{!! CollectiveForm::label('status_id', trans('panichd::lang.list') . trans('panichd::lang.colon'), [
						'class' => 'col-lg-3 control-label'
					]) !!}
					<div class="col-lg-9">
						<button type="button" id="complete_no" class="btn btn-light text-warning" data-value="no" data-click-status="{{$setting->grab('default_close_status_id')}}" title="{{ trans('panichd::lang.create-ticket-change-list') }}" {!! $a_current['complete'] == "yes" ? 'style="display:none"' : ''!!}><span class="fa fa-file"></span> {{ trans('panichd::lang.active-tickets-adjective') }}</button>
						<button type="button" id="complete_yes" class="btn btn-light text-success" data-value="yes" data-click-status="{{$setting->grab('default_reopen_status_id')}}" title="{{ trans('panichd::lang.create-ticket-change-list') }}" {!! $a_current['complete'] == "yes" ? '' : 'style="display:none"'!!}><span class="fa fa-check-circle"></span> {{ trans('panichd::lang.complete-tickets-adjective') }}</button>
					</div>
					{!! CollectiveForm::hidden('complete', isset($ticket) ? ($ticket->completed_at == '' ? 'no' : 'yes') : 'no',['id' => 'value_complete']) !!}
				</div>			
			
				<div class="form-group"><!-- STATUS -->
					{!! CollectiveForm::label('status_id', trans('panichd::lang.status') . trans('panichd::lang.colon'), [
						'class' => 'col-lg-3 control-label'
					]) !!}
					<div class="col-lg-9">
						{!! CollectiveForm::select('status_id', $status_lists, $a_current['status_id'], ['id' => 'select_status', 'class' => 'form-control']) !!}
					</div>
				</div>
				<div class="form-group"><!-- PRIORITY -->
					{!! CollectiveForm::label('priority', trans('panichd::lang.priority') . trans('panichd::lang.colon'), ['class' => 'col-lg-3 control-label']) !!}
					<div class="col-lg-9">
						{!! CollectiveForm::select('priority_id', $priorities, $a_current['priority_id'], ['class' => 'form-control', 'required' => 'required']) !!}
					</div>
				</div>
				
				<div class="form-group">
					{!! CollectiveForm::label('start_date', trans('panichd::lang.start-date') . trans('panichd::lang.colon'), ['class' => 'col-lg-3 control-label']) !!}
					<div class="col-lg-9">
						<div class="input-group date" id="start_date">
							<input type="text" class="form-control" name="start_date" value="{{ $a_current['start_date'] }}"/>
							<span class="input-group-addon">
								<span class="fa fa-calendar"></span>
							</span>
						</div>
						<div class="jquery_error_text"></div>
					</div>
				</div>
				<div class="form-group" style="margin-bottom: 3em">
					{!! CollectiveForm::label('limit_date', trans('panichd::lang.limit-date') . trans('panichd::lang.colon'), ['class' => 'col-lg-3 control-label']) !!}
					<div class="col-lg-9">
						<div class="input-group date" id="limit_date">
							<input type="text" class="form-control" name="limit_date"  value="{{ $a_current['limit_date'] }}"/>
							<span class="input-group-addon">
								<span class="fa fa-calendar"></span>
							</span>
						</div>
						<div class="jquery_error_text"></div>
					</div>
				</div>
			</div>
			@endif
			
			<div class="form-group"><!-- CATEGORY -->
				{!! CollectiveForm::label('category_id', '*' . trans('panichd::lang.category') . trans('panichd::lang.colon'), [
					'class' => ($u->currentLevel()==1 ? 'col-lg-2' : 'col-lg-3').' control-label  level_class',
					'data-level-1-class' => 'col-lg-2',
					'data-level-2-class' => 'col-lg-3'
				]) !!}
				<div class="{{ $u->currentLevel()==1 ? 'col-lg-10' : 'col-lg-9' }} level_class" data-level-1-class="col-lg-10" data-level-2-class="col-lg-9">
					{!! CollectiveForm::select('category_id', $categories, $a_current['cat_id'], ['id'=>($u->currentLevel() > 1 ? 'category_change' : 'category_id'), 'class' => 'form-control', 'required' => 'required']) !!}
				</div>
			</div>
			
			@if ($u->currentLevel() > 1)				
			<div class="jquery_level2_show">
				<div class="form-group"><!-- AGENT -->
					{!! CollectiveForm::label('agent_id', trans('panichd::lang.agent') . trans('panichd::lang.colon'), [
						'class' => 'col-lg-3 control-label'
					]) !!}
					<div class="col-lg-9">
						{!! CollectiveForm::select(
							'agent_id',
							$agent_lists,
							$a_current['agent_id'],
							['class' => 'form-control']) !!}
					</div>
				</div>
			
				@if ($tag_lists->count() > 0)
					<div class="form-group"><!-- TAGS -->
						<label class="control-label col-lg-3">{{ trans('panichd::lang.tags') . trans('panichd::lang.colon') }}</label>
						<div id="jquery_select2_container" class="col-lg-9">
						@include('panichd::tickets.partials.tags_menu')				
						</div>					
					</div>
				@endif

			</div>
			@else
				{!! CollectiveForm::hidden('agent_id', 'auto') !!}
			@endif
			
			@if ($u->currentLevel() > 1)
				</div><div class="jquery_level2_class col-md-8" data-class="col-md-8">
			@endif
			
            <div class="form-group"><!-- DESCRIPTION -->
                <label for="content" class="col-lg-2 control-label tooltip-info" title="{{ trans('panichd::lang.create-ticket-describe-issue') }}"> *{{trans('panichd::lang.description')}}{{trans('panichd::lang.colon')}} <span class="fa fa-question-circle" style="color: #bbb"></span></label>
                <div class="col-lg-10 summernote-text-wrapper">
                <textarea class="form-control summernote-editor" style="display: none" rows="5" name="content" cols="50">{!! $a_current['description'] !!}</textarea>
				<div class="jquery_error_text"></div>
                </div>
            </div>
			
			@if ($u->currentLevel() > 1)
				<div class="jquery_level2_show">
					<div class="form-group"><!-- INTERVENTION -->
						<label for="intervention" class="col-lg-2 control-label tooltip-info" title="{{ trans('panichd::lang.create-ticket-intervention-help') }}">{{ trans('panichd::lang.intervention') . trans('panichd::lang.colon') }} <span class="fa fa-question-circle" style="color: #bbb"></span></label>
						<div class="col-lg-10 summernote-text-wrapper">
						<textarea class="form-control summernote-editor" style="display: none" rows="5" name="intervention" cols="50">{!! $a_current['intervention'] !!}</textarea>			
						</div>
					</div>
				</div>
			@endif
			
			@if ($setting->grab('ticket_attachments_feature'))
					<div class="form-group">
						{!! CollectiveForm::label('attachments', trans('panichd::lang.attachments') . trans('panichd::lang.colon'), [
							'class' => 'col-lg-2 control-label'
						]) !!}
						<div class="col-lg-10">
							@include('panichd::shared.attach_files_button', ['attach_id' => 'ticket_attached'])
							@include('panichd::shared.attach_files_script')							
							<div id="ticket_attached" class="panel-group grouped_check_list deletion_list attached_list" data-new-attachment-modal-id="modal-attachment-edit">
							@if (isset($ticket))
								@foreach($ticket->attachments as $attachment)
									@include('panichd::tickets.partials.attachment', ['template'=>'createedit'])
								@endforeach
							@endif
							</div>
						</div>
					</div>
			@endif
			
			@if ($u->currentLevel() > 1)
				</div></div>
			@endif
            
        	
			
            <div class="form-group"><!-- SUBMIT BUTTON -->
                <div class="col-lg-10 col-lg-offset-2">
                    {!! CollectiveForm::submit(trans('panichd::lang.btn-submit'), [
						'class' => 'btn btn-primary ajax_form_submit',
						'data-errors_div' => 'form_errors'
					]) !!}
                </div>
            </div>
        {!! CollectiveForm::close() !!}
    </div>
	
	@if (!isset($ticket) && $u->currentLevel() == 1 && $setting->grab('departments_notices_feature') && $n_notices > 0)
		</div>
		</div>
	@endif
@endsection

@include('panichd::tickets.partials.modal_attachment_edit')
@include('panichd::shared.photoswipe_files')
@include('panichd::shared.datetimepicker')
@include('panichd::shared.jcrop_files')

@section('footer')
    <script type="text/javascript">
	// PhotoSwipe items array (load before jQuery .pwsp_gallery_link click selector)
	var pswpItems = [
		@if(isset($ticket))
		@foreach($ticket->attachments()->images()->get() as $attachment)
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
						$('.jquery_level2_show').show();
						
					}else{
						$('.jquery_level2_class').each(function(elem){
							$(this).attr('class','jquery_level2_class');						
						});
						$('.jquery_level2_show').hide();
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
		
		$('#complete_no, #complete_yes').click(function(){
			$(this).hide();
			var other = $(this).attr('data-value') == 'yes' ? 'no' : 'yes';
			$('#value_complete').val(other);
			$('#complete_'+other).show();
			$('#select_status').val($(this).attr('data-click-status'));
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
        $("#start_date").on("dp.change", function (e) {
            $('#limit_date').data("DateTimePicker").minDate(e.date);
        });
        $("#limit_date").on("dp.change", function (e) {
            $('#start_date').data("DateTimePicker").maxDate(e.date);
        });
	});	
	</script>	
	@include('panichd::tickets.partials.summernote')
	@include('panichd::tickets.partials.tags_footer_script')
@append