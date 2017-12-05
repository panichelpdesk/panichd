@extends($master)
@section('page', trans('ticketit::lang.create-ticket-title'))

@section('header')
	@include('ticketit::shared.datetimepicker')
@append

@include('ticketit::shared.common')

@section('content')
    
	@if (!isset($ticket) && $setting->grab('departments_notices_feature') && $a_notices->count() > 0)	
		@include('ticketit::tickets.partials.notices')
	@endif
	
	
	<div class="well bs-component">
        @if (isset($ticket))
			{!! CollectiveForm::model($ticket, [
				 'route' => [$setting->grab('main_route').'.update', $ticket->id],
				 'method' => 'PATCH',
				 'class' => 'form-horizontal',
				 'enctype' => 'multipart/form-data'
			 ]) !!}
		@else
			{!! CollectiveForm::open([
				'route'=>$setting->grab('main_route').'.store',
				'method' => 'POST',
				'class' => 'form-horizontal',
				'enctype' => 'multipart/form-data'
			]) !!}
		@endif		
		
            <legend>{!! isset($ticket) ? trans('ticketit::lang.edit-ticket') . ' #'.$ticket->id : trans('ticketit::lang.create-new-ticket') !!}</legend>
            
			@if ($u->currentLevel() > 1)
				<div class="jquery_level2_class row" data-class="row"><div class="jquery_level2_class col-md-4" data-class="col-md-4"><!--</div></div>-->
			@endif
			
			<div class="form-group"><!-- SUBJECT -->
                {!! CollectiveForm::label('subject', '*' . trans('ticketit::lang.subject') . trans('ticketit::lang.colon'), [
					'class' => ($u->currentLevel()==1 ? 'col-lg-2' : 'col-lg-3').' control-label level_class',
					'data-level-1-class' => 'col-lg-2',
					'data-level-2-class' => 'col-lg-3'
				]) !!}
                <div class="{{ $u->currentLevel()==1 ? 'col-lg-10' : 'col-lg-9' }} level_class" data-level-1-class="col-lg-10" data-level-2-class="col-lg-9">
                    {!! CollectiveForm::text('subject', isset($ticket) ? $ticket->subject : null , ['class' => 'form-control', 'required' => 'required', 'placeholder' => trans('ticketit::lang.create-ticket-brief-issue')]) !!}                    
                </div>
            </div>
			
			<div class="form-group"><!-- OWNER -->
                
				<label for="owner_id" class="{{ $u->currentLevel()==1 ? 'col-lg-2' : 'col-lg-3' }} level_class control-label tooltip-info" data-level-1-class="col-lg-2" data-level-2-class="col-lg-3" title="{{ trans('ticketit::lang.create-ticket-owner-help') }}"> *{{trans('ticketit::lang.owner')}}{{trans('ticketit::lang.colon')}} <span class="glyphicon glyphicon-question-sign" style="color: #bbb"></span></label>

                <div class="{{ $u->currentLevel()==1 ? 'col-lg-10' : 'col-lg-9' }} level_class" data-level-1-class="col-lg-10" data-level-2-class="col-lg-9">
                    <select name="owner_id" class="generate_default_select2 form-control" style="display: none; width: 100%">
					@foreach ($a_owners as $owner)
						<option value="{{ $owner->id }}" {{ $owner->id == $ticket_owner_id ? 'selected="selected"' : '' }}>{{ $owner->name . (strpos($owner->email, '@tordera.cat') === false ? '' : ' - ' . $owner->email) }}
						@if ($setting->grab('departments_notices_feature'))
							@if ($owner->ticketit_department == '0')
								{{ ' - ' . trans('ticketit::lang.create-ticket-notices') . ' ' . trans('ticketit::lang.all-depts')}}
							@elseif ($owner->ticketit_department != "")						
								{{ ' - ' . trans('ticketit::lang.create-ticket-notices') . ' ' . $owner->userDepartment->resume() }}
							@endif
						@endif						
						</option>
					@endforeach
					</select>            
                </div>
            </div>
			
			@if ($u->currentLevel() > 1)
			<div class="jquery_level2_show">
				<div class="form-group" style="margin-bottom: 3em"><!-- TICKET LIST -->
					{!! CollectiveForm::label('status_id', trans('ticketit::lang.list') . trans('ticketit::lang.colon'), [
						'class' => 'col-lg-3 control-label'
					]) !!}
					<div class="col-lg-9">
						<button type="button" id="complete_no" class="btn btn-default text-warning" data-value="no" data-click-status="{{$setting->grab('default_close_status_id')}}" title="{{ trans('ticketit::lang.create-ticket-change-list') }}" {!! $a_current['complete'] == "yes" ? 'style="display:none"' : ''!!}><span class="glyphicon glyphicon-file"></span> {{ trans('ticketit::lang.active-tickets-adjective') }}</button>
						<button type="button" id="complete_yes" class="btn btn-default text-success" data-value="yes" data-click-status="{{$setting->grab('default_reopen_status_id')}}" title="{{ trans('ticketit::lang.create-ticket-change-list') }}" {!! $a_current['complete'] == "yes" ? '' : 'style="display:none"'!!}><span class="glyphicon glyphicon-ok-circle"></span> {{ trans('ticketit::lang.complete-tickets-adjective') }}</button>					
					</div>
					{!! CollectiveForm::hidden('complete', 'no',['id' => 'value_complete']) !!}
				</div>			
			
				<div class="form-group"><!-- STATUS -->
					{!! CollectiveForm::label('status_id', trans('ticketit::lang.status') . trans('ticketit::lang.colon'), [
						'class' => 'col-lg-3 control-label'
					]) !!}
					<div class="col-lg-9">
						{!! CollectiveForm::select('status_id', $status_lists, $a_current['status_id'], ['id' => 'select_status', 'class' => 'form-control']) !!}
					</div>
				</div>
				<div class="form-group"><!-- PRIORITY -->
					{!! CollectiveForm::label('priority', trans('ticketit::lang.priority') . trans('ticketit::lang.colon'), ['class' => 'col-lg-3 control-label']) !!}
					<div class="col-lg-9">
						{!! CollectiveForm::select('priority_id', $priorities, null, ['class' => 'form-control', 'required' => 'required']) !!}
					</div>
				</div>
				
				<div class="form-group">
					{!! CollectiveForm::label('start_date', trans('ticketit::lang.start-date') . trans('ticketit::lang.colon'), ['class' => 'col-lg-3 control-label']) !!}
					<div class="col-lg-9">
						<div class="input-group date" id="start_date">
							<input type="text" class="form-control" name="start_date" value="{{ $a_current['start_date'] }}"/>
							<span class="input-group-addon">
								<span class="glyphicon glyphicon-calendar"></span>
							</span>
						</div>
					</div>
				</div>
				<div class="form-group" style="margin-bottom: 3em">
					{!! CollectiveForm::label('limit_date', trans('ticketit::lang.limit-date') . trans('ticketit::lang.colon'), ['class' => 'col-lg-3 control-label']) !!}
					<div class="col-lg-9">
						<div class="input-group date" id="limit_date">
							<input type="text" class="form-control" name="limit_date"  value="{{ $a_current['limit_date'] }}"/>
							<span class="input-group-addon">
								<span class="glyphicon glyphicon-calendar"></span>
							</span>
						</div>
					</div>
				</div>
			</div>
			@endif
			
			<div class="form-group"><!-- CATEGORY -->
				{!! CollectiveForm::label('category_id', '*' . trans('ticketit::lang.category') . trans('ticketit::lang.colon'), [
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
					{!! CollectiveForm::label('agent_id', trans('ticketit::lang.agent') . trans('ticketit::lang.colon'), [
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
			
				<div class="form-group"><!-- TAGS -->
					<label class="control-label col-lg-3">Etiquetes:</label>
					<div id="jquery_select2_container" class="col-lg-9">
					<?php //$a_tags_selected = (old('category_id') and old('category_'.old('category_id').'_tags')) ? old('category_'.old('category_id').'_tags') : [] ?>
					@include('ticketit::tickets.partials.tags_menu')				
					</div>					
				</div>
			</div>
			@else
				{!! CollectiveForm::hidden('agent_id', 'auto') !!}
			@endif
			
			@if ($u->currentLevel() > 1)
				</div><div class="jquery_level2_class col-md-8" data-class="col-md-8">
			@endif
			
            <div class="form-group"><!-- DESCRIPTION -->
                <label for="content" class="col-lg-2 control-label tooltip-info" title="{{ trans('ticketit::lang.create-ticket-describe-issue') }}"> *{{trans('ticketit::lang.description')}}{{trans('ticketit::lang.colon')}} <span class="glyphicon glyphicon-question-sign" style="color: #bbb"></span></label>
                <div class="col-lg-10 summernote-text-wrapper">
                <textarea class="form-control summernote-editor" style="display: none" rows="5" name="content" cols="50">{!! $a_current['description'] !!}</textarea>                   
                </div>
            </div>
			
			@if ($u->currentLevel() > 1)
				<div class="jquery_level2_show">
					<div class="form-group"><!-- INTERVENTION -->
						<label for="intervention" class="col-lg-2 control-label tooltip-info" title="Accions realitzades per a la resolució del tiquet">Actuació{{trans('ticketit::lang.colon')}} <span class="glyphicon glyphicon-question-sign" style="color: #bbb"></span></label>			
						<div class="col-lg-10 summernote-text-wrapper">
						<textarea class="form-control summernote-editor" style="display: none" rows="5" name="intervention" cols="50">{!! $a_current['intervention'] !!}</textarea>			
						</div>
					</div>
				</div>
			@endif
			
			@if ($setting->grab('ticket_attachments_feature'))
				<div class="jquery_level2_show">
					<div class="form-group">
						{!! CollectiveForm::label('attachments', trans('ticketit::lang.attachments') . trans('ticketit::lang.colon'), [
							'class' => 'col-lg-2 control-label'
						]) !!}
						<div class="col-lg-10">							
							@include('ticketit::shared.attach_files_button', ['attach_id' => 'ticket_attached'])
							@include('ticketit::shared.attach_files_script')							
							<div id="ticket_attached" class="panel-group grouped_check_list deletion_list attached_list" data-new-attachment-modal-id="modal-attachment-edit">
							@if (isset($ticket))									
								@foreach($ticket->attachments as $attachment)
									@include('ticketit::tickets.partials.attachment', ['template'=>'createedit'])
								@endforeach															
							@endif
							</div>							
						</div>
					</div>
				</div>			
			@endif
			
			@if ($u->currentLevel() > 1)
				</div></div>
			@endif
            
        	
			
            <div class="form-group"><!-- SUBMIT BUTTON -->
                <div class="col-lg-10 col-lg-offset-2">
                    {!! CollectiveForm::submit(trans('ticketit::lang.btn-submit'), ['class' => 'btn btn-primary']) !!}
                </div>
            </div>
        {!! CollectiveForm::close() !!}
    </div>
@endsection

@include('ticketit::tickets.partials.modal_attachment_edit')
@include('ticketit::shared.photoswipe_files')
@include('ticketit::shared.jcrop_files')

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
					title: '{{ $attachment->new_filename  . ($attachment->description == "" ? '' : trans('ticketit::lang.colon').$attachment->description) }}'							
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
		
		$('#start_date').datetimepicker({
			locale: '{{App::getLocale()}}',
			format: 'YYYY-MM-DD HH:mm'
		});
		$('#limit_date').datetimepicker({			
			locale: '{{App::getLocale()}}',
			format: 'YYYY-MM-DD HH:mm',
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
	@include('ticketit::tickets.partials.summernote')
	@include('ticketit::tickets.partials.tags_footer_script')
@append