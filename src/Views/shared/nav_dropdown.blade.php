<ul class="dropdown-menu">
	<a class="dropdown-item {!! $tools->fullUrlIs(action('\PanicHD\PanicHD\Controllers\DashboardController@index')) || Request::is($setting->grab('admin_route').'/indicator*') ? 'active' : '' !!}" href="{{ action('\PanicHD\PanicHD\Controllers\DashboardController@index') }}" title="{{ trans('panichd::admin.nav-dashboard-title') }}">
		{{ trans('panichd::admin.nav-dashboard') }}
	</a>
	
	<li class="dropdown-divider"></li>
	
	<a class="dropdown-item {!! $tools->fullUrlIs(action('\PanicHD\PanicHD\Controllers\CategoriesController@index').'*') ? "active" : "" !!}" href="{{ action('\PanicHD\PanicHD\Controllers\CategoriesController@index') }}">{{ trans('panichd::admin.nav-categories') }}</a>
	<a class="dropdown-item {!! $tools->fullUrlIs(action('\PanicHD\PanicHD\Controllers\PrioritiesController@index').'*') ? "active" : "" !!}" href="{{ action('\PanicHD\PanicHD\Controllers\PrioritiesController@index') }}">{{ trans('panichd::admin.nav-priorities') }}</a>
	<a class="dropdown-item {!! $tools->fullUrlIs(action('\PanicHD\PanicHD\Controllers\StatusesController@index').'*') ? "active" : "" !!}" href="{{ action('\PanicHD\PanicHD\Controllers\StatusesController@index') }}">{{ trans('panichd::admin.nav-statuses') }}</a>

	<li class="dropdown-divider"></li>
	
	<a class="dropdown-item {!! $tools->fullUrlIs(action('\PanicHD\PanicHD\Controllers\MembersController@index').'*') ? "active" : "" !!}" href="{{ action('\PanicHD\PanicHD\Controllers\MembersController@index') }}">{{ trans('panichd::admin.nav-members') }}</a>
	<a class="dropdown-item {!! $tools->fullUrlIs(action('\PanicHD\PanicHD\Controllers\AgentsController@index').'*') ? "active" : "" !!}" href="{{ action('\PanicHD\PanicHD\Controllers\AgentsController@index') }}">{{ trans('panichd::admin.nav-agents') }}</a>
	<a class="dropdown-item {!! $tools->fullUrlIs(action('\PanicHD\PanicHD\Controllers\AdministratorsController@index').'*') ? "active" : "" !!}" href="{{ action('\PanicHD\PanicHD\Controllers\AdministratorsController@index')}}">{{ trans('panichd::admin.nav-administrators') }}</a>

	<li class="dropdown-divider"></li>
	
	@if ($setting->grab('departments_notices_feature'))
		<a class="dropdown-item {!! $tools->fullUrlIs(action('\PanicHD\PanicHD\Controllers\NoticesController@index').'*') ? "active" : "" !!}" href="{{ action('\PanicHD\PanicHD\Controllers\NoticesController@index') }}">{{ trans('panichd::admin.nav-notices') }}</a>
	@endif
	<a class="dropdown-item {!! $tools->fullUrlIs(action('\PanicHD\PanicHD\Controllers\ConfigurationsController@index').'*') ? "active" : "" !!}" href="{{ action('\PanicHD\PanicHD\Controllers\ConfigurationsController@index') }}">{{ trans('panichd::admin.nav-configuration') }}</a>
</ul>