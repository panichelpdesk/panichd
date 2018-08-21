@extends($master)

@section('page')
    {{ trans('panichd::admin.index-title') }}
@stop

@include('panichd::shared.common')

@section('content')
    @if($tickets_count)
        <div class="row">
            <div class="col-md-3">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3" style="font-size: 3em;">
                                <i class="fa fa-book"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <h2>{{ $tickets_count }} <small>{{ trans('panichd::admin.index-total-tickets') }}</small></h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3" style="font-size: 3em;">
                                <i class="fa fa-certificate"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <h1>{{ $a_tickets_count['newest'] }} <small>{{ trans('panichd::admin.index-newest-tickets') }}</small></h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
			<div class="col-md-3">
                <div class="panel panel-warning">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3" style="font-size: 3em;">
                                <i class="fa fa-file"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <h1>{{ $a_tickets_count['active'] }} <small>{{ trans('panichd::admin.index-active-tickets') }}</small></h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="panel panel-success">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3" style="font-size: 3em;">
                                <i class="fa fa-check-circle"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <h1>{{ $a_tickets_count['complete'] }} <small>{{ trans('panichd::admin.index-complete-tickets') }}</small></h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-bar-chart-o fa-fw"></i> {{ trans('panichd::admin.index-performance-indicator') }}
                        <div class="pull-right">
                            <div class="btn-group">
                                <button type="button" class="btn btn-light btn-xs dropdown-toggle" data-toggle="dropdown">
                                    {{ trans('panichd::admin.index-periods') }}
                                    <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu pull-right" role="menu">
                                    <li>
                                        <a href="{{ action('\PanicHD\PanicHD\Controllers\DashboardController@index', 2) }}">
                                            {{ trans('panichd::admin.index-3-months') }}
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ action('\PanicHD\PanicHD\Controllers\DashboardController@index', 5) }}">
                                            {{ trans('panichd::admin.index-6-months') }}
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ action('\PanicHD\PanicHD\Controllers\DashboardController@index', 11) }}">
                                            {{ trans('panichd::admin.index-12-months') }}
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div id="curve_chart" style="width: 100%; height: 350px"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                {{ trans('panichd::admin.index-tickets-share-per-category') }}
                            </div>
                            <div class="panel-body">
                                <div id="catpiechart" style="width: auto; height: 350;"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                {{ trans('panichd::admin.index-tickets-share-per-agent') }}
                            </div>
                            <div class="panel-body">
                                <div id="agentspiechart" style="width: auto; height: 350;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                {{--<h4><i class="fa fa-info-circle"></i> Tickets Activities</h4>--}}
                {{--<hr />--}}
                <ul class="nav nav-tabs nav-justified">
                    <li class="{{$active_tab == "cat" ? "active" : ""}}">
                        <a data-toggle="pill" href="#information-panel-categories">
                            <i class="fa fa-folder"></i>
                            <small>{{ trans('panichd::admin.index-categories') }}</small>
                        </a>
                    </li>
                    <li class="{{$active_tab == "agents" ? "active"  : ""}}">
                        <a data-toggle="pill" href="#information-panel-agents">
                            <i class="fa fa-user"></i>
                            <small>{{ trans('panichd::admin.index-agents') }}</small>
                        </a>
                    </li>
                    <li class="{{$active_tab == "users" ? "active" : ""}}">
                        <a data-toggle="pill" href="#information-panel-users">
                            <i class="fa fa-user"></i>
                            <small>{{ trans('panichd::admin.index-users') }}</small>
                        </a>
                    </li>
                </ul>
                <br>
                <div class="tab-content">
                    <ul id="information-panel-categories" class="list-group tab-pane fade {{$active_tab == "cat" ? "in active" : ""}}">
                        <li class="list-group-item">
                            <span>{{ trans('panichd::admin.index-category') }}
                                <span class="badge">{{ trans('panichd::admin.index-total') }}</span>
                            </span>
                            <span class="pull-right text-muted small">
                                
								<?php 
									$counter_lists_text = trans('panichd::lang.newest-tickets-adjective') . ' - ' . trans('panichd::lang.active-tickets-adjective') . ' - ' . trans('panichd::lang.complete-tickets-adjective');
								?>
								
								<em>{{ $counter_lists_text }}</em>
                            </span>
                        </li>
                        @foreach($categories as $category)
                            <li class="list-group-item">
								<span style="color: {{ $category->color }}">
									{{ $category->name }} <span class="badge"  style="background-color: {{ $category->color }}">{{ $category->tickets()->count() }}</span>
								</span>
								<span class="pull-right small">
									<?php
										$a_button = [
											'newest' => $category->tickets()->newest()->count() . ' ' . trans('panichd::lang.newest-tickets-adjective'),
											'active' => $category->tickets()->active()->count() . ' ' . trans('panichd::lang.active-tickets-adjective'),
											'complete' => $category->tickets()->complete()->count() . ' ' . trans('panichd::lang.complete-tickets-adjective')
										];
									?>
									
									@if ($category->tickets()->newest()->count() == 0)
										{{ $a_button['newest'] }}
									@else
										<a href="{{ route($setting->grab('main_route') . '-filteronly', ['filter' => 'category', 'value' => $category->id, 'list' => 'newest']) }}" class="btn btn-light btn-xs" title="{{ trans('panichd::admin.index-view-category-tickets', ['list' =>trans('panichd::lang.newest-tickets-adjective'), 'category' => $category->name ]) }}">
										{{ $a_button['newest'] }}
										</a>
									@endif
									 -
									@if ($category->tickets()->active()->count() == 0)
										{{ $a_button['active'] }}
									@else
										<a href="{{ route($setting->grab('main_route') . '-filteronly', ['filter' => 'category', 'value' => $category->id, 'list' => 'active']) }}" class="btn btn-light btn-xs" title="{{ trans('panichd::admin.index-view-category-tickets', ['list' =>trans('panichd::lang.active-tickets-adjective'), 'category' => $category->name ]) }}">
										{{ $a_button['active'] }}
										</a>
									@endif
									 -
									@if ($category->tickets()->complete()->count() == 0)
										{{ $a_button['complete'] }}
									@else
										<a href="{{ route($setting->grab('main_route') . '-filteronly', ['filter' => 'category', 'value' => $category->id, 'list' => 'complete']) }}" class="btn btn-light btn-xs" title="{{ trans('panichd::admin.index-view-category-tickets', ['list' =>trans('panichd::lang.complete-tickets-adjective'), 'category' => $category->name ]) }}">
										{{ $a_button['complete'] }}
										</a>
									@endif
								</span>
                            </li>
                        @endforeach
                        {!! $categories->render() !!}
                    </ul>
                    <ul id="information-panel-agents" class="list-group tab-pane fade {{$active_tab == "agents" ? "in active" : ""}}">
                        <li class="list-group-item">
                            <span>{{ trans('panichd::admin.index-agent') }}
                                <span class="badge">{{ trans('panichd::admin.index-total') }}</span>
                            </span>
                            <span class="pull-right text-muted small">
                                <em>{{ $counter_lists_text }}</em>
                            </span>
                        </li>
                        @foreach($agents as $agent)
                            <li class="list-group-item">
                                <?php $agent_text = $agent->name . ' <span class="badge">' . ($agent->agentTickets(false)->count()  +
                                         $agent->agentTickets(true)->count()) . '</span>'; ?>
								@if ($setting->grab('user_route') != 'disabled')
									<a href="{{ route($setting->grab('user_route'), ['id' => $agent->id]) }}">{!! $agent_text !!}</a>
								@else
									<span>{!! $agent_text !!}</span>
								@endif
								
                                <span class="pull-right small">
									<?php
										$a_button = [
											'newest' => $agent->agentTickets(false)->newest()->count() . ' ' . trans('panichd::lang.newest-tickets-adjective'),
											'active' => $agent->agentTickets(false)->active()->count() . ' ' . trans('panichd::lang.active-tickets-adjective'),
											'complete' => $agent->agentTickets(true)->count() . ' ' . trans('panichd::lang.complete-tickets-adjective')
										];
									?>
									
									@if ($agent->agentTickets(false)->newest()->count() == 0)
										{{ $a_button['newest'] }}
									@else
										<a href="{{ route($setting->grab('main_route') . '-filteronly', ['filter' => 'agent', 'value' => $agent->id, 'list' => 'newest']) }}" class="btn btn-light btn-xs" title="{{ trans('panichd::admin.index-view-agent-tickets', ['list' =>trans('panichd::lang.newest-tickets-adjective')]) }}">
										{{ $a_button['newest'] }}
										</a>
									@endif
									 -
									@if ($agent->agentTickets(false)->active()->count() == 0)
										{{ $a_button['active'] }}
									@else
										<a href="{{ route($setting->grab('main_route') . '-filteronly', ['filter' => 'agent', 'value' => $agent->id, 'list' => 'active']) }}" class="btn btn-light btn-xs" title="{{ trans('panichd::admin.index-view-agent-tickets', ['list' =>trans('panichd::lang.active-tickets-adjective')]) }}">
										{{ $a_button['active'] }}
										</a>
									@endif
									 -
									@if ($agent->agentTickets(true)->count() == 0)
										{{ $a_button['complete'] }}
									@else
										<a href="{{ route($setting->grab('main_route') . '-filteronly', ['filter' => 'agent', 'value' => $agent->id, 'list' => 'complete']) }}" class="btn btn-light btn-xs" title="{{ trans('panichd::admin.index-view-agent-tickets', ['list' =>trans('panichd::lang.complete-tickets-adjective')]) }}">
										{{ $a_button['complete'] }}
										</a>
									@endif
                                </span>
                            </li>
                        @endforeach
                        {!! $agents->render() !!}
                    </ul>
                    <ul id="information-panel-users" class="list-group tab-pane fade {{ $active_tab == "users" ? "in active" : "" }}">
                        <li class="list-group-item">
                            <span>{{ trans('panichd::admin.index-user') }}
                                <span class="badge">{{ trans('panichd::admin.index-total') }}</span>
                            </span>
                            <span class="pull-right text-muted small">
                                <em>{{ $counter_lists_text }}</em>
                            </span>
                        </li>
                        @foreach($users as $user)
                            <li class="list-group-item">
                                <?php $user_text = $user->name . ' <span class="badge">' . $user->tickets()->count() . '</span>'; ?>
								@if ($setting->grab('user_route') != 'disabled')
									<a href="{{ route($setting->grab('user_route'), ['id' => $user->id]) }}">{!! $user_text !!}</a>
								@else
									<span>{!! $user_text !!}</span>
								@endif
								
                                <span class="pull-right small">
                                    <?php
										$a_button = [
											'newest' => $user->tickets()->newest()->count() . ' ' . trans('panichd::lang.newest-tickets-adjective'),
											'active' => $user->tickets()->active()->count() . ' ' . trans('panichd::lang.active-tickets-adjective'),
											'complete' => $user->tickets()->complete()->count() . ' ' . trans('panichd::lang.complete-tickets-adjective')
										];
									?>
									
									@if ($user->tickets()->newest()->count() == 0)
										{{ $a_button['newest'] }}
									@else
										<a href="{{ route($setting->grab('main_route') . '-filteronly', ['filter' => 'owner', 'value' => $user->id, 'list' => 'newest']) }}" class="btn btn-light btn-xs" title="{{ trans('panichd::admin.index-view-user-tickets', ['list' =>trans('panichd::lang.newest-tickets-adjective')]) }}">
										{{ $a_button['newest'] }}
										</a>
									@endif
									 -
									@if ($user->tickets()->active()->count() == 0)
										{{ $a_button['active'] }}
									@else
										<a href="{{ route($setting->grab('main_route') . '-filteronly', ['filter' => 'owner', 'value' => $user->id, 'list' => 'active']) }}" class="btn btn-light btn-xs" title="{{ trans('panichd::admin.index-view-user-tickets', ['list' =>trans('panichd::lang.active-tickets-adjective')]) }}">
										{{ $a_button['active'] }}
										</a>
									@endif
									 -
									@if ($user->tickets()->complete()->count() == 0)
										{{ $a_button['complete'] }}
									@else
										<a href="{{ route($setting->grab('main_route') . '-filteronly', ['filter' => 'owner', 'value' => $user->id, 'list' => 'complete']) }}" class="btn btn-light btn-xs" title="{{ trans('panichd::admin.index-view-user-tickets', ['list' =>trans('panichd::lang.complete-tickets-adjective')]) }}">
										{{ $a_button['complete'] }}
										</a>
									@endif
                                </span>
                            </a>
                        @endforeach
                        {!! $users->render() !!}
                    </ul>
                </div>
            </div>
        </div>
    @else
        <div class="well text-center">
            {{ trans('panichd::admin.index-empty-records') }}
        </div>
    @endif
