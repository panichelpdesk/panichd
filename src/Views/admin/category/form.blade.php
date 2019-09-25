<div class="row">
	<div class="col-md-3">
	<div class="form-group row">
		{!! CollectiveForm::label('name', trans('panichd::admin.category-create-name') . trans('panichd::admin.colon'), ['class' => 'col-lg-4 col-form-label']) !!}
		<div class="col-lg-8">
			{!! CollectiveForm::text('name', isset($category) ? $category->name : null, ['class' => 'form-control']) !!}
		</div>
	</div>
	<div class="form-group row">
		{!! CollectiveForm::label('color', trans('panichd::admin.category-create-color') . trans('panichd::admin.colon'), ['class' => 'col-lg-4 col-form-label']) !!}
		<div class="col-lg-8 ">
			@yield('colorpicker_snippet')
		</div>
	</div>
	<div class="form-group row">
		<label class="col-form-label col-lg-4 tooltip-info" data-toggle="tooltip" data-placement="bottom" title="{{ trans('panichd::admin.category-email-from-info') }}">{{ trans('panichd::admin.category-create-email') . trans('panichd::admin.colon') }}<span class="fa fa-question-circle"></span></label>
		<div class="col-lg-8">
			@if (isset($category) && isset($category->email))
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
			<span id="email_scope_website" class="tooltip-info" data-toggle="tooltip" data-placement="bottom" title="{{ config('mail.from.name') . '. ' . trans('panichd::admin.category-email-origin') . trans('panichd::admin.colon') . trans('panichd::admin.category-email-origin-website') }}" {!! $email_origin == 'website' ? '' : 'style="display: none"' !!}>{{ config('mail.from.address') }} <span class="fa fa-question-circle"></span></span>

			<span id="email_scope_tickets" class="tooltip-info" data-toggle="tooltip" data-placement="bottom" title="{{ $setting->grab('email.account.name') . '. ' . trans('panichd::admin.category-email-origin') . trans('panichd::admin.colon') . trans('panichd::admin.category-email-origin-tickets') }}" {!! $email_origin == 'tickets' ? '' : 'style="display: none"' !!}>{{ $setting->grab('email.account.mailbox') }} <span class="fa fa-question-circle"></span></span>

			<span id="email_scope_category" class="tooltip-info" data-toggle="tooltip" data-placement="bottom" title="{{ trans('panichd::admin.category-email-origin') . trans('panichd::admin.colon') . trans('panichd::admin.category-email-origin-category') }}" {!! $email_origin == 'category' ? '' : 'style="display: none"' !!}><span class="email">{{ isset($category) ? $category->email : '' }}</span> <span class="fa fa-question-circle"></span></span>

			<button type="button" class="btn btn-light btn-default btn-sm" id="edit_email" data-toggle="modal" data-target="#email-edit-modal">{{ trans('panichd::admin.btn-edit') }}</button>
		</div>
	</div>
	<div class="form-group row">
		<label class="col-form-label col-lg-4 tooltip-info" data-toggle="tooltip" data-placement="bottom" title="{{ trans('panichd::admin.category-create-new-tickets-help') }}">{{ trans('panichd::admin.category-create-new-tickets') . trans('panichd::admin.colon') }}<span class="fa fa-question-circle"></span></label>
		<div class="col-lg-8">
			<select name="create_level" class="generate_default_select2" style="display: none; width: 100%">
				<?php $levels = [1, 2, 3];
				$current_lv = isset($category) ? $category->create_level : 1; ?>
				@foreach ($levels as $lv)
					<option value="{{ $lv }}" {{ $lv == $current_lv ? 'selected="selected"' : '' }}>{{ trans('panichd::admin.level-'.$lv) }}</option>
				@endforeach
			</select>
		</div>
	</div>
	</div>
	<div class="col-md-9">
	<div class="form-group row">
		<label class="col-form-label col-sm-2 tooltip-info" data-toggle="tooltip" data-placement="bottom" title="{{ trans('panichd::admin.category-edit-closing-reasons-help') }}">{{ trans('panichd::admin.category-edit-closing-reasons') . trans('panichd::admin.colon') }}<span class="fa fa-question-circle"></span></label>
		<div class="col-sm-10">
			<p><button type="button" class="btn btn-light btn-default" id="button_new_reason" data-toggle="modal" data-target="#reason-edit-modal" data-i="new">{{ trans('panichd::admin.btn-create') }}</button></p>

			<div id="reason_list" class="grouped_check_list deletion_list">
			@if (isset($category) and $category->has('closingReasons'))
			@foreach ($category->closingReasons as $i=>$reason)
				<div style="margin-bottom: 10px">
					<div class="btn-group check_parent unchecked">
					<a href="#" role="button" id="reason_{{ $i }}" class="btn btn-light btn-default check_info" aria-label="{{ trans('panichd::admin.category-delete-reason') }}" title="{{ trans('panichd::admin.btn-edit') }}" data-toggle="modal" data-target="#reason-edit-modal" data-text="{{ $reason->text }}" data-reason_status_id="{{ $reason->status_id }}" data-i="{{ $i }}"><span class="reason_text">{{ $reason->text }}</span> <span class="fa fa-arrow-right" style="color: #bbb"></span> <span class="reason_status">{{ $reason->status->name }}</span></a>

					<a href="#" role="button" id="jquery_reason_{{ $i }}" class="btn btn-light btn-default check_button" data-delete_id="jquery_delete_reason_{{ $i }}" title="{{ trans('panichd::admin.category-delete-reason') }}" aria-label="{{ trans('panichd::admin.category-delete-reason') }}"><span class="fa fa-times" aria-hidden="true"></span><span class="fa fa-check" aria-hidden="true" style="display: none"></span></a>
					</div>

					<input type="hidden" id="jquery_delete_reason_{{ $i }}" name="jquery_delete_reason_{{ $i }}" value="{{$reason->id}}" disabled="disabled">
					<input type="hidden" id="jquery_reason_ordering_{{ $i }}" name="reason_ordering[]" value="{{ $i }}">
					<input type="hidden" id="jquery_reason_id_{{ $i }}" name="jquery_reason_id_{{ $i }}" value="{{$reason->id}}">
					<input type="hidden" id="jquery_reason_text_{{ $i }}" name="jquery_reason_text_{{ $i }}" value="{{$reason->text}}" disabled="disabled">
					<input type="hidden" id="jquery_reason_status_id_{{ $i }}" name="jquery_reason_status_id_{{ $i }}" value="{{$reason->status_id}}" disabled="disabled">
				</div>
			@endforeach
			@endif
			<input type="hidden" id="reasons_count" name="reasons_count" value="<?=isset($i)?$i+1:0;?>">
			</div>

			<div id="reason_template" style="display: none">
				<div style="margin-bottom: 10px">
					<div class="btn-group check_parent unchecked">
					<?php $i=1;?>
					<a href="#" role="button" id="reason_tempnum" class="btn btn-light btn-default check_info" aria-label="{{ trans('panichd::admin.category-delete-reason') }}" title="{{ trans('panichd::admin.btn-edit') }}" data-toggle="modal" data-target="#reason-edit-modal" data-text="button text" data-i="i"><span class="reason_text">reason text</span> <span class="fa fa-arrow-right" style="color: #bbb"></span> <span class="reason_status">reason status name</span></a>

					<a href="#" role="button" id="jquery_reason_{{ $i }}" class="btn btn-light btn-default check_button" data-delete_id="jquery_delete_reason_{{ $i }}" title="{{ trans('panichd::admin.category-delete-reason') }}" aria-label="{{ trans('panichd::admin.category-delete-reason') }}"><span class="fa fa-times" aria-hidden="true"></span><span class="fa fa-check" aria-hidden="true" style="display: none"></span></a>
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

	<div class="form-group row mb-4">
		<label class="col-form-label col-sm-2" for="admin-select2-tags">{{ trans('panichd::admin.category-edit-new-tags') . trans('panichd::admin.colon') }}</label>
		<div class="col-sm-10">
			<button role="button" id="btn_tag_create" class="btn btn-default" data-toggle="modal" data-target="#tag-modal" title="{{ trans('panichd::admin.category-edit-new-tag-title') }}">{{ trans('panichd::admin.btn-create') }}</button>
			<div id="new_tags_container" class="grouped_check_list deletion_list no-border coloured-list" style="display: inline-block">

			</div>
		</div>

	</div>
	@if (isset($category) and $category->has('tags'))
	<div class="form-group row">
		<label class="col-form-label col-sm-2">{{ trans('panichd::admin.category-edit-current-tags') . trans('panichd::admin.colon') }}</label>
		<div class="col-sm-10">
			<div id="tag-panel" class="grouped_check_list deletion_list no-border coloured-list">
				@foreach ($category->tags as $i=>$tag)
					<div class="btn-group check_parent unchecked">
						<a href="#" role="button" id="jquery_tag_check_{{ $i }}" class="btn btn-light check_button" data-delete_id="jquery_delete_tag_{{ $i }}" title="Delete tag" aria-label="Delete tag"><span class="fa fa-times" aria-hidden="true"></span><span class="fa fa-check" aria-hidden="true" style="display: none"></span></a>
						<a href="#" role="button" id="tag_text_{{ $i }}" class="btn btn-light btn-tag check_info" aria-label="{{ $tag->name }}" title="'{{$tag->name}}' tag contains {{$tag->tickets_count}} related tickets" data-toggle="modal" data-target="#tag-modal" data-tag_name="{{$tag->name}}" data-i="{{ $i }}" style="color: {{$tag->text_color}}; background: {{$tag->bg_color}}"><span class="name">{{$tag->name}}</span> ({{$tag->tickets_count}})</a>
						<input type="hidden" id="jquery_delete_tag_{{ $i }}" name="jquery_delete_tag_{{ $i }}" value="{{$tag->id}}" disabled="disabled">
						<input type="hidden" id="jquery_tag_id_{{ $i }}" name="jquery_tag_id_{{ $i }}" value="{{$tag->id}}">
						<input type="hidden" id="jquery_tag_name_{{ $i }}" name="jquery_tag_name_{{ $i }}" value="{{$tag->name}}" disabled="disabled">
						<input type="hidden" id="jquery_tag_color_{{ $i }}" name="jquery_tag_color_{{ $i }}" value="{{$tag->bg_color.'_'.$tag->text_color}}" disabled="disabled">
					</div>
				@endforeach
			</div>
			<input type="hidden" name="tags_count" value="<?=isset($i)?$i+1:0;?>">

		</div>
	</div>
	@endif
	</div>
</div>
@if(isset($category))
	{!! CollectiveForm::submit(trans('panichd::lang.update'), ['class' => 'btn btn-primary']) !!}
@else
	{!! CollectiveForm::submit(trans('panichd::lang.btn-add'), ['class' => 'btn btn-primary']) !!}
@endif
