<b>{{ trans('ticketit::lang.description') }}</b>
<table border="0" cellpadding="10" cellspacing="0" style="border: 1px solid #ddd; border-radius: 5px;"><tr>
	<td>@include ('ticketit::emails.templates.html_field', ['html_field' => $ticket->html])</td>
</tr></table><br /><br />
@if ($ticket->intervention_html)
	<b>{{ trans('ticketit::lang.intervention') }}</b>
	<table border="0" cellpadding="10" cellspacing="0" style="border: 1px solid #ddd; border-radius: 5px;"><tr>
		<td>@include ('ticketit::emails.templates.html_field', ['html_field' => $ticket->intervention_html])</td>
	</tr></table><br /><br />
@endif