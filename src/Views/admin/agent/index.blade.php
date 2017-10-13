@extends($master)

@section('page')
    {{ trans('ticketit::admin.agent-index-title') }}
@stop

@include('ticketit::shared.common')

@include ('ticketit::admin.agent.form_new')

@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">
            <h2>{{ trans('ticketit::admin.agent-index-title') }}
                <button class="btn btn-primary pull-right" data-toggle="modal" data-target="#CreateAgentModal">{{  trans('ticketit::admin.btn-create-new-agent') }}</button>
            </h2>
        </div>

        @if ($agents->isEmpty())
            <h3 class="text-center">{{ trans('ticketit::admin.agent-index-no-agents') }}
                {!! link_to_route($setting->grab('admin_route').'.agent.create', trans('ticketit::admin.agent-index-create-new')) !!}
            </h3>
        @else
            <div id="message"></div>
            <table class="table table-hover">
                <thead>
                    <tr>
                        <td>{{ trans('ticketit::admin.table-id') }}</td>
                        <td>{{ trans('ticketit::admin.table-name') }}</td>
                        <td>{{ trans('ticketit::admin.table-categories') }}</td>
						<td>{{ trans('ticketit::admin.table-categories-autoassign') }}</td>                        
                        <td>{{ trans('ticketit::admin.table-remove-agent') }}</td>
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
                                <span style="color: {{ $category->color }}">
                                    {{  $category->name }}
                                </span>
                            @endforeach
							
							<button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#CategoriesPopupAgent{{ $agent->id }}">{{ trans('ticketit::admin.btn-edit')}}</button>
							
							@include ('ticketit::admin.agent.form_edit')
                        </td>
						<td>
                            @foreach($agent->categories as $category)
								@if ($category->pivot->autoassign==1)
									<span style="color: {{ $category->color }}">
										{{  $category->name }}
									</span>
								@endif
								
                            @endforeach
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
                            {!! CollectiveForm::submit(trans('ticketit::admin.btn-remove'), ['class' => 'btn btn-danger']) !!}
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
