<!-- filter panel --><div id="filter_panel" class="card">
	<div class="card-body">
		@if($u->maxLevel() == 2)
			<div @if ($u->currentLevel() == 1) class="float-left" @else style="display: inline-block" @endif>
				<div class="title pov">{{ trans('panichd::lang.filter-pov') }}</div>
				@if ($u->currentLevel() == 2)
					<a class="btn btn-light btn-sm pov-link" href="{{ url($setting->grab('main_route').'/filter/currentLevel/1') }}" id="agent_pov" data-other="user_pov" title="{{ trans('panichd::lang.filter-pov-member-title') }}">{{ trans('panichd::lang.agent') }}</a>
				@else
					<a class="btn btn-light btn-sm pov-link" href="{{ url($setting->grab('main_route').'/filter/currentLevel/remove') }}" id="user_pov" data-other="agent_pov" title="{{ trans('panichd::lang.filter-pov-agent-title') }}">{{ trans('panichd::lang.member') }}</a>
				@endif
			</div>
		@endif

		@if ($u->currentLevel() > 1)
			@if (session()->has('panichd_filters'))
				<a href="{{ action('\PanicHD\PanicHD\Controllers\TicketsController@index') }}/filter/removeall" class="btn btn-light btn-sm tooltip-info removeall-link"  data-toggle="tooltip" title="{{ trans('panichd::lang.filter-removeall-title') }}" data-placement="bottom"><span class="fa fa-filter"></span></a>
			@endif

			@include('panichd::tickets.partials.filter_blocks')
		@endif

		{!! link_to_route($setting->grab('main_route').'.create', trans('panichd::lang.btn-create-new-ticket'), null, ['class' => 'btn btn-light float-right']); !!}

		@if ($u->currentLevel() == 1)
			<h2 class="text-center" style="margin: 0px;">{!! trans('panichd::lang.index-my-tickets') !!}</h2>
		@endif
	</div>
</div><!-- /filter panel -->
