<div class="row">
	<div class="col-md-3">
	<div class="form-group">
		{!! CollectiveForm::label('name', trans('ticketit::admin.category-create-name') . trans('ticketit::admin.colon'), ['class' => 'col-lg-4 control-label']) !!}
		<div class="col-lg-8">
			{!! CollectiveForm::text('name', isset($category->name) ? $category->name : null, ['class' => 'form-control']) !!}
		</div>
	</div>
	<div class="form-group">
		{!! CollectiveForm::label('color', trans('ticketit::admin.category-create-color') . trans('ticketit::admin.colon'), ['class' => 'col-lg-4 control-label']) !!}
		<div class="col-lg-8 ">
			<!---->
			<button class="btn btn-default pull-left" id="category_color_picker" type="button"><span class="color-fill-icon dropdown-color-fill-icon" style="background-color: {{isset($category->color) ? $category->color : "#000000"}};"></span>&nbsp;<b class="caret"></b></button>
			<input type="hidden" id="category_color" name="color" value="{{isset($category->color) ? $category->color : '#000000'}}">

		</div>
	</div>
	<div class="form-group">
		<label class="control-label col-lg-4 tooltip-info" data-toggle="tooltip" data-placement="auto bottom" title="{{ trans('ticketit::admin.category-email-from-info') }}">{{ trans('ticketit::admin.category-create-email') . trans('ticketit::admin.colon') }}<span class="glyphicon glyphicon-question-sign"></span></label>
		<div class="col-lg-8">
			@if (isset($category->email))
				@php
					$email_origin = 'category';
				@endphp
			@else
				@if ($setting->grab('email.account.mailbox') != 'default' && $setting->grab('email.account.name') != 'default')
					@php
						$email_origin = 'tickets';
					@endphp
				@else
					@php
						$email_origin = 'website';
					@endphp
				@endif
			@endif
			<span id="email_scope_website" class="tooltip-info" data-toggle="tooltip" data-placement="auto bottom" title="{{ env('MAIL_FROM_NAME') . '. ' . trans('ticketit::admin.category-email-origin') . trans('ticketit::admin.colon') . trans('ticketit::admin.category-email-origin-website') }}" {!! $email_origin == 'website' ? '' : 'style="display: none"' !!}>{{ env('MAIL_FROM_ADDRESS') }} <span class="glyphicon glyphicon-question-sign"></span></span>
			
			<span id="email_scope_tickets" class="tooltip-info" data-toggle="tooltip" data-placement="auto bottom" title="{{ $setting->grab('email.account.name') . '. ' . trans('ticketit::admin.category-email-origin') . trans('ticketit::admin.colon') . trans('ticketit::admin.category-email-origin-tickets') }}" {!! $email_origin == 'tickets' ? '' : 'style="display: none"' !!}>{{ $setting->grab('email.account.mailbox') }} <span class="glyphicon glyphicon-question-sign"></span></span>
			
			<span id="email_scope_category" class="tooltip-info" data-toggle="tooltip" data-placement="auto bottom" title="{{ trans('ticketit::admin.category-email-origin') . trans('ticketit::admin.colon') . trans('ticketit::admin.category-email-origin-category') }}" {!! $email_origin == 'category' ? '' : 'style="display: none"' !!}><span class="email">{{ $category->email }}</span> <span class="glyphicon glyphicon-question-sign"></span></span>
			
			<button type="button" class="btn btn-default btn-sm" id="edit_email" data-toggle="modal" data-target="#email-edit-modal">{{ trans('ticketit::admin.btn-edit') }}</button>
		</div>
	</div>
	<div class="form-group">
		<label class="control-label col-lg-4 tooltip-info" data-toggle="tooltip" data-placement="auto bottom" title="{{ trans('ticketit::admin.category-create-new-tickets-help') }}">{{ trans('ticketit::admin.category-create-new-tickets') . trans('ticketit::admin.colon') }}<span class="glyphicon glyphicon-question-sign"></span></label>
		<div class="col-lg-8">
			<select name="create_level" class="generate_default_select2" style="display: none; width: 100%">
				<?php $levels = [1, 2, 3];
				$current_lv = isset($category) ? $category->create_level : 1; ?>
				@foreach ($levels as $lv)
					<option value="{{ $lv }}" {{ $lv == $current_lv ? 'selected="selected"' : '' }}>{{ trans('ticketit::admin.level-'.$lv) }}</option>
				@endforeach
			</select>
		</div>
	</div>
	</div>
	<div class="col-md-9">
	<div class="form-group">
		<label class="control-label col-sm-2 tooltip-info" data-toggle="tooltip" data-placement="auto bottom" title="{{ trans('ticketit::admin.category-edit-closing-reasons-help') }}">{{ trans('ticketit::admin.category-edit-closing-reasons') . trans('ticketit::admin.colon') }}<span class="glyphicon glyphicon-question-sign"></span></label>
		<div class="col-sm-10">
			<p><button type="button" class="btn btn-default" id="button_new_reason" data-toggle="modal" data-target="#reason-edit-modal" data-i="new">{{ trans('ticketit::admin.btn-create') }}</button></p>
			
			<div id="reason_list" class="grouped_check_list deletion_list">
			@if (isset($category) and $category->has('closingReasons'))
			@foreach ($category->closingReasons as $i=>$reason)					
				<div style="margin-bottom: 10px">
					<div class="btn-group check_parent unchecked">
					<a href="#" role="button" id="reason_{{$i}}" class="btn btn-default check_info" aria-label="{{ trans('ticketit::admin.category-delete-reason') }}" title="{{ trans('ticketit::admin.btn-edit') }}" data-toggle="modal" data-target="#reason-edit-modal" data-text="{{ $reason->text }}" data-reason_status_id="{{ $reason->status_id }}" data-i="{{$i}}"><span class="reason_text">{{ $reason->text }}</span> <span class="glyphicon glyphicon-arrow-right" style="color: #bbb"></span> <span class="reason_status">{{ $reason->status->name }}</span></a>
					
					<a href="#" role="button" id="jquery_reason_{{$i}}" class="btn btn-default check_button" data-delete_id="jquery_delete_reason_{{$i}}" title="{{ trans('ticketit::admin.category-delete-reason') }}" aria-label="{{ trans('ticketit::admin.category-delete-reason') }}"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span><span class="glyphicon glyphicon-ok" aria-hidden="true" style="display: none"></span></a>			
					</div>
					
					<input type="hidden" id="jquery_delete_reason_{{$i}}" name="jquery_delete_reason_{{$i}}" value="{{$reason->id}}" disabled="disabled">
					<input type="hidden" id="jquery_reason_ordering_{{$i}}" name="reason_ordering[]" value="{{$i}}">
					<input type="hidden" id="jquery_reason_id_{{$i}}" name="jquery_reason_id_{{$i}}" value="{{$reason->id}}">
					<input type="hidden" id="jquery_reason_text_{{$i}}" name="jquery_reason_text_{{$i}}" value="{{$reason->text}}" disabled="disabled">
					<input type="hidden" id="jquery_reason_status_id_{{$i}}" name="jquery_reason_status_id_{{$i}}" value="{{$reason->status_id}}" disabled="disabled">
				</div>				
			@endforeach
				<input type="hidden" id="reasons_count" name="reasons_count" value="<?=isset($i)?$i+1:0;?>">
			@endif
			</div>
			
			<div id="reason_template" style="display: none">				
				<div style="margin-bottom: 10px">
					<div class="btn-group check_parent unchecked">
					<?php $i=1;?>
					<a href="#" role="button" id="reason_tempnum" class="btn btn-default check_info" aria-label="{{ trans('ticketit::admin.category-delete-reason') }}" title="{{ trans('ticketit::admin.btn-edit') }}" data-toggle="modal" data-target="#reason-edit-modal" data-text="button text" data-i="i"><span class="reason_text">reason text</span> <span class="glyphicon glyphicon-arrow-right" style="color: #bbb"></span> <span class="reason_status">reason status name</span></a>
					
					<a href="#" role="button" id="jquery_reason_{{$i}}" class="btn btn-default check_button" data-delete_id="jquery_delete_reason_{{$i}}" title="{{ trans('ticketit::admin.category-delete-reason') }}" aria-label="{{ trans('ticketit::admin.category-delete-reason') }}"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span><span class="glyphicon glyphicon-ok" aria-hidden="true" style="display: none"></span></a>			
					</div>
					
					<input type="hidden" id="jquery_delete_reason_tempnum" name="jquery_delete_reason_tempnum" value="tempnum" disabled="disabled">
					<input type="hidden" id="jquery_reason_ordering_tempnum" name="reason_ordering[]" value="tempnum" disabled="disabled">
					<input type="hidden" id="jquery_reason_id_tempnum" name="jquery_reason_id_tempnum" value="new" disabled="disabled">
					<input type="hidden" id="jquery_reason_text_tempnum" name="jquery_reason_text_tempnum" value="reason text" disabled="disabled">
					<input type="hidden" id="jquery_reason_status_id_tempnum" name="jquery_reason_status_id_tempnum" value="status_id" disabled="disabled">
				</div>				
			</div>
			
		</div>
	</div>
	
	<div class="form-group">
		<label class="control-label col-sm-2" for="admin-select2-tags">{{ trans('ticketit::admin.category-edit-new-tags') . trans('ticketit::admin.colon') }}</label>
		<div class="col-sm-10">
		<select id="admin-select2-tags" class="select2-multiple" name="new_tags[]" multiple="multiple" style="display: none; width: 100%"></select></div>
		
	</div>
	@if (isset($category) and $category->has('tags'))
	<div class="form-group">
		<label class="control-label col-sm-2">{{ trans('ticketit::admin.category-edit-current-tags') . trans('ticketit::admin.colon') }}</label>
		<div class="col-sm-10">					
			<div id="tag-panel" class="grouped_check_list deletion_list no-border coloured-list pull-left">
				@foreach ($category->tags as $i=>$tag)
					<div class="btn-group check_parent unchecked">				
					<a href="#" role="button" id="jquery_tag_check_{{$i}}" class="btn btn-default check_button" data-delete_id="jquery_delete_tag_{{$i}}" title="Eliminar etiqueta {{$tag->name}}" aria-label="Eliminar etiqueta {{$tag->name}}"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span><span class="glyphicon glyphicon-ok" aria-hidden="true" style="display: none"></span></a>								
					<a href="#" role="button" id="tag_text_{{$i}}" class="btn btn-default btn-tag check_info" aria-label="Etiqueta {{$tag->name}}" title="Etiqueta '{{$tag->name}}' conté {{$tag->tickets_count}} tiquets relacionats" data-toggle="modal" data-target="#tag-edit-modal" data-tag_name="{{$tag->name}}" data-i="{{$i}}" style="color: {{$tag->text_color}}; background: {{$tag->bg_color}}"><span class="name">{{$tag->name}}</span> ({{$tag->tickets_count}})</a>
					
					</div>
					<input type="hidden" id="jquery_delete_tag_{{$i}}" name="jquery_delete_tag_{{$i}}" value="{{$tag->id}}" disabled="disabled">
					<input type="hidden" id="jquery_tag_id_{{$i}}" name="jquery_tag_id_{{$i}}" value="{{$tag->id}}">
					<input type="hidden" id="jquery_tag_name_{{$i}}" name="jquery_tag_name_{{$i}}" value="{{$tag->name}}" disabled="disabled">
					<input type="hidden" id="jquery_tag_color_{{$i}}" name="jquery_tag_color_{{$i}}" value="{{$tag->bg_color.'_'.$tag->text_color}}" disabled="disabled">
				@endforeach
			</div>
			<input type="hidden" name="tags_count" value="<?=isset($i)?$i+1:0;?>">

		</div>
	</div>
	@endif
	</div>
</div>
{!! link_to_route($setting->grab('admin_route').'.category.index', trans('ticketit::admin.btn-back'), null, ['class' => 'btn btn-default']) !!}
@if(isset($category))
	{!! CollectiveForm::submit(trans('ticketit::admin.btn-update'), ['class' => 'btn btn-primary']) !!}
@else
	{!! CollectiveForm::submit(trans('ticketit::admin.btn-submit'), ['class' => 'btn btn-primary']) !!}
@endif