<ul class="nav nav-pills">
	<?php $title = trans('ticketit::lang.filter-'.((session()->has('ticketit_filter_currentLevel') or (isset($ticket) and session()->has('ticketit_filters'))) ? 'on' : 'off').'-total');	?>
	
	@if($u->canViewNewTickets())
	<li role="presentation" class="{!! $tools->fullUrlIs(action('\Kordy\Ticketit\Controllers\TicketsController@indexNewest')) ? "active" : "" !!}" title="{{ $title }}">
		<a href="{{ action('\Kordy\Ticketit\Controllers\TicketsController@indexNewest') }}" >Nous		
			<span class="badge">
			@if (session()->has('ticketit_filter_currentLevel') or (isset($ticket) and session()->has('ticketit_filters')))
				<span class="glyphicon glyphicon-filter"></span>
			@endif
			@if (isset($ticket))
				{{ Kordy\Ticketit\Models\Ticket::newest()->visible()->filtered()->count() }}
			@else
				{{ Kordy\Ticketit\Models\Ticket::newest()->visible()->count() }}
			@endif
			
			</span>
			@if (!isset($ticket) and session()->has('ticketit_filters') and !session()->has('ticketit_filter_currentLevel'))
				<span class="glyphicon glyphicon-filter"></span>
			@endif
		</a>
	</li>	
	@endif
	
	<li role="presentation" class="{!! $tools->fullUrlIs(action('\Kordy\Ticketit\Controllers\TicketsController@index')) ? "active" : "" !!}" title="{{ $title }}">
		<a href="{{ action('\Kordy\Ticketit\Controllers\TicketsController@index') }}" >{{ trans('ticketit::lang.nav-active-tickets') }}		
			<span class="badge">
			@if (session()->has('ticketit_filter_currentLevel') or (isset($ticket) and session()->has('ticketit_filters')))
				<span class="glyphicon glyphicon-filter"></span>
			@endif
			@if (isset($ticket))
				{{ Kordy\Ticketit\Models\Ticket::active()->visible()->filtered()->count() }}
			@else
				{{ Kordy\Ticketit\Models\Ticket::active()->visible()->count() }}
			@endif
			
			</span>
			@if (!isset($ticket) and session()->has('ticketit_filters') and !session()->has('ticketit_filter_currentLevel'))
				<span class="glyphicon glyphicon-filter"></span>
			@endif
		</a>
	</li>
	<li role="presentation" class="{!! $tools->fullUrlIs(action('\Kordy\Ticketit\Controllers\TicketsController@indexComplete')) ? "active" : "" !!}" title="{{ $title }}">
		<a href="{{ action('\Kordy\Ticketit\Controllers\TicketsController@indexComplete') }}">{{ trans('ticketit::lang.nav-completed-tickets') }}			
			<span class="badge">
			@if (session()->has('ticketit_filter_currentLevel') or (isset($ticket) and session()->has('ticketit_filters')))
				<span class="glyphicon glyphicon-filter"></span>
			@endif
			@if (isset($ticket))
				{{ Kordy\Ticketit\Models\Ticket::complete()->visible()->filtered()->count() }}
			@else
				{{ Kordy\Ticketit\Models\Ticket::complete()->visible()->count() }}
			@endif
			
			</span>
			@if (!isset($ticket) and session()->has('ticketit_filters') and !session()->has('ticketit_filter_currentLevel'))
				<span class="glyphicon glyphicon-filter"></span>
			@endif
		</a>
	</li>

	@if($u->isAdmin())
		<li role="presentation" class="{!! $tools->fullUrlIs(action('\Kordy\Ticketit\Controllers\DashboardController@index')) || Request::is($setting->grab('admin_route').'/indicator*') ? "active" : "" !!}">
			<a href="{{ action('\Kordy\Ticketit\Controllers\DashboardController@index') }}">{{ trans('ticketit::admin.nav-dashboard') }}</a>
		</li>

		<li role="presentation" class="dropdown {!!
			$tools->fullUrlIs(action('\Kordy\Ticketit\Controllers\StatusesController@index').'*') ||
			$tools->fullUrlIs(action('\Kordy\Ticketit\Controllers\PrioritiesController@index').'*') ||
			$tools->fullUrlIs(action('\Kordy\Ticketit\Controllers\AgentsController@index').'*') ||
			$tools->fullUrlIs(action('\Kordy\Ticketit\Controllers\CategoriesController@index').'*') ||
			$tools->fullUrlIs(action('\Kordy\Ticketit\Controllers\ConfigurationsController@index').'*') ||
			$tools->fullUrlIs(action('\Kordy\Ticketit\Controllers\AdministratorsController@index').'*')
			? "active" : "" !!}">

			<a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
				{{ trans('ticketit::admin.nav-settings') }} <span class="caret"></span>
			</a>
			<ul class="dropdown-menu">
				<li role="presentation" class="{!! $tools->fullUrlIs(action('\Kordy\Ticketit\Controllers\StatusesController@index').'*') ? "active" : "" !!}">
					<a href="{{ action('\Kordy\Ticketit\Controllers\StatusesController@index') }}">{{ trans('ticketit::admin.nav-statuses') }}</a>
				</li>
				<li role="presentation"  class="{!! $tools->fullUrlIs(action('\Kordy\Ticketit\Controllers\PrioritiesController@index').'*') ? "active" : "" !!}">
					<a href="{{ action('\Kordy\Ticketit\Controllers\PrioritiesController@index') }}">{{ trans('ticketit::admin.nav-priorities') }}</a>
				</li>
				<li role="presentation"  class="{!! $tools->fullUrlIs(action('\Kordy\Ticketit\Controllers\AgentsController@index').'*') ? "active" : "" !!}">
					<a href="{{ action('\Kordy\Ticketit\Controllers\AgentsController@index') }}">{{ trans('ticketit::admin.nav-agents') }}</a>
				</li>
				<li role="presentation"  class="{!! $tools->fullUrlIs(action('\Kordy\Ticketit\Controllers\CategoriesController@index').'*') ? "active" : "" !!}">
					<a href="{{ action('\Kordy\Ticketit\Controllers\CategoriesController@index') }}">{{ trans('ticketit::admin.nav-categories') }}</a>
				</li>
				<li role="presentation"  class="{!! $tools->fullUrlIs(action('\Kordy\Ticketit\Controllers\ConfigurationsController@index').'*') ? "active" : "" !!}">
					<a href="{{ action('\Kordy\Ticketit\Controllers\ConfigurationsController@index') }}">{{ trans('ticketit::admin.nav-configuration') }}</a>
				</li>
				<li role="presentation"  class="{!! $tools->fullUrlIs(action('\Kordy\Ticketit\Controllers\AdministratorsController@index').'*') ? "active" : "" !!}">
					<a href="{{ action('\Kordy\Ticketit\Controllers\AdministratorsController@index')}}">{{ trans('ticketit::admin.nav-administrator') }}</a>
				</li>
			</ul>
		</li>
	@endif
</ul>