@extends($master)
@section('page', trans('ticketit::lang.show-ticket-title') . trans('ticketit::lang.colon') . $ticket->subject)
@section('content')
        @include('ticketit::shared.header')
        @include('ticketit::tickets.partials.ticket_body')
        <br>
        <h2>{{ trans('ticketit::lang.comments') }}</h2>
        @include('ticketit::tickets.partials.comments')
        {!! $comments->render() !!}
        @include('ticketit::tickets.partials.comment_form')
@endsection

@section('footer')
    <script>
		var category_id=<?=$ticket->category_id;?>;
        $(document).ready(function() {
            $( ".deleteit" ).click(function( event ) {
                event.preventDefault();
                if (confirm("{!! trans('ticketit::lang.show-ticket-js-delete') !!}" + $(this).attr("node") + " ?"))
                {
                    var form = $(this).attr("form");
                    $("#" + form).submit();
                }

            });
            $('#category_id').change(function(){				
				// Update agent list
				var loadpage = "{!! route($setting->grab('main_route').'agentselectlist') !!}/" + $(this).val() + "/{{ $ticket->id }}";
                $('#agent_id').load(loadpage);
				
				// Update tag list				
				$('#jquery_select2_container .select2-container').hide();
				$('#jquery_tag_category_'+$(this).val()).next().show();
            });
            $('#confirmDelete').on('show.bs.modal', function (e) {
                $message = $(e.relatedTarget).attr('data-message');
                $(this).find('.modal-body p').text($message);
                $title = $(e.relatedTarget).attr('data-title');
                $(this).find('.modal-title').text($title);

                // Pass form reference to modal for submission on yes/ok
                var form = $(e.relatedTarget).closest('form');
                $(this).find('.modal-footer #confirm').data('form', form);
            });

            <!-- Form confirm (yes/ok) handler, submits form -->
            $('#confirmDelete').find('.modal-footer #confirm').on('click', function(){
                $(this).data('form').submit();
            });
        });
    </script>
    @include('ticketit::tickets.partials.summernote')
	@include('ticketit::tickets.partials.tags_footer_script')
@append
