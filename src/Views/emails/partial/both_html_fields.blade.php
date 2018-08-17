<b>{{ trans('panichd::lang.description') }}</b>
<table border="0" cellpadding="10" cellspacing="0" style="border: 1px solid #ddd; border-radius: 5px;"><tr>
	<td>@include('panichd::emails.partial.html_field', ['html_field' => $ticket->html, 'ticket' => $ticket, 'field' => 'description'])</td>
</tr></table><br /><br />
@if ($ticket->intervention_html)
	<b>{{ trans('panichd::lang.intervention') }}</b>
	<table border="0" cellpadding="10" cellspacing="0" style="border: 1px solid #ddd; border-radius: 5px;"><tr>
		<td>@include('panichd::emails.partial.html_field', ['html_field' => $ticket->intervention_html, 'ticket' => $ticket, 'field' => 'intervention'])</td>
	</tr></table><br /><br />
@endif