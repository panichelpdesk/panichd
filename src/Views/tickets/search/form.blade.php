<div id="search_form" class="card bg-light mb-2" @if(isset($search_fields)) style="display: none" @endif><div class="card-body">
{!! CollectiveForm::open(['route'=> $setting->grab('main_route').'.search.results', 'method' => 'POST']) !!}

    <legend>Search tickets</legend>

    <div class="row" data-class="row"><div class="col-md-6">

    <div class="form-group row"><!-- SUBJECT -->
        {!! CollectiveForm::label('subject', '*' . trans('panichd::lang.subject') . trans('panichd::lang.colon'), [
            'class' => 'col-lg-3 col-form-label level_class',
            'data-level-1-class' => 'col-lg-2',
            'data-level-2-class' => 'col-lg-3'
        ]) !!}
        <div class="col-lg-9 level_class" data-level-1-class="col-lg-10" data-level-2-class="col-lg-9">
            {!! CollectiveForm::text('subject', null , ['class' => 'form-control', 'placeholder' => trans('panichd::lang.create-ticket-brief-issue')]) !!}
        </div>
    </div>

    <div class="form-group row" style="margin-bottom: 1.5em"><!-- OWNER -->

        <label for="owner_id" class="col-lg-3 level_class col-form-label tooltip-info" data-level-1-class="col-lg-2" data-level-2-class="col-lg-3" title="{{ trans('panichd::lang.create-ticket-owner-help') }}"> *{{trans('panichd::lang.owner')}}{{trans('panichd::lang.colon')}} <span class="fa fa-question-circle" style="color: #bbb"></span></label>

        <div class="col-lg-9 level_class" data-level-1-class="col-lg-10" data-level-2-class="col-lg-9">
            <select name="owner_id" class="generate_default_select2 form-control" style="display: none; width: 100%">
                <option value="">- none -</option>
                @foreach ($c_members as $owner)
                    <option value="{{ $owner->id }}">{{ $owner->name . ($owner->email == "" ? ' ' . trans('panichd::lang.ticket-owner-no-email') : ' - ' . $owner->email) }}
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

    <div>
        <div class="form-group row align-items-center" style="margin-bottom: 1.5em"><!-- TICKET LIST -->
            {!! CollectiveForm::label('complete', trans('panichd::lang.list') . trans('panichd::lang.colon'), [
                'class' => 'col-lg-3 col-form-label',
                'title' => trans('panichd::lang.create-ticket-change-list'),
                'id' => 'last_list',
                'data-last_list_default_status_id' => null
            ]) !!}
            <div class="col-lg-9">
                <div class="form-check form-check-inline">
                    <label class="form-check-label">
                        <input type="radio" class="jquery_ticket_list form-check-input" name="list" value="" checked="checked">- none -
                    </label>
                </div>
                @foreach (['newest', 'active', 'complete'] as $list)
                    <div class="form-check form-check-inline">
                        <label class="form-check-label">
                            <input type="radio" class="jquery_ticket_list form-check-input" name="list" value="{{ $list }}">{{ trans('panichd::lang.' . $list . '-tickets-adjective') }}
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
                <select class="form-control" name="status_id">
                    <option value="">- none -</option>
                    @foreach($c_status as $status)
                        <option value="{{ $status->id }}">{{ $status->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group row"><!-- PRIORITY -->
            {!! CollectiveForm::label('priority_id', trans('panichd::lang.priority') . trans('panichd::lang.colon'), ['class' => 'col-lg-3 col-form-label']) !!}
            <div class="col-lg-9">
                <select class="form-control" name="status_id">
                    <option value="">- none -</option>
                    @foreach($priorities as $id => $priority)
                        <option value="{{ $id }}">{{ $priority }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-group row">
            {!! CollectiveForm::label('start_date', trans('panichd::lang.start-date') . trans('panichd::lang.colon'), ['class' => 'col-lg-3 col-form-label']) !!}
            <div class="col-lg-9">
                <div class="input-group date" id="start_date">
                    <input type="text" class="form-control" name="start_date" value=""/>
                    <span class="input-group-addon" style="display: none"></span>
                    <span class="input-group-append">
                        <button class="btn btn-light btn-default"><span class="fa fa-calendar"></span></button>
                    </span>
                </div>
            </div>
        </div>
        <div class="form-group row" style="margin-bottom: 1.5em">
            {!! CollectiveForm::label('limit_date', trans('panichd::lang.limit-date') . trans('panichd::lang.colon'), ['class' => 'col-lg-3 col-form-label']) !!}
            <div class="col-lg-9">
                <div class="input-group date" id="limit_date">
                    <input type="text" class="form-control" name="limit_date"  value=""/>
                    <span class="input-group-addon" style="display: none"></span>
                    <span class="input-group-append">
                        <button class="btn btn-light btn-default"><span class="fa fa-calendar"></span></button>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group row"><!-- CATEGORY -->
        {!! CollectiveForm::label('category_id', '*' . trans('panichd::lang.category') . trans('panichd::lang.colon'), [
            'class' => 'col-lg-3 col-form-label level_class',
            'data-level-1-class' => 'col-lg-2',
            'data-level-2-class' => 'col-lg-3'
        ]) !!}
        <div class="col-lg-9 level_class" data-level-1-class="col-lg-10" data-level-2-class="col-lg-9">
            <select id="category_change" class="form-control" name="category_id">
                <option value="">- none -</option>
                @foreach($a_categories as $id => $cat)
                    <option value="{{ $id }}">{{ $cat }}</option>
                @endforeach
            </select>    
       </div>
    </div>

    <div class="form-group row"><!-- AGENT -->
        {!! CollectiveForm::label('agent_id', trans('panichd::lang.agent') . trans('panichd::lang.colon'), [
            'class' => 'col-lg-3 col-form-label'
        ]) !!}
        <div class="col-lg-9">
            <select id="agent_id" class="form-control" style="display: none"></select>
        </div>
    </div>

    <div class="form-group row"><!-- TAGS -->
        <label class="col-form-label col-lg-3">{{ trans('panichd::lang.tags') . trans('panichd::lang.colon') }}</label>
        <div id="jquery_select2_container" class="col-lg-9">
        @include('panichd::tickets.partials.tags_menu', ['categories' => $a_categories, 'tag_lists' => $c_cat_tags, 'a_tags_selected' => []])
        </div>
    </div>

    </div><div class="col-md-6">

    <div class="form-group row"><!-- DESCRIPTION -->
        <label for="content" class="col-lg-3 col-form-label"> *{{trans('panichd::lang.description')}}{{trans('panichd::lang.colon')}}</label>
        <div class="col-lg-9">
        <textarea class="form-control" rows="5" name="content" cols="50"></textarea>
        </div>
    </div>

    <div class="form-group row"><!-- INTERVENTION -->
        <label for="intervention" class="col-lg-3 col-form-label">{{ trans('panichd::lang.intervention') . trans('panichd::lang.colon') }}</label>
        <div class="col-lg-9">
        <textarea class="form-control" rows="5" name="intervention" cols="50"></textarea>
        </div>
    </div>

    @if ($setting->grab('ticket_attachments_feature'))
        <div class="form-group row">
            {!! CollectiveForm::label('attachment_name', 'Attachment filename' . trans('panichd::lang.colon'), [
                'class' => 'col-lg-3 col-form-label'
            ]) !!}
            <div class="col-lg-9">
                {!! CollectiveForm::text('attachment_name', null , ['class' => 'form-control']) !!}
            </div>
        </div>
    @endif

    </div></div>

    <div class="text-center"><!-- SUBMIT BUTTON -->
        {!! CollectiveForm::submit('Search', [
            'class' => 'btn btn-primary'
        ]) !!}
    </div>
{!! CollectiveForm::close() !!}
</div></div>