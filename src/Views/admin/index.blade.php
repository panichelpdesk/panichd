@extends($master)

@section('page')
    {{ trans('panichd::admin.index-title') }}
@stop

@include('panichd::shared.common')

@section('content')
    @if($tickets_count)
        <?php
            $a_cards = [
                ['class' => 'bg-light', 'icon' => 'book', 'body' => '<h2>' . $tickets_count . '<small>' . trans('panichd::admin.index-total-tickets') . '</small></h2>'],
                ['class' => 'text-white bg-info', 'icon' => 'certificate', 'body' => '<h2 class="text-white">' . $a_tickets_count['newest'] . '<small>' . trans('panichd::admin.index-newest-tickets') . '</small></h2>'],
                ['class' => 'text-white bg-warning', 'icon' => 'file', 'body' => '<h2 class="text-white">' . $a_tickets_count['active'] . '<small>' . trans('panichd::admin.index-active-tickets') . '</small></h2>'],
                ['class' => 'text-white bg-success', 'icon' => 'check-circle', 'body' => '<h2 class="text-white">' . $a_tickets_count['complete'] . '<small>' . trans('panichd::admin.index-complete-tickets') . '</small></h2>'],
            ];

        ?>
        <div class="row mb-2">
            @foreach ($a_cards as $card)
                <div class="col-sm-6 col-lg-3">
                    <div class="media {{ $card['class'] }}">
                        <i class="mr-3 fa fa-{{ $card['icon'] }}" style="font-size: 2.5em; margin: 0.5em"></i>
                        <div class="media-body align-self-center mr-2">
                            {!! $card['body'] !!}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="card bg-light">
                    <div class="card-header">
                      <div class="float-right">
                          <div class="btn-group">
                              <button type="button" class="btn btn-light btn-xs dropdown-toggle" data-toggle="dropdown">
                                  {{ trans('panichd::admin.index-periods') }}
                                  <span class="caret"></span>
                              </button>
                              <ul class="dropdown-menu" role="menu">
                                  <a class="dropdown-item" href="{{ action('\PanicHD\PanicHD\Controllers\DashboardController@index', 2) }}">
                                      {{ trans('panichd::admin.index-3-months') }}
                                  </a>
                                  <a class="dropdown-item" href="{{ action('\PanicHD\PanicHD\Controllers\DashboardController@index', 5) }}">
                                      {{ trans('panichd::admin.index-6-months') }}
                                  </a>
                                  <a class="dropdown-item" href="{{ action('\PanicHD\PanicHD\Controllers\DashboardController@index', 11) }}">
                                      {{ trans('panichd::admin.index-12-months') }}
                                  </a>
                                  </li>
                              </ul>
                          </div>
                      </div>
                      <i class="fa fa-chart-bar fa-fw"></i> {{ trans('panichd::admin.index-performance-indicator') }}

                    </div>
                    <div class="card-body">
                        <div id="curve_chart" style="width: 100%; height: 350px"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="card bg-light">
                            <div class="card-header">
                                {{ trans('panichd::admin.index-tickets-share-per-category') }}
                            </div>
                            <div class="card-body">
                                <div id="catpiechart" style="width: auto; height: 350;"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card bg-light">
                            <div class="card-header">
                                {{ trans('panichd::admin.index-tickets-share-per-agent') }}
                            </div>
                            <div class="card-body">
                                <div id="agentspiechart" style="width: auto; height: 350;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                {{--<h4><i class="fa fa-info-circle"></i> Tickets Activities</h4>--}}
                {{--<hr />--}}
                <ul class="nav nav-tabs nav-justified" class="nav-item">
                    <li class="nav-item {{$active_tab == "cat" ? "active" : ""}}">
                        <a class="nav-link active" data-toggle="pill" role="tab" href="#information-panel-categories">
                            <i class="fa fa-folder"></i>
                            <small>{{ trans('panichd::admin.index-categories') }}</small>
                        </a>
                    </li>
                    <li class="nav-item {{$active_tab == "agents" ? "active"  : ""}}">
                        <a class="nav-link" data-toggle="pill" role="tab" href="#information-panel-agents">
                            <i class="fa fa-user"></i>
                            <small>{{ trans('panichd::admin.index-agents') }}</small>
                        </a>
                    </li>
                    <li class="nav-item {{$active_tab == "users" ? "active" : ""}}">
                        <a class="nav-link" data-toggle="pill" role="tab" href="#information-panel-users">
                            <i class="fa fa-user"></i>
                            <small>{{ trans('panichd::admin.index-users') }}</small>
                        </a>
                    </li>
                </ul>
                <br>
                <div class="tab-content">
                    <ul id="information-panel-categories" class="list-group tab-pane fade {{$active_tab == "cat" ? "show active" : ""}}"  role="tabpanel">
                        <li class="list-group-item d-flex">
                            <span class="mr-auto">{{ trans('panichd::admin.index-category') }}
                                <span class="badge">{{ trans('panichd::admin.index-total') }}</span>
                            </span>
                            <span class="text-muted small">

								<?php
									$counter_lists_text = trans('panichd::lang.newest-tickets-adjective') . ' - ' . trans('panichd::lang.active-tickets-adjective') . ' - ' . trans('panichd::lang.complete-tickets-adjective');
								?>

								<em>{{ $counter_lists_text }}</em>
                            </span>
                        </li>
                        @foreach($categories as $category)
                            <li class="list-group-item d-flex">
								<span style="color: {{ $category->color }}">{{ $category->name }} </span><span class="badge align-self-center ml-1 mr-auto"  style="color: white; background-color: {{ $category->color }}">{{ $category->tickets()->count() }}</span>

								<span class="small">
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
                        {!! $categories->links('pagination::bootstrap-4') !!}
                    </ul>
                    <ul id="information-panel-agents" class="list-group tab-pane fade {{$active_tab == "agents" ? "show active" : ""}}" role="tabpanel">
                        <li class="list-group-item">
                            <span class="mr-auto">{{ trans('panichd::admin.index-agent') }}
                                <span class="badge">{{ trans('panichd::admin.index-total') }}</span>
                            </span>
                            <span class="text-muted small">
                                <em>{{ $counter_lists_text }}</em>
                            </span>
                        </li>
                        @foreach($agents as $agent)
                            <li class="list-group-item d-flex">
                                <?php $agent_text = $agent->name . ' <span class="badge">' . $agent->ticketsAsAgent()->count() . '</span>'; ?>
                             <span class="mr-auto">
                              @if ($setting->grab('user_route') != 'disabled')
              									<a href="{{ route($setting->grab('user_route'), ['user' => $agent->id]) }}">{!! $agent_text !!}</a>
              								@else
              									<span>{!! $agent_text !!}</span>
              								@endif
                            </span>
                            <span class="small">
									<?php
										$a_button = [
											'newest' => $agent->ticketsAsAgent()->newest()->count() . ' ' . trans('panichd::lang.newest-tickets-adjective'),
											'active' => $agent->ticketsAsAgent()->active()->count() . ' ' . trans('panichd::lang.active-tickets-adjective'),
											'complete' => $agent->ticketsAsAgent()->complete()->count() . ' ' . trans('panichd::lang.complete-tickets-adjective')
										];
									?>

									@if ($agent->ticketsAsAgent()->newest()->count() == 0)
										{{ $a_button['newest'] }}
									@else
										<a href="{{ route($setting->grab('main_route') . '-filteronly', ['filter' => 'agent', 'value' => $agent->id, 'list' => 'newest']) }}" class="btn btn-light btn-xs" title="{{ trans('panichd::admin.index-view-agent-tickets', ['list' =>trans('panichd::lang.newest-tickets-adjective')]) }}">
										{{ $a_button['newest'] }}
										</a>
									@endif
									 -
									@if ($agent->ticketsAsAgent()->active()->count() == 0)
										{{ $a_button['active'] }}
									@else
										<a href="{{ route($setting->grab('main_route') . '-filteronly', ['filter' => 'agent', 'value' => $agent->id, 'list' => 'active']) }}" class="btn btn-light btn-xs" title="{{ trans('panichd::admin.index-view-agent-tickets', ['list' =>trans('panichd::lang.active-tickets-adjective')]) }}">
										{{ $a_button['active'] }}
										</a>
									@endif
									 -
									@if ($agent->ticketsAsAgent()->complete()->count() == 0)
										{{ $a_button['complete'] }}
									@else
										<a href="{{ route($setting->grab('main_route') . '-filteronly', ['filter' => 'agent', 'value' => $agent->id, 'list' => 'complete']) }}" class="btn btn-light btn-xs" title="{{ trans('panichd::admin.index-view-agent-tickets', ['list' =>trans('panichd::lang.complete-tickets-adjective')]) }}">
										{{ $a_button['complete'] }}
										</a>
									@endif
                                </span>
                            </li>
                        @endforeach
                        {!! $agents->links('pagination::bootstrap-4') !!}
                    </ul>
                    <ul id="information-panel-users" class="list-group tab-pane fade {{ $active_tab == "users" ? "show active" : "" }}" role="tabpanel">
                        <li class="list-group-item d-flex">
                            <span class="mr-auto">{{ trans('panichd::admin.index-user') }}
                                <span class="badge">{{ trans('panichd::admin.index-total') }}</span>
                            </span>
                            <span class="text-muted small">
                                <em>{{ $counter_lists_text }}</em>
                            </span>
                        </li>
                        @foreach($users as $user)
                            <li class="list-group-item d-flex">
                                <?php $user_text = $user->name . ' <span class="badge">' . $user->ticketsAsOwner()->count() . '</span>'; ?>
                              <span class="mr-auto">
                                @if ($setting->grab('user_route') != 'disabled')
                									<a href="{{ route($setting->grab('user_route'), ['user' => $user->id]) }}">{!! $user_text !!}</a>
                								@else
                									<span>{!! $user_text !!}</span>
                								@endif
                              </span>

                                <span class="small">
                                    <?php
										$a_button = [
											'newest' => $user->ticketsAsOwner()->newest()->count() . ' ' . trans('panichd::lang.newest-tickets-adjective'),
											'active' => $user->ticketsAsOwner()->active()->count() . ' ' . trans('panichd::lang.active-tickets-adjective'),
											'complete' => $user->ticketsAsOwner()->complete()->count() . ' ' . trans('panichd::lang.complete-tickets-adjective')
										];
									?>

									@if ($user->ticketsAsOwner()->newest()->count() == 0)
										{{ $a_button['newest'] }}
									@else
										<a href="{{ route($setting->grab('main_route') . '-filteronly', ['filter' => 'owner', 'value' => $user->id, 'list' => 'newest']) }}" class="btn btn-light btn-xs" title="{{ trans('panichd::admin.index-view-user-tickets', ['list' =>trans('panichd::lang.newest-tickets-adjective')]) }}">
										{{ $a_button['newest'] }}
										</a>
									@endif
									 -
									@if ($user->ticketsAsOwner()->active()->count() == 0)
										{{ $a_button['active'] }}
									@else
										<a href="{{ route($setting->grab('main_route') . '-filteronly', ['filter' => 'owner', 'value' => $user->id, 'list' => 'active']) }}" class="btn btn-light btn-xs" title="{{ trans('panichd::admin.index-view-user-tickets', ['list' =>trans('panichd::lang.active-tickets-adjective')]) }}">
										{{ $a_button['active'] }}
										</a>
									@endif
									 -
									@if ($user->ticketsAsOwner()->complete()->count() == 0)
										{{ $a_button['complete'] }}
									@else
										<a href="{{ route($setting->grab('main_route') . '-filteronly', ['filter' => 'owner', 'value' => $user->id, 'list' => 'complete']) }}" class="btn btn-light btn-xs" title="{{ trans('panichd::admin.index-view-user-tickets', ['list' =>trans('panichd::lang.complete-tickets-adjective')]) }}">
										{{ $a_button['complete'] }}
										</a>
									@endif
                                </span>
                            </li>
                        @endforeach
                        {!! $users->links('pagination::bootstrap-4') !!}
                    </ul>
                </div>
            </div>
        </div>
    @else
        <div class="card bg-light">
            <div class="card-body text-center">
                {{ trans('panichd::admin.index-empty-records') }}
            </div>
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
