<div class="modal fade" id="email-edit-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog model-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">{{ trans('ticketit::lang.flash-x') }}</span></button>
                <h4 class="modal-title" id="email-edit-modal-Label">{{ trans('ticketit::admin.category-create-email') }}</h4>
            </div>
            <div class="modal-body">
				<form action="" class="form-horizontal">
					<div class="form-group">
						<div class="col-lg-12">{{ trans('ticketit::admin.category-email-from-info') }}
						</div>
					</div>

					<div class="form-group">						
						{!! CollectiveForm::label('email_scope', trans('ticketit::admin.category-email-from') . trans('ticketit::admin.colon'), ['class' => 'col-lg-3 control-label']) !!}
						
						<div class="col-lg-9"><label id="email_scope_default">{!! CollectiveForm::radio('email_scope','default', !isset($category) || (isset($category) && $category->email == "") ? true : false) !!} {{ trans('ticketit::admin.category-email-default') . trans('ticketit::admin.colon') }}</label>
						@if ($setting->grab('email.account.mailbox') != 'default' && $setting->grab('email.account.name') != 'default')
							<span class="tooltip-info" title="{{ $setting->grab('email.account.name') . '. ' . trans('ticketit::admin.category-email-origin') . trans('ticketit::admin.colon') . trans('ticketit::admin.category-email-origin-tickets') }}">{{ $setting->grab('email.account.mailbox') }} <span class="glyphicon glyphicon-question-sign"></span></span>
						@else
							<span class="tooltip-info" title="{{ config('mail.from.name') . '. ' . trans('ticketit::admin.category-email-origin') . trans('ticketit::admin.colon') . trans('ticketit::admin.category-email-origin-website') }}">{{ config('mail.from.address') }} <span class="glyphicon glyphicon-question-sign"></span></span>
						@endif
						
						</div>	
					</div>
					<div class="form-group">
						<div class="col-lg-9 col-lg-offset-3">
							<label id="email_scope_category">{!! CollectiveForm::radio('email_scope','category', isset($category) && $category->email != "" ? true : false) !!} {{ trans('ticketit::admin.category-email-this') . trans('ticketit::admin.colon') }}</label>
						</div>
					</div>
					
					<div class="form-group">
						{!! CollectiveForm::label('email_name', trans('ticketit::admin.category-email-name') . trans('ticketit::admin.colon'), [
							'class' => 'col-sm-2 col-sm-offset-1 col-lg-offset-3 control-label']) !!}
						
						<div class="col-sm-9 col-lg-7">{!! CollectiveForm::text('email_name', isset($category) && $category->email_name != "" ? $category->email_name : null, [
							'class' => 'form-control jquery_email',
							!isset($category) || (isset($category) && !$errors && $category->email == "") ? 'disabled' : ''
						]) !!}
						</div>
					</div>
					
					<div class="form-group">
						{!! CollectiveForm::label('email', trans('ticketit::admin.category-email') . trans('ticketit::admin.colon'), [
							'class' => 'col-sm-2 col-sm-offset-1 col-lg-offset-3 control-label']) !!}
						
						<div class="col-sm-9 col-lg-7">{!! CollectiveForm::text('email', isset($category) && $category->email != "" ? $category->email : null, [
								'class' => 'form-control jquery_email',
								!isset($category) || (isset($category) && !$errors && $category->email == "") ? 'disabled' : ''
							]) !!}
						</div>
					</div>
					
					<div class="form-group">
						<div class="col-lg-12">{{ trans('ticketit::admin.category-email-reply-to-info') }}
						</div>
					</div>					
					
					<div class="form-group">						
						{!! CollectiveForm::label('email_replies', trans('ticketit::admin.category-email-reply-to') . trans('ticketit::admin.colon'), ['class' => 'col-lg-3 control-label']) !!}
						
						<div class="col-lg-9"><label id="email_replies_0">{!! CollectiveForm::radio('email_replies',0, !isset($category) || (isset($category) && $category->email_replies != 1)  ? true : false) !!} {{ trans('ticketit::admin.category-email-default') . trans('ticketit::admin.colon') }}</label>
						@if ($setting->grab('email.account.mailbox') != 'default' && $setting->grab('email.account.name') != 'default')
							<span class="tooltip-info" title="{{ $setting->grab('email.account.name') . '. ' . trans('ticketit::admin.category-email-origin') . trans('ticketit::admin.colon') . trans('ticketit::admin.category-email-origin-tickets') }}">{{ $setting->grab('email.account.mailbox') }} <span class="glyphicon glyphicon-question-sign"></span></span>
						@else
							<span class="tooltip-info" title="{{ config('mail.from.name') . '. ' . trans('ticketit::admin.category-email-origin') . trans('ticketit::admin.colon') . trans('ticketit::admin.category-email-origin-website') }}">{{ config('mail.from.address') }} <span class="glyphicon glyphicon-question-sign"></span></span>
						@endif
						
						</div>	
					</div>
					<div class="form-group">
						<div class="col-lg-9 col-lg-offset-3">
							<label id="email_replies_1">{!! CollectiveForm::radio('email_replies',1, isset($category) && $category->email_replies == 1 ? true : false) !!} {{ trans('ticketit::admin.category-email-this') }}</label> ({{ trans('ticketit::admin.category-email-reply-this-info') }})
						</div>
					</div>
					
					
					
					
				</form>			

				<div class="modal-footer">					
					{!! CollectiveForm::button(trans('ticketit::admin.btn-change'), ['id'=>'jquery_popup_email_submit', 'class' => 'btn btn-primary']) !!}
				</div>
				
			</div>
		</div>
	</div>
</div>