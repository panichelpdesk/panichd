<div class="modal fade" id="ticket-edit-modal" tabindex="-1" role="dialog" aria-labelledby="ticket-edit-modal-Label">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            {!! CollectiveForm::model($ticket, [
                 'route' => [$setting->grab('main_route').'.update', $ticket->id],
                 'method' => 'PATCH',
                 'class' => 'form-horizontal'
             ]) !!}
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">{{ trans('ticketit::lang.flash-x') }}</span></button>
                <h4 class="modal-title" id="ticket-edit-modal-Label">{{ $ticket->subject }}</h4>
            </div>
            <div class="modal-body">

				
				<div class="row">
					<div class="col-sm-4">
						
						<div class="form-group">
							{!! CollectiveForm::label('status_id', trans('ticketit::lang.subject') . trans('ticketit::lang.colon'), [
									'class' => 'col-sm-12 control-label',
									'style' => 'text-align: left'
								]) !!}
							<div class="col-sm-12">
								{!! CollectiveForm::text('subject', $ticket->subject, ['class' => 'form-control', 'required']) !!}
							</div>
						</div>
						
						<div class="form-group">
							{!! CollectiveForm::label('status_id', trans('ticketit::lang.status') . trans('ticketit::lang.colon'), [
								'class' => 'col-lg-6 control-label'
							]) !!}
							<div class="col-lg-6">
								{!! CollectiveForm::select('status_id', $status_lists, $ticket->status_id, ['class' => 'form-control']) !!}
							</div>
						</div>
						
						<div class="form-group">
							{!! CollectiveForm::label('priority_id', trans('ticketit::lang.priority') . trans('ticketit::lang.colon'), ['class' => 'col-lg-6 control-label']) !!}
							<div class="col-lg-6">
								{!! CollectiveForm::select('priority_id', $priority_lists, $ticket->priority_id, ['class' => 'form-control']) !!}
							</div>
						</div>
						
						<div class="form-group">
							{!! CollectiveForm::label('category_id',  trans('ticketit::lang.category') . trans('ticketit::lang.colon'), [
								'class' => 'col-lg-6 control-label'
							]) !!}
							<div class="col-lg-6">
								{!! CollectiveForm::select('category_id', $category_lists, $ticket->category_id, ['class' => 'form-control']) !!}
								</div>
						</div>
						<div class="form-group">
							{!! CollectiveForm::label('agent_id', trans('ticketit::lang.agent') . trans('ticketit::lang.colon'), [
								'class' => 'col-lg-6 control-label'
							]) !!}
							<div class="col-lg-6">
								{!! CollectiveForm::select(
									'agent_id',
									$agent_lists,
									$ticket->agent_id,
									['class' => 'form-control']) !!}
							</div>
						</div>
						
						<div class="form-group">						
							{!! CollectiveForm::label('tags', trans('ticketit::lang.tags') . trans('ticketit::lang.colon'), [
								'class' => 'col-sm-12 control-label',
								'style' => 'text-align: left'
							]) !!}							
							<div id="jquery_select2_container" class="col-sm-12">
							
							<?php $categories = $category_lists; ?>
							@include('ticketit::tickets.partials.tags_menu')
							
							</div>
						</div>
					</div>
					<div class="col-sm-8">
					                    
						<div class="form-group">
							{!! CollectiveForm::label('content', trans('ticketit::lang.description') . trans('ticketit::lang.colon'), [
								'class' => 'col-sm-12 control-label',
								'style' => 'text-align: left'
							]) !!}
							<div class="col-sm-12">{!! CollectiveForm::textarea('content', $ticket->html, [
									'class' => 'form-control summernote-editor', 'rows' => '5', 'required'
								]) !!}</div>
						</div>
						@if ($u->currentLevel() > 1)
							<div class="form-group">
								{!! CollectiveForm::label('intervention', trans('ticketit::lang.intervention') . trans('ticketit::lang.colon'), [
									'class' => 'col-sm-12 control-label',
									'style' => 'text-align: left'
								]) !!}
								<div class="col-sm-12">{!! CollectiveForm::textarea('intervention', $ticket->intervention_html, [
									'class' => 'form-control summernote-editor', 'rows' => '5'
								]) !!}</div>
							</div>
						@endif
					                    {{--@endif--}}
					
					                    

					</div>
				</div>

                


                    </div>
                    

                    

					
								
					
					<div class="clearfix"></div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('ticketit::lang.btn-close') }}</button>
                        {!! CollectiveForm::submit(trans('ticketit::lang.btn-submit'), ['class' => 'btn btn-primary']) !!}
                    </div>
                    {!! CollectiveForm::close() !!}
        </div>
    </div>
</div>