@stop
@section('footer')
    @if($tickets_count)
    {{--@include('panichd::shared.footer')--}}
    <script type="text/javascript"
            src="https://www.google.com/jsapi?autoload={
            'modules':[{
              'name':'visualization',
              'version':'1',
              'packages':['corechart']
            }]
          }"></script>

    <script type="text/javascript">
        google.setOnLoadCallback(drawChart);

        // performance line chart
        function drawChart() {
            var data = google.visualization.arrayToDataTable([
                ["{{ trans('panichd::admin.index-month') }}", "{!! implode('", "', $monthly_performance['categories']) !!}"],
                @foreach($monthly_performance['interval'] as $month => $records)
                    ["{{ $month }}", {!! implode(',', $records) !!}],
                @endforeach
            ]);

            var options = {
                title: '{!! addslashes(trans('panichd::admin.index-performance-chart')) !!}',
                curveType: 'function',
                legend: {position: 'right'},
                vAxis: {
                    viewWindowMode:'explicit',
                    format: '#',
                    viewWindow:{
                        min:0
                    }
                }
            };

            var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));

            chart.draw(data, options);

            // Categories Pie Chart
            var cat_data = google.visualization.arrayToDataTable([
              ['{{ trans('panichd::admin.index-category') }}', '{!! addslashes(trans('panichd::admin.index-tickets')) !!}'],
              @foreach($categories_share as $cat_name => $cat_tickets)
                    ['{!! addslashes($cat_name) !!}', {{ $cat_tickets }}],
              @endforeach
            ]);

            var cat_options = {
              title: '{!! addslashes(trans('panichd::admin.index-categories-chart')) !!}',
              legend: {position: 'bottom'}
            };

            var cat_chart = new google.visualization.PieChart(document.getElementById('catpiechart'));

            cat_chart.draw(cat_data, cat_options);

            // Agents Pie Chart
            var agent_data = google.visualization.arrayToDataTable([
              ['{{ trans('panichd::admin.index-agent') }}', '{!! addslashes(trans('panichd::admin.index-tickets')) !!}'],
              @foreach($agents_share as $agent_name => $agent_tickets)
                    ['{!! addslashes($agent_name) !!}', {{ $agent_tickets }}],
              @endforeach
            ]);

            var agent_options = {
              title: '{!! addslashes(trans('panichd::admin.index-agents-chart')) !!}',
              legend: {position: 'bottom'}
            };

            var agent_chart = new google.visualization.PieChart(document.getElementById('agentspiechart'));

            agent_chart.draw(agent_data, agent_options);

        }
    </script>
    @endif
@append
