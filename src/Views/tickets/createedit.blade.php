@extends($master)
@section('page', trans('panichd::lang.create-ticket-title'))

@include('panichd::shared.common')

@section('content')

	@if (!isset($ticket) && $u->currentLevel() == 1 && $setting->grab('departments_notices_feature') && $n_notices > 0)
		<div class="row">
		<div class="col-xl-5 order-xl-2">
			@include('panichd::notices.widget')
		</div>
		<div class="col-xl-7 order-xl-1">
	@endif

	<div class="card bg-light"><div class="card-body">
        @if (isset($ticket))
			{!! CollectiveForm::model($ticket, [
				 'route' => [$setting->grab('main_route').'.update', $ticket->id],
				 'method' => 'PATCH',
				 'id' => 'ticket_form',
				 'enctype' => 'multipart/form-data'
			 ]) !!}
		@else
			{!! CollectiveForm::open([
				'route'=>$setting->grab('main_route').'.store',
				'method' => 'POST',
				'id' => 'ticket_form',
				'enctype' => 'multipart/form-data'
			]) !!}
		@endif

            <legend>{!! isset($ticket) ? trans('panichd::lang.edit-ticket') . ' #'.$ticket->id : trans('panichd::lang.create-new-ticket') !!}</legend>

			@if ($u->currentLevel() > 1)
				<div class="jquery_level2_class row" data-class="row"><div class="jquery_level2_class col-md-4" data-class="col-md-4"><!--</div></div>-->
			@endif

			<div class="form-group row"><!-- SUBJECT -->
                {!! CollectiveForm::label('subject', '*' . trans('panichd::lang.subject') . trans('panichd::lang.colon'), [
					'class' => ($u->currentLevel()==1 ? 'col-lg-2' : 'col-lg-3').' col-form-label level_class',
					'data-level-1-class' => 'col-lg-2',
					'data-level-2-class' => 'col-lg-3'
				]) !!}
                <div class="{{ $u->currentLevel()==1 ? 'col-lg-10' : 'col-lg-9' }} level_class" data-level-1-class="col-lg-10" data-level-2-class="col-lg-9">
                    {!! CollectiveForm::text('subject', isset($ticket) ? $ticket->subject : null , ['class' => 'form-control', 'required' => 'required', 'placeholder' => trans('panichd::lang.create-ticket-brief-issue')]) !!}
					<div class="jquery_error_text"></div>
                </div>
            </div>

			<div class="form-group row" style="margin-bottom: 1.5em"><!-- OWNER -->

				<label for="owner_id" class="{{ $u->currentLevel()==1 ? 'col-lg-2' : 'col-lg-3' }} level_class col-form-label tooltip-info" data-level-1-class="col-lg-2" data-level-2-class="col-lg-3" title="{{ trans('panichd::lang.create-ticket-owner-help') }}"> *{{trans('panichd::lang.owner')}}{{trans('panichd::lang.colon')}} <span class="fa fa-question-circle" style="color: #bbb"></span></label>

                <div class="{{ $u->currentLevel()==1 ? 'col-lg-10' : 'col-lg-9' }} level_class" data-level-1-class="col-lg-10" data-level-2-class="col-lg-9">
                    <select name="owner_id" class="generate_default_select2 form-control" style="display: none; width: 100%">
					@foreach ($a_owners as $owner)
						<option value="{{ $owner->id }}" {{ $owner->id == $a_current['owner_id'] ? 'selected="selected"' : '' }}>{{ $owner->name . ($owner->email == "" ? ' ' . trans('panichd::lang.ticket-owner-no-email') : ' - ' . $owner->email) }}
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
				<div class="form-group row align-items-center">
					<label class="col-lg-3 col-form-label tooltip-info" title="{{ trans('panichd::lang.create-ticket-visible-help') }}">{{ trans('panichd::lang.create-ticket-visible') . trans('panichd::lang.colon') }} <span class="fa fa-question-circle" style="color: #bbb"></span></label>

					<div class="col-lg-9">
						<div class="form-check form-check-inline">
							<label class="form-check-label">
								<input type="radio" name="hidden" value="false" {{ (!isset($ticket) || (isset($ticket) && !$ticket->hidden)) ? 'checked' : '' }}> {{ trans('panichd::lang.yes') }}
							</label>
						</div><div class="form-check form-check-inline">
							<label class="form-check-label">
								<input type="radio" name="hidden" value="true" {{ (isset($ticket) && $ticket->hidden) ? 'checked' : ''}}> {{ trans('panichd::lang.no') }}
							</label>
						</div>
					</div>

				</div>

				<div class="form-group row align-items-center" style="margin-bottom: 1.5em"><!-- TICKET LIST -->
					{!! CollectiveForm::label('complete', trans('panichd::lang.list') . trans('panichd::lang.colon'), [
						'class' => 'col-lg-3 col-form-label',
						'title' => trans('panichd::lang.create-ticket-change-list'),
						'id' => 'last_list',
						'data-last_list_default_status_id' => $a_current['status_id']
					]) !!}
					<div class="col-lg-9">
                        <?php
							if ($setting->grab('use_default_status_id')){
							    $a_lists['newest'] = [
                                    'complete' => 'no',
                                    'default_status_id' => $setting->grab('default_status_id')
                                ];
							}

							$a_lists['active'] = [
								'complete' => 'no',
								'default_status_id' => $setting->grab('default_reopen_status_id')
							];
							$a_lists['complete'] = [
								'complete' => 'yes',
								'default_status_id' => $setting->grab('default_close_status_id')
							];

							if ($a_current['complete'] == "yes"){
								$checked_list = 'complete';
							}elseif($a_current['status_id'] == $setting->grab('default_status_id')){
								if ($setting->grab('use_default_status_id')){
                                    $checked_list = 'newest';
								}else{
                                    $checked_list = 'active';
								}
							}else{
								$checked_list = 'active';
							}
                        ?>

						@foreach ($a_lists as $list => $a_list)
							<div class="form-check form-check-inline">
								<label class="form-check-label">
									<input type="radio" id="radio_{{ $list }}_list" class="jquery_ticket_list form-check-input" name="complete" value="{{ $a_list['complete'] }}" @if($list == $checked_list) {!! 'checked="checked"' !!}@endif data-list="{{ $list }}" data-default_status_id="{{ $a_list['default_status_id'] }}">{{ trans('panichd::lang.' . $list . '-tickets-adjective') }}
								</label>
							</div>
						@endforeach

					</div>
				</div>

				<div class="form-group row"><!-- STATUS -->
					{!! CollectiveForm::label('status_id', trans('panichd::lang.status') . trans('panichd::lang.colon'), [
						'class' => 'col-lg-3 col-form-label'
					]) !!}
					<div class="col-lg-9">
						{!! CollectiveForm::select('status_id', $status_lists, $a_current['status_id'], ['id' => 'select_status', 'class' => 'form-control']) !!}
					</div>
				</div>
				<div class="form-group row"><!-- PRIORITY -->
					{!! CollectiveForm::label('priority', trans('panichd::lang.priority') . trans('panichd::lang.colon'), ['class' => 'col-lg-3 col-form-label']) !!}
					<div class="col-lg-9">
						{!! CollectiveForm::select('priority_id', $priorities, $a_current['priority_id'], ['class' => 'form-control', 'required' => 'required']) !!}
					</div>
				</div>

				<div class="form-group row">
					{!! CollectiveForm::label('start_date', trans('panichd::lang.start-date') . trans('panichd::lang.colon'), ['class' => 'col-lg-3 col-form-label']) !!}
					<div class="col-lg-9">
						<div class="input-group date" id="start_date">
							<input type="text" class="form-control" name="start_date" value="{{ $a_current['start_date'] }}"/>
							<span class="input-group-addon" style="display: none"></span>
							<span class="input-group-append">
								<button class="btn btn-light btn-default"><span class="fa fa-calendar"></span></button>
							</span>
						</div>
						<div class="jquery_error_text"></div>
					</div>
				</div>
				<div class="form-group row" style="margin-bottom: 1.5em">
					{!! CollectiveForm::label('limit_date', trans('panichd::lang.limit-date') . trans('panichd::lang.colon'), ['class' => 'col-lg-3 col-form-label']) !!}
					<div class="col-lg-9">
						<div class="input-group date" id="limit_date">
							<input type="text" class="form-control" name="limit_date"  value="{{ $a_current['limit_date'] }}"/>
							<span class="input-group-addon" style="display: none"></span>
							<span class="input-group-append">
								<button class="btn btn-light btn-default"><span class="fa fa-calendar"></span></button>
							</span>
						</div>
						<div class="jquery_error_text"></div>
					</div>
				</div>
			</div>
			@endif

			<div class="form-group row"><!-- CATEGORY -->
				{!! CollectiveForm::label('category_id', '*' . trans('panichd::lang.category') . trans('panichd::lang.colon'), [
					'class' => ($u->currentLevel()==1 ? 'col-lg-2' : 'col-lg-3').' col-form-label  level_class',
					'data-level-1-class' => 'col-lg-2',
					'data-level-2-class' => 'col-lg-3'
				]) !!}
				<div class="{{ $u->currentLevel()==1 ? 'col-lg-10' : 'col-lg-9' }} level_class" data-level-1-class="col-lg-10" data-level-2-class="col-lg-9">
					{!! CollectiveForm::select('category_id', $categories, $a_current['cat_id'], ['id'=>($u->currentLevel() > 1 ? 'category_change' : 'category_id'), 'class' => 'form-control', 'required' => 'required']) !!}
				</div>
			</div>

			@if ($u->currentLevel() > 1)
			<div class="jquery_level2_show">
				<div class="form-group row"><!-- AGENT -->
					{!! CollectiveForm::label('agent_id', trans('panichd::lang.agent') . trans('panichd::lang.colon'), [
						'class' => 'col-lg-3 col-form-label'
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
					<div class="form-group row"><!-- TAGS -->
						<label class="col-form-label col-lg-3">{{ trans('panichd::lang.tags') . trans('panichd::lang.colon') }}</label>
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

            <div class="form-group row"><!-- DESCRIPTION -->
                <label for="content" class="col-lg-2 col-form-label tooltip-info" title="{{ trans('panichd::lang.create-ticket-describe-issue') }}"> *{{trans('panichd::lang.description')}}{{trans('panichd::lang.colon')}} <span class="fa fa-question-circle" style="color: #bbb"></span></label>
                <div class="col-lg-10 summernote-text-wrapper">
                <textarea class="form-control summernote-editor" style="display: none" rows="5" name="content" cols="50">{!! $a_current['description'] !!}</textarea>
				<div class="jquery_error_text"></div>
                </div>
            </div>

			@if ($u->currentLevel() > 1)
				<div class="jquery_level2_show">
					<div class="form-group row"><!-- INTERVENTION -->
						<label for="intervention" class="col-lg-2 col-form-label tooltip-info" title="{{ trans('panichd::lang.create-ticket-intervention-help') }}">{{ trans('panichd::lang.intervention') . trans('panichd::lang.colon') }} <span class="fa fa-question-circle" style="color: #bbb"></span></label>
						<div class="col-lg-10 summernote-text-wrapper">
						<textarea class="form-control summernote-editor" style="display: none" rows="5" name="intervention" cols="50">{!! $a_current['intervention'] !!}</textarea>
						</div>
					</div>
				</div>
			@endif

			@if ($setting->grab('ticket_attachments_feature'))
					<div class="form-group row">
						{!! CollectiveForm::label('attachments', trans('panichd::lang.attachments') . trans('panichd::lang.colon'), [
							'class' => 'col-lg-2 col-form-label'
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



            <div class="text-center"><!-- SUBMIT BUTTON -->
                {!! CollectiveForm::submit(isset($ticket) ? trans('panichd::lang.update') : trans('panichd::lang.btn-add'), [
						'class' => 'btn btn-primary ajax_form_submit',
						'data-errors_div' => 'form_errors'
					]) !!}
            </div>
        {!! CollectiveForm::close() !!}
    </div></div>

	@if (!isset($ticket) && $u->currentLevel() == 1 && $setting->grab('departments_notices_feature') && $n_notices > 0)
		</div>
		</div>
	@endif
@endsection

@include('panichd::tickets.partials.modal_attachment_edit')
@include('panichd::shared.photoswipe_files')
@include('panichd::shared.datetimepicker')
@include('panichd::shared.jcrop_files')
@include('panichd::tickets.partials.summernote')

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
        // Change in List affects current status
	    $('.jquery_ticket_list').click(function(){
            var new_status = "";

            if ($(this).data('default_status_id') != ""){
                if ($(this).data('list') == 'newest'){
					new_status = $(this).data('default_status_id');
                }else if($('#last_list').data('last_list_default_status_id') == $('#select_status').val()){
                    new_status = $(this).data('default_status_id');
                }

                if (new_status != ""){
                    $('#select_status').val(new_status).effect('highlight');
				}

                $('#last_list').data('last_list_default_status_id', $(this).data('default_status_id'));
			}
        });

	    @if($setting->grab('use_default_status_id'))
			// Change in status affects the List only changing from or to default_status_id
			$('#select_status').change(function(){
				if ($(this).val() == '{{ $setting->grab('default_status_id') }}'){
					if (!$('#radio_newest_list').is(':checked')) $('#radio_newest_list').prop('checked', true).parent().effect('highlight');
				}else{
					if ($('#radio_newest_list').is(':checked')) $('#radio_active_list').prop('checked', true).parent().effect('highlight');
				}
			});
		@endif

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

		$('#start_date .btn, #limit_date .btn').click(function(e){
            e.preventDefault();
		    $('#' + $(this).closest('.input-group').prop('id')).data("DateTimePicker").toggle();
		});

        $("#start_date").on("dp.change", function (e) {
            $('#limit_date').data("DateTimePicker").minDate(e.date);
        });
        $("#limit_date").on("dp.change", function (e) {
            $('#start_date').data("DateTimePicker").maxDate(e.date);
        });
	});
	</script>
	@include('panichd::tickets.partials.tags_footer_script')
@append
