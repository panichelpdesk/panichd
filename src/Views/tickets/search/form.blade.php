<div id="search_form" class="card bg-light mb-2" @if(isset($search_fields)) style="display: none" @endif><div class="card-body">
{!! CollectiveForm::open(['route'=> $setting->grab('main_route').'.search.register', 'method' => 'POST']) !!}

    <legend>{{ trans('panichd::lang.searchform-title') }}</legend>

    <div class="row" data-class="row"><div class="col-md-6">

    <div class="form-group row @if(isset($search_fields['subject'])) bg-info @endif"><!-- SUBJECT -->
        {!! CollectiveForm::label('subject', trans('panichd::lang.subject') . trans('panichd::lang.colon'), [
            'class' => 'col-lg-3 col-form-label'
        ]) !!}
        <div class="col-lg-9">
            {!! CollectiveForm::text('subject', $search_fields['subject'] ?? null , ['class' => 'form-control', 'placeholder' => trans('panichd::lang.create-ticket-brief-issue')]) !!}
        </div>
    </div>

    <div class="form-group row @if(isset($search_fields['creator_id'])) bg-info @endif"><!-- CREATOR -->
        <label for="creator_id" class="col-lg-3 col-form-label tooltip-info" title="{{ trans('panichd::lang.searchform-help-creator') }}">{{ trans('panichd::lang.searchform-creator')  . trans('panichd::lang.colon') }} <span class="fa fa-question-circle" style="color: #bbb"></span></label>
        <div class="col-lg-9">
            <select name="creator_id" class="generate_default_select2 form-control" style="display: none; width: 100%">
                <option value="">{{ trans('panichd::lang.searchform-creator-none') }}</option>
                @foreach ($c_members as $member)
                    <option value="{{ $member->id }}" @if(isset($search_fields['creator_id']) && $search_fields['creator_id'] == $member->id) selected="selected" @endif>{{ $member->name . ($member->email == "" ? ' ' . trans('panichd::lang.ticket-owner-no-email') : ' - ' . $member->email) }}
                    @if ($setting->grab('departments_notices_feature'))
                        @if ($member->ticketit_department == '0')
                            {{ ' - ' . trans('panichd::lang.create-ticket-notices') . ' ' . trans('panichd::lang.all-depts')}}
                        @elseif ($member->ticketit_department != "")
                            {{ ' - ' . trans('panichd::lang.create-ticket-notices') . ' ' . $member->userDepartment->getFullName() }}
                        @endif
                    @endif
                    </option>
                @endforeach
            </select>
        </div>
    </div>
    
    <div style="margin-bottom: 1.5em">
        <div class="form-group row @if(isset($search_fields['user_id'])) bg-info @endif"><!-- OWNER -->
            <label for="user_id" class="col-lg-3 col-form-label tooltip-info" title="{{ trans('panichd::lang.searchform-help-owner') }}">{{trans('panichd::lang.owner') . trans('panichd::lang.colon')}} <span class="fa fa-question-circle" style="color: #bbb"></span></label>
            <div class="col-lg-9">
                <select name="user_id" class="generate_default_select2 form-control" style="display: none; width: 100%">
                    <option value="">{{ trans('panichd::lang.searchform-owner-none') }}</option>
                    @foreach ($c_members as $owner)
                        <option value="{{ $owner->id }}" @if(isset($search_fields['user_id']) && $search_fields['user_id'] == $owner->id) selected="selected" @endif>{{ $owner->name . ($owner->email == "" ? ' ' . trans('panichd::lang.ticket-owner-no-email') : ' - ' . $owner->email) }}
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

        @if($setting->grab('departments_feature'))
            <div class="form-group row @if(isset($search_fields['department_id'])) bg-info @endif"><!-- DEPARTMENT -->
                <label for="user_id" class="col-lg-3 col-form-label tooltip-info" title="{{ trans('panichd::lang.searchform-help-department') }}">{{trans('panichd::lang.searchform-department') . trans('panichd::lang.colon')}} <span class="fa fa-question-circle" style="color: #bbb"></span></label>
                <div class="col-lg-9">
                    <select name="department_id" class="generate_default_select2 form-control" style="display: none; width: 100%">
                        <option value="">{{ trans('panichd::lang.searchform-department-none') }}</option>
                        @foreach ($c_departments as $dep)
                            <option value="{{ $dep->id }}" @if(isset($search_fields['department_id']) && $search_fields['department_id'] == $dep->id) selected="selected" @endif>{{ $dep->name }}</option>
                            @foreach($dep->descendants as $descendant)
                                <option value="{{ $descendant->id }}" @if(isset($search_fields['department_id']) && $search_fields['department_id'] == $descendant->id) selected="selected" @endif>&nbsp;&nbsp;&nbsp;&nbsp;{{ $descendant->getFullName() }}</option>
                            @endforeach
                        @endforeach
                    </select>
                </div>
            </div>
        @endif
    </div>

    <div class="form-group row align-items-center @if(isset($search_fields['list'])) bg-info @endif" style="margin-bottom: 1.5em"><!-- TICKET LIST -->
        {!! CollectiveForm::label('list', trans('panichd::lang.list') . trans('panichd::lang.colon'), [
            'class' => 'col-lg-3 col-form-label',
            'title' => trans('panichd::lang.create-ticket-change-list')
        ]) !!}
        <div class="col-lg-9">
            <div class="form-check form-check-inline">
                <label class="form-check-label">
                    <input type="radio" class="jquery_ticket_list form-check-input" name="list" value="" @if(!isset($search_fields['list'])) checked="checked" @endif>{{ trans('panichd::lang.searchform-list-none') }}
                </label>
            </div>
            @foreach (['newest', 'active', 'complete'] as $list)
                <div class="form-check form-check-inline">
                    <label class="form-check-label">
                        <input type="radio" class="jquery_ticket_list form-check-input" name="list" value="{{ $list }}" @if(isset($search_fields['list']) && $search_fields['list'] == $list) checked="checked" @endif>{{ trans('panichd::lang.' . $list . '-tickets-adjective') }}
                    </label>
                </div>
            @endforeach

        </div>
    </div>

    <div class="form-group row @if(isset($search_fields['status_id'])) bg-info @endif"><!-- STATUS -->
        {!! CollectiveForm::label('status_id', trans('panichd::lang.status') . trans('panichd::lang.colon'), [
            'class' => 'col-lg-3 col-form-label'
        ]) !!}
        <div class="col-lg-9">
            <select name="status_id[]" class="generate_default_select2 select2-multiple form-control" multiple="multiple" style="display: none; width: 100%">
                <option value="">{{ trans('panichd::lang.searchform-status-none') }}</option>
                @foreach($c_status as $status)
                    <option value="{{ $status->id }}" @if(isset($search_fields['array_status_id']) && in_array($status->id, $search_fields['array_status_id'])) selected="selected" @endif>{{ $status->name }}</option>
                @endforeach
            </select>
            <div class="form-text">
                <label><input type="radio" name="status_id_type" value="any" checked="checked"> {{ trans('panichd::lang.searchform-status-rule-any') }}</label>
                <label class="ml-2"><input type="radio" name="status_id_type" value="none" @if(isset($search_fields['status_id_type']) && $search_fields['status_id_type'] == 'none') checked="checked" @endif> {{ trans('panichd::lang.searchform-status-rule-none') }}</label>
            </div>
        </div>
    </div>
    <div class="form-group row @if(isset($search_fields['priority_id'])) bg-info @endif"><!-- PRIORITY -->
        {!! CollectiveForm::label('priority_id', trans('panichd::lang.priority') . trans('panichd::lang.colon'), ['class' => 'col-lg-3 col-form-label']) !!}
        <div class="col-lg-9">
            <select name="priority_id[]" class="generate_default_select2 select2-multiple form-control" multiple="multiple" style="display: none; width: 100%">
                <option value="">{{ trans('panichd::lang.searchform-priority-none') }}</option>
                @foreach($priorities as $id => $priority)
                    <option value="{{ $id }}" @if(isset($search_fields['array_priority_id']) && in_array($id, $search_fields['array_priority_id'])) selected="selected" @endif>{{ $priority }}</option>
                @endforeach
            </select>
            <div class="form-text">
                <label><input type="radio" name="priority_id_type" value="any" checked="checked"> {{ trans('panichd::lang.searchform-priority-rule-any') }}</label>
                <label class="ml-2"><input type="radio" name="priority_id_type" value="none" @if(isset($search_fields['priority_id_type']) && $search_fields['priority_id_type'] == 'none') checked="checked" @endif> {{ trans('panichd::lang.searchform-priority-rule-none') }}</label>
            </div>
        </div>
    </div>

    <div class="form-group row @if(isset($search_fields['start_date'])) bg-info @endif"><!-- START DATE -->
        {!! CollectiveForm::label('start_date', trans('panichd::lang.start-date') . trans('panichd::lang.colon'), ['class' => 'col-lg-3 col-form-label']) !!}
        <div class="col-lg-9">
            <div class="input-group date" id="start_date">
                <input type="text" class="form-control" name="start_date" value=""/>
                <span class="input-group-addon" style="display: none"></span>
                <span class="input-group-append">
                    <button class="btn btn-light btn-default"><span class="fa fa-calendar"></span></button>
                </span>
            </div>
            <div class="form-text">
                <label><input type="radio" name="start_date_type" value="from" @if(!isset($search_fields['start_date_type']) || $search_fields['start_date_type'] == 'from') checked="checked" @endif> {{ trans('panichd::lang.searchform-date-type-from') }}</label>
                @foreach($a_date_additional_types as $type)
                    <label class="ml-2"><input type="radio" name="start_date_type" value="{{ $type }}" @if(isset($search_fields['start_date_type']) && $search_fields['start_date_type'] == $type) checked="checked" @endif> {{ trans('panichd::lang.searchform-date-type-' . $type) }}</label>
                @endforeach
            </div>
        </div>
    </div>
    
    <div class="form-group row @if(isset($search_fields['limit_date'])) bg-info @endif" style="margin-bottom: 1.5em"><!-- LIMIT DATE -->
        {!! CollectiveForm::label('limit_date', trans('panichd::lang.limit-date') . trans('panichd::lang.colon'), ['class' => 'col-lg-3 col-form-label']) !!}
        <div class="col-lg-9">
            <div class="input-group date" id="limit_date">
                <input type="text" class="form-control" name="limit_date"  value=""/>
                <span class="input-group-addon" style="display: none"></span>
                <span class="input-group-append">
                    <button class="btn btn-light btn-default"><span class="fa fa-calendar"></span></button>
                </span>
            </div>
            <div class="form-text">
                <label><input type="radio" name="limit_date_type" value="from" @if(!isset($search_fields['limit_date_type']) || $search_fields['limit_date_type'] == 'from') checked="checked" @endif> {{ trans('panichd::lang.searchform-date-type-from') }}</label>
                @foreach($a_date_additional_types as $type)
                    <label class="ml-2"><input type="radio" name="limit_date_type" value="{{ $type }}" @if(isset($search_fields['limit_date_type']) && $search_fields['limit_date_type'] == $type) checked="checked" @endif> {{ trans('panichd::lang.searchform-date-type-' . $type) }}</label>
                @endforeach
            </div>
        </div>
    </div>

    <div class="form-group row @if(isset($search_fields['category_id'])) bg-info @endif"><!-- CATEGORY -->
        {!! CollectiveForm::label('category_id', trans('panichd::lang.category') . trans('panichd::lang.colon'), [
            'class' => 'col-lg-3 col-form-label'
        ]) !!}
        <div class="col-lg-9">
            <select id="select_category" class="form-control" name="category_id">
                <option value="">{{ trans('panichd::lang.searchform-category-none') }}</option>
                @foreach($a_categories as $id => $cat)
                    <option value="{{ $id }}" @if(isset($search_fields['category_id']) && $search_fields['category_id'] == $id) selected="selected" @endif>{{ $cat }}</option>
                @endforeach
            </select>    
       </div>
    </div>

    <div class="form-group row @if(isset($search_fields['agent_id'])) bg-info @endif"><!-- AGENT -->
        {!! CollectiveForm::label('agent_id', trans('panichd::lang.agent') . trans('panichd::lang.colon'), [
            'class' => 'col-lg-3 col-form-label'
        ]) !!}
        <div class="col-lg-9">
            <select id="select_category_agent" name="agent_id" class="form-control" style="display: none" disabled="disabled"></select>
            <select id="select_visible_agent" name="agent_id" class="form-control">
                <option value="">{{ trans('panichd::lang.searchform-agent-none') }}</option>
                @foreach($c_visible_agents as $agent)
                    <option value="{{ $agent->id }}" @if(isset($search_fields['agent_id']) && $search_fields['agent_id'] == $agent->id) selected="selected" @endif>{{ $agent->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="form-group row @if(isset($search_fields['tags'])) bg-info @endif"><!-- TAGS -->
        <label for="tags" class="col-form-label col-lg-3">{{ trans('panichd::lang.tags') . trans('panichd::lang.colon') }}</label>
        <div class="col-lg-9">
            <div id="tag_list_container" style="display: none">
                @include('panichd::tickets.partials.tags_menu', ['categories' => $a_categories, 'tag_lists' => $c_cat_tags, 'a_tags_selected' => $search_fields['array_tags'] ?? []])
            </div>
            <div class="form-text">
                <label><input type="radio" id="tags_no_filter" name="tags_type" value="" checked="checked"> {{ trans('panichd::lang.searchform-tags-rule-no-filter') }}</label>
                <label class="ml-2"><input type="radio" name="tags_type" value="has_not_tags" @if(isset($search_fields['tags_type']) && $search_fields['tags_type'] == 'has_not_tags') checked="checked" @endif> {{ trans('panichd::lang.searchform-tags-rule-has_not_tags') }}</label>
                <label class="ml-2"><input type="radio" name="tags_type" value="has_any_tag" @if(isset($search_fields['tags_type']) && $search_fields['tags_type'] == 'has_any_tag') checked="checked" @endif> {{ trans('panichd::lang.searchform-tags-rule-has_any_tag') }}</label>
                <span id="category_tags_rules" class="ml-2" @if(!isset($search_fields['category_id'])) style="display: none" @endif>
                    <label><input type="radio" name="tags_type" value="any" @if(isset($search_fields['tags_type']) && $search_fields['tags_type'] == 'all') checked="checked" @endif> {{ trans('panichd::lang.searchform-tags-rule-any') }}</label>
                    <label class="ml-2"><input type="radio" name="tags_type" value="all" @if(isset($search_fields['tags_type']) && $search_fields['tags_type'] == 'all') checked="checked" @endif> {{ trans('panichd::lang.searchform-tags-rule-all') }}</label>
                    <label class="ml-2"><input type="radio" name="tags_type" value="none" @if(isset($search_fields['tags_type']) && $search_fields['tags_type'] == 'none') checked="checked" @endif> {{ trans('panichd::lang.searchform-tags-rule-none') }}</label>
                </span>
                </div>
        </div>
    </div>

    </div><div class="col-md-6">

    <div class="form-group row @if(isset($search_fields['content'])) bg-info @endif"><!-- DESCRIPTION -->
        <label for="content" class="col-lg-3 col-form-label">{{trans('panichd::lang.description')}}{{trans('panichd::lang.colon')}}</label>
        <div class="col-lg-9">
            <textarea class="form-control" rows="2" name="content" cols="50">{{ $search_fields['content'] ?? '' }}</textarea>
        </div>
    </div>

    <div class="form-group row @if(isset($search_fields['intervention'])) bg-info @endif"><!-- INTERVENTION -->
        <label for="intervention" class="col-lg-3 col-form-label">{{ trans('panichd::lang.intervention') . trans('panichd::lang.colon') }}</label>
        <div class="col-lg-9">
            <textarea class="form-control" rows="2" name="intervention" cols="50">{{ $search_fields['intervention'] ?? '' }}</textarea>
        </div>
    </div>

    <div class="form-group row @if(isset($search_fields['comments'])) bg-info @endif"><!-- COMMENTS -->
        <label for="comments" class="col-lg-3 col-form-label">{{ trans('panichd::lang.searchform-comments') . trans('panichd::lang.colon') }}</label>
        <div class="col-lg-9">
            <textarea class="form-control" rows="2" name="comments" cols="50">{{ $search_fields['comments'] ?? '' }}</textarea>
        </div>
    </div>

    @if ($setting->grab('ticket_attachments_feature'))
        <div class="form-group row @if(isset($search_fields['attachment_name'])) bg-info @endif"><!-- ATTACHMENT FILENAME -->
            {!! CollectiveForm::label('attachment_name', trans('panichd::lang.searchform-attachment_filename') . trans('panichd::lang.colon'), [
                'class' => 'col-lg-3 col-form-label'
            ]) !!}
            <div class="col-lg-9">
                {!! CollectiveForm::text('attachment_name', $search_fields['attachment_name'] ?? null , ['class' => 'form-control']) !!}
            </div>
        </div>
    @endif
    <div class="form-group row @if(isset($search_fields['any_text_field'])) bg-info @endif"><!-- FIND IN ANY TEXT FIELD -->
        <label for="any_text_field" class="col-lg-3 col-form-label tooltip-info" title="{{ trans('panichd::lang.searchform-help-any_text_field') }}">{{ trans('panichd::lang.searchform-any_text_field') . trans('panichd::lang.colon') }} <span class="fa fa-question-circle" style="color: #bbb"></span></label>
        <div class="col-lg-9">
            <textarea class="form-control" rows="2" name="any_text_field" cols="50">{{ $search_fields['any_text_field'] ?? '' }}</textarea>
        </div>
    </div>

    @foreach(['created_at', 'completed_at', 'updated_at'] as $date_field)
        <div class="form-group row @if(isset($search_fields[$date_field])) bg-info @endif">
            {!! CollectiveForm::label($date_field, trans('panichd::lang.searchform-' . $date_field) . trans('panichd::lang.colon'), ['class' => 'col-lg-3 col-form-label']) !!}
            <div class="col-lg-9">
                <div class="input-group date" id="{{ $date_field }}">
                    <input type="text" class="form-control" name="{{ $date_field }}" value="{{ $search_fields[$date_field] ?? '' }}"/>
                    <span class="input-group-addon" style="display: none"></span>
                    <span class="input-group-append">
                        <button class="btn btn-light btn-default"><span class="fa fa-calendar"></span></button>
                    </span>
                </div>
                <div class="form-text">
                    <label><input type="radio" name="{{ $date_field }}_type" value="from" @if(!isset($search_fields[$date_field . '_type']) || $search_fields[$date_field . '_type'] == 'from') checked="checked" @endif> {{ trans('panichd::lang.searchform-date-type-from') }}</label>
                    @foreach($a_date_additional_types as $type)
                        <label class="ml-2"><input type="radio" name="{{ $date_field }}_type" value="{{ $type }}" @if(isset($search_fields[$date_field . '_type']) && $search_fields[$date_field . '_type'] == $type) checked="checked" @endif> {{ trans('panichd::lang.searchform-date-type-' . $type) }}</label>
                    @endforeach
                </div>
            </div>
        </div>
    @endforeach

    <div class="form-group row align-items-center @if(isset($search_fields['read_by_agent'])) bg-info @endif"><!-- UNREAD TICKETS -->
        {!! CollectiveForm::label('read_by_agent', trans('panichd::lang.searchform-read_by_agent') . trans('panichd::lang.colon'), [
            'class' => 'col-lg-3 col-form-label'
        ]) !!}
        <div class="col-lg-9">
            <div class="form-check form-check-inline">
                <label class="form-check-label">
                    <input type="radio" class="jquery_ticket_list form-check-input" name="read_by_agent" value="" checked="checked">{{ trans('panichd::lang.searchform-read_by_agent-none') }}
                </label>
            </div>
            <div class="form-check form-check-inline">
                <label class="form-check-label">
                    <input type="radio" class="jquery_ticket_list form-check-input" name="read_by_agent" value="1" @if(isset($search_fields['read_by_agent']) && $search_fields['read_by_agent'] == '1') checked="checked" @endif>{{ trans('panichd::lang.searchform-read_by_agent-yes') }}
                </label>
            </div>
            <div class="form-check form-check-inline">
                <label class="form-check-label">
                    <input type="radio" class="jquery_ticket_list form-check-input" name="read_by_agent" value="0" @if(isset($search_fields['read_by_agent']) && $search_fields['read_by_agent'] == '0') checked="checked" @endif>{{ trans('panichd::lang.searchform-read_by_agent-no') }}
                </label>
            </div>

        </div>
    </div>

    </div></div>

    <div class="text-center"><!-- SUBMIT BUTTON -->
        {!! CollectiveForm::submit(trans('panichd::lang.searchform-btn-submit'), [
            'class' => 'btn btn-primary ajax_form_submit',
            'data-errors_div' => 'form_errors'
        ]) !!}
    </div>
{!! CollectiveForm::close() !!}
</div></div>