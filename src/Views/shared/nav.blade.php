<ul class="nav nav-pills">
	
	<li role="presentation" class="{!! $n_notices == 0 ? 'disabled' : ($tools->fullUrlIs(route($setting->grab('main_route').'.notices')) ? 'active' : '') !!}" >
		<a href="{{ $n_notices == 0 ? '#' : route($setting->grab('main_route').'.notices') }}" title="{{ $n_notices == 0 ? trans('panichd::lang.ticket-notices-empty') : trans('panichd::lang.nav-notices-number-title', ['num' => $n_notices]) }}">{{ trans('panichd::lang.ticket-notices-title') }} <span class="badge">{{ $n_notices }}</span></a>
	</li>
	
	<?php 
		$title = trans('panichd::lang.filter-'.((session()->has('panichd_filter_currentLevel') or (isset($ticket) and session()->has('panichd_filters'))) ? 'on' : 'off').'-total');
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
			<a href="#" class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false" title="{{ trans('panichd::lang.nav-new-tickets-title') }}">
				<span class="{{ $nav_text }}">{{ trans('panichd::lang.nav-new-tickets') }}</span>
				<span class="{{ $nav_icon }} glyphicon glyphicon-certificate"></span>

				<span class="badge" title="{{ $title }}" style="cursor: help">
				@if (session()->has('panichd_filter_currentLevel') or (isset($ticket) and session()->has('panichd_filters')))
					<span class="glyphicon glyphicon-filter"></span>
				@endif
				@if (isset($ticket))
					{{ PanicHD\PanicHD\Models\Ticket::newest()->visible()->filtered('new')->count() }}
				@else
					{{ PanicHD\PanicHD\Models\Ticket::newest()->visible()->count() }}
				@endif
				
				</span>
				@if (!isset($ticket) and session()->has('panichd_filters') and !session()->has('panichd_filter_currentLevel'))
					<span class="glyphicon glyphicon-filter"></span>
				@endif
				 <span class="caret"></span>
			</a>
			<ul class="dropdown-menu">
				<li role="presentation" class="{!! $tools->fullUrlIs(action('\PanicHD\PanicHD\Controllers\TicketsController@indexNewest').'*') ? "active" : "" !!}">
					<a href="{{ action('\PanicHD\PanicHD\Controllers\TicketsController@indexNewest') }}" title="{{ trans('panichd::lang.nav-new-dd-list-title') }}">{{ trans('panichd::lang.nav-new-dd-list') }}</a>
				</li>
				<li role="presentation" class="{!! $tools->fullUrlIs(action('\PanicHD\PanicHD\Controllers\TicketsController@create').'*') ? "active" : "" !!}">
					<a href="{{ action('\PanicHD\PanicHD\Controllers\TicketsController@create') }}" title="{{ trans('panichd::lang.nav-create-ticket-title') }}">{{ trans('panichd::lang.nav-new-dd-create') }}</a>
				</li>
			</ul>
		</li>
	@else
		<li role="presentation" class="{!! $tools->fullUrlIs(action('\PanicHD\PanicHD\Controllers\TicketsController@create')) ? "active" : "" !!}">
			<a href="{{ action('\PanicHD\PanicHD\Controllers\TicketsController@create') }}" title="{{ trans('panichd::lang.nav-create-ticket-title') }}">
			<span class="{{ $nav_text }}">{{ trans('panichd::lang.nav-create-ticket') }}</span>
			<span class="{{ $nav_icon }} glyphicon glyphicon-plus"></span>
			</a>
		</li>
	@endif
	
	<li role="presentation" class="{!! $tools->fullUrlIs(action('\PanicHD\PanicHD\Controllers\TicketsController@index')) ? "active" : "" !!}">
		<a href="{{ action('\PanicHD\PanicHD\Controllers\TicketsController@index') }}" title="{{ trans('panichd::lang.nav-active-tickets-title') }}">
			<span class="{{ $nav_text }}">{{ trans('panichd::lang.active-tickets-adjective') }}</span>
			<span class="{{ $nav_icon }} glyphicon glyphicon-file"></span>			
			
			<span class="badge" title="{{ $title }}" style="cursor: help">
			@if (session()->has('panichd_filter_currentLevel') or (isset($ticket) and session()->has('panichd_filters')))
				<span class="glyphicon glyphicon-filter"></span>
			@endif
			@if (isset($ticket))
				{{ PanicHD\PanicHD\Models\Ticket::active()->visible()->filtered('active')->count() }}
			@else
				{{ PanicHD\PanicHD\Models\Ticket::active()->visible()->count() }}
			@endif
			
			</span>
			@if (!isset($ticket) and session()->has('panichd_filters') and !session()->has('panichd_filter_currentLevel'))
				<span class="glyphicon glyphicon-filter"></span>
			@endif
		</a>
	</li>
	<li role="presentation" class="{!! $tools->fullUrlIs(action('\PanicHD\PanicHD\Controllers\TicketsController@indexComplete')) ? "active" : "" !!}">
		<a href="{{ action('\PanicHD\PanicHD\Controllers\TicketsController@indexComplete') }}" title="{{ trans('panichd::lang.nav-completed-tickets-title') }}">
			<span class="{{ $nav_text }}">{{ trans('panichd::lang.complete-tickets-adjective') }}</span>
			<span class="{{ $nav_icon }} glyphicon glyphicon-ok-circle"></span>
			
			<span class="badge" title="{{ $title }}" style="cursor: help">
			@if (session()->has('panichd_filter_currentLevel') or (isset($ticket) and session()->has('panichd_filters')))
				<span class="glyphicon glyphicon-filter"></span>
			@endif
			@if (isset($ticket))
				{{ PanicHD\PanicHD\Models\Ticket::complete()->visible()->filtered('complete')->count() }}
			@else
				{{ PanicHD\PanicHD\Models\Ticket::visible()->completedOnYear()->count() }}
			@endif
			
			</span>
			@if (!isset($ticket) and session()->has('panichd_filters') and !session()->has('panichd_filter_currentLevel'))
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

		<li role="presentation" class="dropdown {!!
			$tools->fullUrlIs(action('\PanicHD\PanicHD\Controllers\StatusesController@index').'*') ||
			$tools->fullUrlIs(action('\PanicHD\PanicHD\Controllers\PrioritiesController@index').'*') ||
			$tools->fullUrlIs(action('\PanicHD\PanicHD\Controllers\AgentsController@index').'*') ||
			$tools->fullUrlIs(action('\PanicHD\PanicHD\Controllers\CategoriesController@index').'*') ||
			$tools->fullUrlIs(action('\PanicHD\PanicHD\Controllers\NoticesController@index').'*') ||
			$tools->fullUrlIs(action('\PanicHD\PanicHD\Controllers\ConfigurationsController@index').'*') ||
			$tools->fullUrlIs(action('\PanicHD\PanicHD\Controllers\AdministratorsController@index').'*')
			? "active" : "" !!}">

			<a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false" title="{{ trans('panichd::admin.nav-settings') }}">
				<span class="{{ $nav_text }}">{{ $setting->grab('admin_button_text') }}</span>
				<span class="{{ $nav_icon }} glyphicon glyphicon-cog"></span>
				<span class="caret"></span>
			</a>
			<ul class="dropdown-menu">
				@include('panichd::shared.nav_dropdown')
			</ul>
		</li>
	@endif
</ul>