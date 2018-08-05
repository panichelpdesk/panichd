<li role="presentation" class="{!! $tools->fullUrlIs(action('\PanicHD\PanicHD\Controllers\DashboardController@index')) || Request::is($setting->grab('admin_route').'/indicator*') ? 'active' : '' !!}">
	<a href="{{ action('\PanicHD\PanicHD\Controllers\DashboardController@index') }}" title="{{ trans('panichd::admin.nav-dashboard-title') }}">
		{{ trans('panichd::admin.nav-dashboard') }}
	</a>
</li>

<li class="divider"></li>

<li role="presentation"  class="{!! $tools->fullUrlIs(action('\PanicHD\PanicHD\Controllers\CategoriesController@index').'*') ? "active" : "" !!}">
	<a href="{{ action('\PanicHD\PanicHD\Controllers\CategoriesController@index') }}">{{ trans('panichd::admin.nav-categories') }}</a>
</li>
<li role="presentation"  class="{!! $tools->fullUrlIs(action('\PanicHD\PanicHD\Controllers\PrioritiesController@index').'*') ? "active" : "" !!}">
	<a href="{{ action('\PanicHD\PanicHD\Controllers\PrioritiesController@index') }}">{{ trans('panichd::admin.nav-priorities') }}</a>
</li>
<li role="presentation" class="{!! $tools->fullUrlIs(action('\PanicHD\PanicHD\Controllers\StatusesController@index').'*') ? "active" : "" !!}">
	<a href="{{ action('\PanicHD\PanicHD\Controllers\StatusesController@index') }}">{{ trans('panichd::admin.nav-statuses') }}</a>
</li>

<li class="divider"></li>

<li role="presentation" class="{!! $tools->fullUrlIs(action('\PanicHD\PanicHD\Controllers\MembersController@index').'*') ? "active" : "" !!}">
	<a href="{{ action('\PanicHD\PanicHD\Controllers\MembersController@index') }}">{{ trans('panichd::admin.nav-members') }}</a>
</li>
<li role="presentation"  class="{!! $tools->fullUrlIs(action('\PanicHD\PanicHD\Controllers\AgentsController@index').'*') ? "active" : "" !!}">
	<a href="{{ action('\PanicHD\PanicHD\Controllers\AgentsController@index') }}">{{ trans('panichd::admin.nav-agents') }}</a>
</li>
<li role="presentation"  class="{!! $tools->fullUrlIs(action('\PanicHD\PanicHD\Controllers\AdministratorsController@index').'*') ? "active" : "" !!}">
	<a href="{{ action('\PanicHD\PanicHD\Controllers\AdministratorsController@index')}}">{{ trans('panichd::admin.nav-administrators') }}</a>
</li>

<li class="divider"></li>



@if ($setting->grab('departments_notices_feature'))
	<li role="presentation"  class="{!! $tools->fullUrlIs(action('\PanicHD\PanicHD\Controllers\NoticesController@index').'*') ? "active" : "" !!}">
		<a href="{{ action('\PanicHD\PanicHD\Controllers\NoticesController@index') }}">{{ trans('panichd::admin.nav-notices') }}</a>
	</li>
@endif
<li role="presentation"  class="{!! $tools->fullUrlIs(action('\PanicHD\PanicHD\Controllers\ConfigurationsController@index').'*') ? "active" : "" !!}">
	<a href="{{ action('\PanicHD\PanicHD\Controllers\ConfigurationsController@index') }}">{{ trans('panichd::admin.nav-configuration') }}</a>
</li>
