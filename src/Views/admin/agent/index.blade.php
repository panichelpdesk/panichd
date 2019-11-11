@extends($master)

@section('page')
    {{ trans('panichd::admin.agent-index-title') }}
@stop

@include('panichd::shared.common')

@include('panichd::admin.agent.form_new')

@section('content')
    <div class="card bg-light">
        <div class="card-header">
            <h2>{{ trans('panichd::admin.agent-index-title') }}
                <button class="btn btn-primary float-right" data-toggle="modal" data-target="#CreateAgentModal">{{  trans('panichd::admin.agent-index-create-new') }}</button>
            </h2>
        </div>

        @if ($agents->isEmpty())
            <h3 class="text-center">{{ trans('panichd::admin.agent-index-no-agents') }}</h3>
        @else
            <div id="message"></div>
            <table class="table table-hover">
                <thead>
                    <tr>
                        <td>{{ trans('panichd::admin.table-id') }}</td>
                        <td>{{ trans('panichd::admin.table-name') }}</td>
                        <td>{{ trans('panichd::admin.table-categories') }}</td>
                        <td>{{ trans('panichd::admin.table-remove-agent') }}</td>
                    </tr>
                </thead>
                <tbody>
                @foreach($agents as $agent)
                    <tr>
                        <td>
                            {{ $agent->id }}
                        </td>
                        <td>
                            {{ $agent->name }}
                        </td>
                        <td>
                            @foreach($agent->categories as $category)
                                @if ($category->pivot->autoassign==1)
									<span class="tooltip-info" style="display: inline-block; border: 1px solid {{ $category->color }}; border-width: 0px 0px 1px 0px; color: {{ $category->color }};  margin: 0em 0.5em; padding: 0.1em" title="{{ trans('panichd::admin.table-categories-autoasg-title')}}">
									{{  $category->name }}
									 <span class="fa fa-play-circle" aria-hidden="true"></span>
									</span>
								@else
									<span style="color: {{ $category->color }}">
										{{  $category->name }}
									</span>
								@endif

                            @endforeach

							 <button type="button" class="btn btn-light btn-default btn-xs" data-toggle="modal" data-target="#CategoriesPopupAgent{{ $agent->id }}" style="margin-left: 1em;">{{ trans('panichd::admin.btn-edit')}}</button>

							@include('panichd::admin.agent.form_edit')
                        </td>
                        <td>
                            {!! CollectiveForm::open([
                            'method' => 'DELETE',
                            'route' => [
                                        $setting->grab('admin_route').'.agent.destroy',
                                        $agent->id
                                        ],
                            'id' => "delete-$agent->id"
                            ]) !!}
                            {!! CollectiveForm::submit(trans('panichd::admin.btn-remove'), ['class' => 'btn btn-light btn-default btn-sm']) !!}
                            {!! CollectiveForm::close() !!}
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif
    </div>
@stop

@section('footer')
<script type="text/javascript">
	$(function(){
		$('.jquery_agent_cat').click(function(){
			var checked=$(this).hasClass('checked');
			if (checked){
				$(this).removeClass('checked');
				$('#'+$(this).attr('id')+"_auto").prop('checked',false).prop('disabled','disabled');
			}else{
				$(this).addClass('checked');
				$('#'+$(this).attr('id')+"_auto").prop('checked','checked').prop('disabled',false);
			}
		});
	});
</script>
@append
