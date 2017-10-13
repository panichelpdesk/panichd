<ul class="nav nav-pills">
	<?php 
		$title = trans('ticketit::lang.filter-'.((session()->has('ticketit_filter_currentLevel') or (isset($ticket) and session()->has('ticketit_filters'))) ? 'on' : 'off').'-total');
		$nav_hidden_sizes = $setting->grab('nav_icons_user_sizes');
		$nav_text = $nav_icon = "";
		if ($nav_hidden_sizes != ""){
			$a_hidden_sizes = explode(',', $nav_hidden_sizes);
			
			foreach($a_hidden_sizes as $size){
				$nav_text.= "hidden-".$size." ";
				$nav_icon.= "visible-".$size."-inline-block ";					
			}
		}else{
			$nav_icon = "hidden";
		}
	?>
	
	@if($u->canViewNewTickets())
	<li role="presentation" class="dropdown {!! $tools->fullUrlIs(action('\Kordy\Ticketit\Controllers\TicketsController@indexNewest')) || $tools->fullUrlIs(action('\Kordy\Ticketit\Controllers\TicketsController@create')) ? "active" : "" !!}" title="{{ $title }}">
		<a href="#" class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false" title="{{ trans('ticketit::lang.nav-new-tickets-title') }}">
			<span class="{{ $nav_text }}">{{ trans('ticketit::lang.nav-new-tickets') }}</span>
			<span class="{{ $nav_icon }} glyphicon glyphicon-certificate"></span>

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
			 <span class="caret"></span>
		</a>
		<ul class="dropdown-menu">
			<li role="presentation" class="{!! $tools->fullUrlIs(action('\Kordy\Ticketit\Controllers\TicketsController@indexNewest').'*') ? "active" : "" !!}">
				<a href="{{ action('\Kordy\Ticketit\Controllers\TicketsController@indexNewest') }}">{{ trans('ticketit::lang.nav-new-tickets') }}</a>
			</li>
			<li role="presentation" class="{!! $tools->fullUrlIs(action('\Kordy\Ticketit\Controllers\TicketsController@create').'*') ? "active" : "" !!}">
				<a href="{{ action('\Kordy\Ticketit\Controllers\TicketsController@create') }}">{{ trans('ticketit::lang.nav-create-ticket') }}</a>
			</li>
		</ul>
	</li>	
	@else
		<li role="presentation" class="{!! $tools->fullUrlIs(action('\Kordy\Ticketit\Controllers\TicketsController@create')) ? "active" : "" !!}">
			<a href="{{ action('\Kordy\Ticketit\Controllers\TicketsController@create') }}" title="{{ trans('ticketit::lang.nav-create-ticket-title') }}">
			<span class="{{ $nav_text }}">{{ trans('ticketit::lang.nav-create-ticket') }}</span>
			<span class="{{ $nav_icon }} glyphicon glyphicon-plus"></span>
			</a>
		</li>
	@endif
	
	<li role="presentation" class="{!! $tools->fullUrlIs(action('\Kordy\Ticketit\Controllers\TicketsController@index')) ? "active" : "" !!}" title="{{ $title }}">
		<a href="{{ action('\Kordy\Ticketit\Controllers\TicketsController@index') }}" title="{{ trans('ticketit::lang.nav-active-tickets-title') }}">
			<span class="{{ $nav_text }}">{{ trans('ticketit::lang.nav-active-tickets') }}</span>
			<span class="{{ $nav_icon }} glyphicon glyphicon-file"></span>			
			
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
		<a href="{{ action('\Kordy\Ticketit\Controllers\TicketsController@indexComplete') }}" title="{{ trans('ticketit::lang.nav-completed-tickets-title') }}">
			<span class="{{ $nav_text }}">{{ trans('ticketit::lang.nav-completed-tickets') }}</span>
			<span class="{{ $nav_icon }} glyphicon glyphicon-ok-circle"></span>
			
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
		<?php
			$nav_hidden_sizes = $setting->grab('nav_icons_admin_sizes');
			$nav_text = $nav_icon = "";
			if ($nav_hidden_sizes != ""){
				$a_hidden_sizes = explode(',', $nav_hidden_sizes);
				
				foreach($a_hidden_sizes as $size){
					$nav_text.= "hidden-".$size." ";
					$nav_icon.= "visible-".$size."-inline-block ";					
				}
			}else{
				$nav_icon = "hidden";
			}			
		?>
		<li role="presentation" class="{!! $tools->fullUrlIs(action('\Kordy\Ticketit\Controllers\DashboardController@index')) || Request::is($setting->grab('admin_route').'/indicator*') ? "active" : "" !!}">
			<a href="{{ action('\Kordy\Ticketit\Controllers\DashboardController@index') }}" title="{{ trans('ticketit::admin.nav-dashboard-title') }}">
				<span class="{{ $nav_text }}">{{ trans('ticketit::admin.nav-dashboard') }}</span>
				<span class="{{ $nav_icon }} glyphicon glyphicon-stats"></span>
			</a>
		</li>

		<li role="presentation" class="dropdown {!!
			$tools->fullUrlIs(action('\Kordy\Ticketit\Controllers\StatusesController@index').'*') ||
			$tools->fullUrlIs(action('\Kordy\Ticketit\Controllers\PrioritiesController@index').'*') ||
			$tools->fullUrlIs(action('\Kordy\Ticketit\Controllers\AgentsController@index').'*') ||
			$tools->fullUrlIs(action('\Kordy\Ticketit\Controllers\CategoriesController@index').'*') ||
			$tools->fullUrlIs(action('\Kordy\Ticketit\Controllers\DeptsUsersController@index').'*') ||
			$tools->fullUrlIs(action('\Kordy\Ticketit\Controllers\ConfigurationsController@index').'*') ||
			$tools->fullUrlIs(action('\Kordy\Ticketit\Controllers\AdministratorsController@index').'*')
			? "active" : "" !!}">

			<a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false" title="{{ trans('ticketit::admin.nav-settings') }}">
				<span class="{{ $nav_text }}">{{ trans('ticketit::admin.nav-settings') }}</span>
				<span class="{{ $nav_icon }} glyphicon glyphicon-cog"></span>
				<span class="caret"></span>
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
				@if ($setting->grab('departments_notices_feature'))
					<li role="presentation"  class="{!! $tools->fullUrlIs(action('\Kordy\Ticketit\Controllers\DeptsUsersController@index').'*') ? "active" : "" !!}">
						<a href="{{ action('\Kordy\Ticketit\Controllers\DeptsUsersController@index') }}">{{ trans('ticketit::admin.nav-dept-users') }}</a>
					</li>
				@endif
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