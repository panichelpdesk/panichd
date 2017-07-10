@extends($master)
@section('page', trans('ticketit::lang.create-ticket-title'))

@section('content')
@include('ticketit::shared.header')
    <div class="well bs-component">
        {!! CollectiveForm::open([
			'route'=>$setting->grab('main_route').'.store',
			'method' => 'POST',
			'class' => 'form-horizontal'
			]) !!}
            <legend>{!! trans('ticketit::lang.create-new-ticket') !!}</legend>
            
			@if ($u->maxLevel() > 1)
				<div class="jquery_level2_class row" data-class="row"><div class="jquery_level2_class col-md-4" data-class="col-md-4"><!--</div></div>-->
			@endif
			
			<div class="form-group"><!-- SUBJECT -->
                {!! CollectiveForm::label('subject', '*' . trans('ticketit::lang.subject') . trans('ticketit::lang.colon'), [
					'class' => ($u->currentLevel()==1 ? 'col-lg-2' : 'col-lg-3').' control-label level_class',
					'data-level-1-class' => 'col-lg-2',
					'data-level-2-class' => 'col-lg-3'
				]) !!}
                <div class="{{ $u->currentLevel()==1 ? 'col-lg-10' : 'col-lg-9' }} level_class" data-level-1-class="col-lg-10" data-level-2-class="col-lg-9">
                    {!! CollectiveForm::text('subject', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => trans('ticketit::lang.create-ticket-brief-issue')]) !!}                    
                </div>
            </div>
			
			<div class="form-group"><!-- OWNER -->
                
				<label for="owner_id" class="{{ $u->currentLevel()==1 ? 'col-lg-2' : 'col-lg-3' }} level_class control-label" data-level-1-class="col-lg-2" data-level-2-class="col-lg-3" title="{{ trans('ticketit::lang.create-ticket-owner-help') }}" style="cursor: help"> *{{trans('ticketit::lang.create-ticket-owner')}}{{trans('ticketit::lang.colon')}} <span class="glyphicon glyphicon-question-sign" style="color: #bbb"></span></label>

                <div class="{{ $u->currentLevel()==1 ? 'col-lg-10' : 'col-lg-9' }} level_class" data-level-1-class="col-lg-10" data-level-2-class="col-lg-9">
                    <select name="owner_id" id="owner_select2" class="form-control" style="display: none; width: 100%">
					@foreach (App\User::orderBy('name')->get() as $owner)
						<option value="{{ $owner->id }}" {{ $owner->id == $u->id ? 'selected="selected"' : '' }}>{{ ($owner->ticketit_department ? trans('ticketit::lang.department-shortening').trans('ticketit::lang.colon'):'').$owner->name }}</option>
					@endforeach
					</select>            
                </div>
            </div>
			
			@if ($u->maxLevel() > 1)
			<div class="jquery_level2_show">
				<div class="form-group"><!-- ACTIVE / COMPLETE -->
					{!! CollectiveForm::label('status_id', 'Llistat' . trans('ticketit::lang.colon'), [
						'class' => 'col-lg-3 control-label'
					]) !!}
					<div class="col-lg-9">
						<button type="button" id="complete_no" class="btn btn-default text-warning" data-value="no" data-click-status="{{$setting->grab('default_close_status_id')}}" title="canviar llistat" {!! $a_current['complete'] == "yes" ? 'style="display:none"' : ''!!}><span class="glyphicon glyphicon-file"></span> Oberts</button>
						<button type="button" id="complete_yes" class="btn btn-default text-success" data-value="yes" data-click-status="{{$setting->grab('default_reopen_status_id')}}" title="canviar llistat" {!! $a_current['complete'] == "yes" ? '' : 'style="display:none"'!!}><span class="glyphicon glyphicon-ok-circle"></span> Completats</button>					
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
				<div class="form-group" style="margin-bottom: 3em"><!-- PRIORITY -->
					{!! CollectiveForm::label('priority', trans('ticketit::lang.priority') . trans('ticketit::lang.colon'), ['class' => 'col-lg-3 control-label']) !!}
					<div class="col-lg-9">
						{!! CollectiveForm::select('priority_id', $priorities, null, ['class' => 'form-control', 'required' => 'required']) !!}
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
					{!! CollectiveForm::select('category_id', $categories, $a_current['cat_id'], ['id'=>($u->maxLevel() > 1 ? 'category_change' : 'category_id'), 'class' => 'form-control', 'required' => 'required']) !!}
				</div>
			</div>
			
			@if ($u->maxLevel() > 1)				
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
			
			@if ($u->maxLevel() > 1)
				</div><div class="jquery_level2_class col-md-8" data-class="col-md-8">
			@endif
			
            <div class="form-group"><!-- DESCRIPTION -->
                <label for="content" class="col-lg-2 control-label" title="{{ trans('ticketit::lang.create-ticket-describe-issue') }}" style="cursor: help"> *{{trans('ticketit::lang.description')}}{{trans('ticketit::lang.colon')}} <span class="glyphicon glyphicon-question-sign" style="color: #bbb"></span></label>
                <div class="col-lg-10">
                <textarea class="form-control summernote-editor" style="display: none" rows="5" name="content" cols="50">{!! old('content_html') !!}</textarea>                   
                </div>
            </div>
			
			@if ($u->maxLevel() > 1)
			<div class="jquery_level2_show">
				<div class="form-group"><!-- INTERVENTION -->
					<label for="intervention" class="col-lg-2 control-label" title="Accions realitzades per a la resolució del tiquet" style="cursor: help">Actuació{{trans('ticketit::lang.colon')}} <span class="glyphicon glyphicon-question-sign" style="color: #bbb"></span></label>			
					<div class="col-lg-10">
					<textarea class="form-control summernote-editor" style="display: none" rows="5" name="intervention" cols="50">{!! old('intervention_html') !!}</textarea>			
					</div>
				</div>
			</div>
				
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

@section('footer')
    <script type="text/javascript">
	var category_id=<?=$a_current['cat_id'];?>;

	$(function(){		
		// User select
		$('#owner_select2').select2();
		
		// Category select with $u->maxLevel() > 1 only
		$('#category_change').change(function(){
			// Update agent list
			$('#agent_id').prop('disabled',true);
			var loadpage = "{!! route($setting->grab('main_route').'agentselectlist') !!}/" + $(this).val() + "/"+$('#agent_id').val();
			$('#agent_id').load(loadpage, function(){
				$('#agent_id').prop('disabled',false);
			});
			
			
			@if ($u->maxLevel() == 2)
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
	});	
	</script>	
	@include('ticketit::tickets.partials.summernote')
	@include('ticketit::tickets.partials.tags_footer_script')
@append