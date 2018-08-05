<!-- filter panel --><div id="filter_panel" class="panel panel-default">	
	<div class="panel-body">
		<?php $button_create = link_to_route($setting->grab('main_route').'.create', trans('panichd::lang.btn-create-new-ticket'), null, ['class' => 'btn btn-default pull-right']);?>
		
		@if ($u->currentLevel() == 2 and $u->maxLevel() == 2)
			<div class="title pov">{{ trans('panichd::lang.filter-pov') }}</div>
			<a href="{{ url($setting->grab('main_route').'/filter/currentLevel/1') }}" id="agent_pov" data-other="user_pov" title="veure com usuari"><button type="button" class="btn btn-default pov-link">{{ trans('panichd::lang.agent') }}</button></a>			
		@endif
		
		@if ($u->currentLevel() > 1)
			@if (session()->has('panichd_filters'))
				<a href="{{ action('\PanicHD\PanicHD\Controllers\TicketsController@index') }}/filter/removeall" class="btn btn-default tooltip-info removeall-link"  data-toggle="tooltip" title="{{ trans('panichd::lang.filter-removeall-title') }}" data-placement="auto bottom"><span class="glyphicon glyphicon-filter"></span></a>
			@endif

			@include('panichd::tickets.partials.filter_blocks')
			{!! $button_create !!}
		@else			
			<div class="text-center">
				@if ($u->maxLevel() == 2)
					<div class="pull-left">
						<div class="title pov">{{ trans('panichd::lang.filter-pov') }}</div>
						<a href="{{ url($setting->grab('main_route').'/filter/currentLevel/remove') }}" id="user_pov" data-other="agent_pov" title="veure com agent"><button type="button" class="btn btn-default pov-link">{{ trans('panichd::lang.user') }}</button></a>
					</div>
				@endif
				<h2 style="display: inline-block; margin: 0em;">{!! trans('panichd::lang.index-my-tickets') !!}</h2>{!! $button_create !!}
			</div>			
		@endif
	
	</div>	
</div><!-- /filter panel -->