@extends($master)

@section('page')
    {{ trans('panichd::admin.category-index-title') }}
@stop

@include('panichd::shared.common')

@section('content')
    <div class="card bg-light">
        <div class="card-header">
            <h2>{{ trans('panichd::admin.category-index-title') }}
                {!! link_to_route(
                                    $setting->grab('admin_route').'.category.create',
                                    trans('panichd::admin.btn-create-new-category'), null,
                                    ['class' => 'btn btn-primary float-right'])
                !!}
            </h2>
        </div>

        @if ($categories->isEmpty())
            <h3 class="text-center">{{ trans('panichd::admin.category-index-no-categories') }}
                {!! link_to_route($setting->grab('admin_route').'.category.create', trans('panichd::admin.category-index-create-new')) !!}
            </h3>
        @else
            <div id="message"></div>
            <table class="table table-hover">
                <thead>
                    <tr>
                        <td>{{ trans('panichd::admin.table-id') }}</td>
                        <td>{{ trans('panichd::admin.table-name') }}</td>
						<td>{{ trans('panichd::admin.category-index-email') }}</td>
                        <td>{{ trans('panichd::admin.table-create-level') }}</td>
                        <td>{{ trans('panichd::admin.category-index-reasons') }}</td>
						<td>{{ trans('panichd::admin.category-index-tags') }}</td>
						<td>{{ trans('panichd::admin.table-action') }}</td>
                    </tr>
                </thead>
                <tbody>

				@foreach($categories as $category)
                    <tr>
                        <td style="vertical-align: middle">
                            {{ $category->id }}
                        </td>
                        <td style="color: {{ $category->color }}; vertical-align: middle">
                            {{ $category->name }}
                        </td>
						<td style="vertical-align: middle">
                            <span class="tooltip-info" data-toggle="tooltip" data-placement="bottom" title="{{ trans('panichd::admin.category-email-origin') . trans('panichd::admin.colon') }}
							@if ($category->email != "")
								{{ trans('panichd::admin.category-email-origin-category') }}">{{ $category->email_name }} &lt;{{ $category->email }}>
							@elseif($setting->grab('email.account.name') != 'default' && $setting->grab('email.account.mailbox') != 'default')
								{{ trans('panichd::admin.category-email-origin-tickets') }}">{{ $setting->grab('email.account.name') }} &lt;{{ $setting->grab('email.account.mailbox') }}>
							@else
								{{ trans('panichd::admin.category-email-origin-website') }}">{{ config('mail.from.name') }} &lt;{{ config('mail.from.address') }}>
							@endif
							 <span class="fa fa-question-circle"></span></span>
                        </td>
						<td style="vertical-align: middle">
						{{ trans('panichd::admin.level-'.$category->create_level) }}
						</td>
                        <td>
							@if ($category->has('closingReasons'))
								<ul>
								@foreach ($category->closingReasons as $reason)
									<li>{{ $reason->text }}</li>
								@endforeach
								</ul>
							@endif
						</td>
						<td style="vertical-align: middle">
						@if ($category->has('tags'))
							@foreach ($category->tags as $tag)
								<button class="btn btn-light btn-tag btn-xs" style="pointer-events: none; color: {{$tag->text_color}}; background: {{$tag->bg_color}}">{{$tag->name}}</button>
							@endforeach
						@endif
						</td>
						<td>
                            {!! link_to_route(
                                $setting->grab('admin_route').'.category.edit', trans('panichd::admin.btn-edit'), $category->id,
                                ['class' => 'btn btn-light btn-default'] )
                             !!}

                            {!! link_to_route(
                                $setting->grab('admin_route').'.category.destroy', trans('panichd::admin.btn-delete'), $category->id,
                                [
                                'class' => 'btn btn-light btn-default deleteit',
                                'form' => "delete-$category->id",
                                "node" => $category->name
                                ])
                            !!}
                            {!! CollectiveForm::open([
                                            'method' => 'DELETE',
                                            'route' => [
                                                        $setting->grab('admin_route').'.category.destroy',
                                                        $category->id
                                                        ],
                                            'id' => "delete-$category->id"
                                            ])
                            !!}
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
    <script>
        $( ".deleteit" ).click(function( event ) {
            event.preventDefault();
            if (confirm("{!! trans('panichd::admin.category-index-js-delete') !!}" + $(this).attr("node") + " ?"))
            {
                var form = $(this).attr("form");
                $("#" + form).submit();
            }

        });
    </script>
@append
