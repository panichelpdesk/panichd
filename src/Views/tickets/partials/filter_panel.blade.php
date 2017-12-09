<!-- filter panel --><div id="ticketit_filter_panel" class="panel panel-default">	
	<div class="panel-body">
		<?php $button_create = link_to_route($setting->grab('main_route').'.create', trans('ticketit::lang.btn-create-new-ticket'), null, ['class' => 'btn btn-default pull-right']);?>
		
		@if ($u->currentLevel() == 2 and $u->maxLevel() == 2)
			<div class="title pov">{{ trans('ticketit::lang.filter-pov') }}</div>
			<a href="{{ url($setting->grab('main_route').'/filter/currentLevel/1') }}" id="agent_pov" data-other="user_pov" title="veure com usuari"><button type="button" class="btn btn-default pov-link">{{ trans('ticketit::lang.agent') }}</button></a>			
		@endif
		
		@if ($u->currentLevel() > 1)
			@include('ticketit::tickets.partials.filter_blocks')
			{!! $button_create !!}
		@else			
			<div class="text-center">
				@if ($u->maxLevel() == 2)
					<div class="pull-left">
						<div class="title pov">{{ trans('ticketit::lang.filter-pov') }}</div>
						<a href="{{ url($setting->grab('main_route').'/filter/currentLevel/remove') }}" id="user_pov" data-other="agent_pov" title="veure com agent"><button type="button" class="btn btn-default pov-link">{{ trans('ticketit::lang.user') }}</button></a>
					</div>
				@endif
				<h2 style="display: inline-block; margin: 0em;">{!! trans('ticketit::lang.index-my-tickets') !!}</h2>{!! $button_create !!}
			</div>			
		@endif
	
	</div>	
</div><!-- /filter panel -->