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
		<li role="presentation" class="dropdown {!! $tools->fullUrlIs(action('\PanicHD\PanicHD\Controllers\TicketsController@indexNewest')) || $tools->fullUrlIs(action('\PanicHD\PanicHD\Controllers\TicketsController@create')) ? "active" : "" !!}">
			<a href="#" class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false" title="{{ trans('ticketit::lang.nav-new-tickets-title') }}">
				<span class="{{ $nav_text }}">{{ trans('ticketit::lang.nav-new-tickets') }}</span>
				<span class="{{ $nav_icon }} glyphicon glyphicon-certificate"></span>

				<span class="badge" title="{{ $title }}" style="cursor: help">
				@if (session()->has('ticketit_filter_currentLevel') or (isset($ticket) and session()->has('ticketit_filters')))
					<span class="glyphicon glyphicon-filter"></span>
				@endif
				@if (isset($ticket))
					{{ PanicHD\PanicHD\Models\Ticket::newest()->visible()->filtered()->count() }}
				@else
					{{ PanicHD\PanicHD\Models\Ticket::newest()->visible()->count() }}
				@endif
				
				</span>
				@if (!isset($ticket) and session()->has('ticketit_filters') and !session()->has('ticketit_filter_currentLevel'))
					<span class="glyphicon glyphicon-filter"></span>
				@endif
				 <span class="caret"></span>
			</a>
			<ul class="dropdown-menu">
				<li role="presentation" class="{!! $tools->fullUrlIs(action('\PanicHD\PanicHD\Controllers\TicketsController@indexNewest').'*') ? "active" : "" !!}">
					<a href="{{ action('\PanicHD\PanicHD\Controllers\TicketsController@indexNewest') }}" title="{{ trans('ticketit::lang.nav-new-dd-list-title') }}">{{ trans('ticketit::lang.nav-new-dd-list') }}</a>
				</li>
				<li role="presentation" class="{!! $tools->fullUrlIs(action('\PanicHD\PanicHD\Controllers\TicketsController@create').'*') ? "active" : "" !!}">
					<a href="{{ action('\PanicHD\PanicHD\Controllers\TicketsController@create') }}" title="{{ trans('ticketit::lang.nav-create-ticket-title') }}">{{ trans('ticketit::lang.nav-new-dd-create') }}</a>
				</li>
			</ul>
		</li>
	@else
		<li role="presentation" class="{!! $tools->fullUrlIs(action('\PanicHD\PanicHD\Controllers\TicketsController@create')) ? "active" : "" !!}">
			<a href="{{ action('\PanicHD\PanicHD\Controllers\TicketsController@create') }}" title="{{ trans('ticketit::lang.nav-create-ticket-title') }}">
			<span class="{{ $nav_text }}">{{ trans('ticketit::lang.nav-create-ticket') }}</span>
			<span class="{{ $nav_icon }} glyphicon glyphicon-plus"></span>
			</a>
		</li>
	@endif
	
	<li role="presentation" class="{!! $tools->fullUrlIs(action('\PanicHD\PanicHD\Controllers\TicketsController@index')) ? "active" : "" !!}">
		<a href="{{ action('\PanicHD\PanicHD\Controllers\TicketsController@index') }}" title="{{ trans('ticketit::lang.nav-active-tickets-title') }}">
			<span class="{{ $nav_text }}">{{ trans('ticketit::lang.active-tickets-adjective') }}</span>
			<span class="{{ $nav_icon }} glyphicon glyphicon-file"></span>			
			
			<span class="badge" title="{{ $title }}" style="cursor: help">
			@if (session()->has('ticketit_filter_currentLevel') or (isset($ticket) and session()->has('ticketit_filters')))
				<span class="glyphicon glyphicon-filter"></span>
			@endif
			@if (isset($ticket))
				{{ PanicHD\PanicHD\Models\Ticket::active()->visible()->filtered()->count() }}
			@else
				{{ PanicHD\PanicHD\Models\Ticket::active()->visible()->count() }}
			@endif
			
			</span>
			@if (!isset($ticket) and session()->has('ticketit_filters') and !session()->has('ticketit_filter_currentLevel'))
				<span class="glyphicon glyphicon-filter"></span>
			@endif
		</a>
	</li>
	<li role="presentation" class="{!! $tools->fullUrlIs(action('\PanicHD\PanicHD\Controllers\TicketsController@indexComplete')) ? "active" : "" !!}">
		<a href="{{ action('\PanicHD\PanicHD\Controllers\TicketsController@indexComplete') }}" title="{{ trans('ticketit::lang.nav-completed-tickets-title') }}">
			<span class="{{ $nav_text }}">{{ trans('ticketit::lang.complete-tickets-adjective') }}</span>
			<span class="{{ $nav_icon }} glyphicon glyphicon-ok-circle"></span>
			
			<span class="badge" title="{{ $title }}" style="cursor: help">
			@if (session()->has('ticketit_filter_currentLevel') or (isset($ticket) and session()->has('ticketit_filters')))
				<span class="glyphicon glyphicon-filter"></span>
			@endif
			@if (isset($ticket))
				{{ PanicHD\PanicHD\Models\Ticket::complete()->visible()->filtered()->count() }}
			@else
				{{ PanicHD\PanicHD\Models\Ticket::complete()->visible()->count() }}
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
		<li role="presentation" class="{!! $tools->fullUrlIs(action('\PanicHD\PanicHD\Controllers\DashboardController@index')) || Request::is($setting->grab('admin_route').'/indicator*') ? "active" : "" !!}">
			<a href="{{ action('\PanicHD\PanicHD\Controllers\DashboardController@index') }}" title="{{ trans('ticketit::admin.nav-dashboard-title') }}">
				<span class="{{ $nav_text }}">{{ trans('ticketit::admin.nav-dashboard') }}</span>
				<span class="{{ $nav_icon }} glyphicon glyphicon-stats"></span>
			</a>
		</li>

		<li role="presentation" class="dropdown {!!
			$tools->fullUrlIs(action('\PanicHD\PanicHD\Controllers\StatusesController@index').'*') ||
			$tools->fullUrlIs(action('\PanicHD\PanicHD\Controllers\PrioritiesController@index').'*') ||
			$tools->fullUrlIs(action('\PanicHD\PanicHD\Controllers\AgentsController@index').'*') ||
			$tools->fullUrlIs(action('\PanicHD\PanicHD\Controllers\CategoriesController@index').'*') ||
			$tools->fullUrlIs(action('\PanicHD\PanicHD\Controllers\NoticesController@index').'*') ||
			$tools->fullUrlIs(action('\PanicHD\PanicHD\Controllers\ConfigurationsController@index').'*') ||
			$tools->fullUrlIs(action('\PanicHD\PanicHD\Controllers\AdministratorsController@index').'*')
			? "active" : "" !!}">

			<a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false" title="{{ trans('ticketit::admin.nav-settings') }}">
				<span class="{{ $nav_text }}">{{ trans('ticketit::admin.nav-settings') }}</span>
				<span class="{{ $nav_icon }} glyphicon glyphicon-cog"></span>
				<span class="caret"></span>
			</a>
			<ul class="dropdown-menu">
				<li role="presentation" class="{!! $tools->fullUrlIs(action('\PanicHD\PanicHD\Controllers\StatusesController@index').'*') ? "active" : "" !!}">
					<a href="{{ action('\PanicHD\PanicHD\Controllers\StatusesController@index') }}">{{ trans('ticketit::admin.nav-statuses') }}</a>
				</li>
				<li role="presentation"  class="{!! $tools->fullUrlIs(action('\PanicHD\PanicHD\Controllers\PrioritiesController@index').'*') ? "active" : "" !!}">
					<a href="{{ action('\PanicHD\PanicHD\Controllers\PrioritiesController@index') }}">{{ trans('ticketit::admin.nav-priorities') }}</a>
				</li>
				<li role="presentation"  class="{!! $tools->fullUrlIs(action('\PanicHD\PanicHD\Controllers\AgentsController@index').'*') ? "active" : "" !!}">
					<a href="{{ action('\PanicHD\PanicHD\Controllers\AgentsController@index') }}">{{ trans('ticketit::admin.nav-agents') }}</a>
				</li>
				<li role="presentation"  class="{!! $tools->fullUrlIs(action('\PanicHD\PanicHD\Controllers\CategoriesController@index').'*') ? "active" : "" !!}">
					<a href="{{ action('\PanicHD\PanicHD\Controllers\CategoriesController@index') }}">{{ trans('ticketit::admin.nav-categories') }}</a>
				</li>
				@if ($setting->grab('departments_notices_feature'))
					<li role="presentation"  class="{!! $tools->fullUrlIs(action('\PanicHD\PanicHD\Controllers\NoticesController@index').'*') ? "active" : "" !!}">
						<a href="{{ action('\PanicHD\PanicHD\Controllers\NoticesController@index') }}">{{ trans('ticketit::admin.nav-notices') }}</a>
					</li>
				@endif
				<li role="presentation"  class="{!! $tools->fullUrlIs(action('\PanicHD\PanicHD\Controllers\ConfigurationsController@index').'*') ? "active" : "" !!}">
					<a href="{{ action('\PanicHD\PanicHD\Controllers\ConfigurationsController@index') }}">{{ trans('ticketit::admin.nav-configuration') }}</a>
				</li>
				<li role="presentation"  class="{!! $tools->fullUrlIs(action('\PanicHD\PanicHD\Controllers\AdministratorsController@index').'*') ? "active" : "" !!}">
					<a href="{{ action('\PanicHD\PanicHD\Controllers\AdministratorsController@index')}}">{{ trans('ticketit::admin.nav-administrator') }}</a>
				</li>
			</ul>
		</li>
	@endif
</ul>