<?php

return [
	// Email subject
	'notify-ticket-category'           => ':name Tickets',
	'notify-created-by'                => 'New from :name',
	'notify-closed-by'                 => 'closed by :agent',
	'notify-status-updated-by'         => 'status changed by :agent',
	'notify-ticket-closed-by'          => 'ticket closed by :agent',
	'notify-assigned-to-you-by'        => 'assigned by :agent',

	'notify-new-comment-from'          => 'New comment from ',
	'notify-on'                        => ' on ',
	'notify-updated'                   => ' updated ',
	
		
	// Body: General
	'salutation'          => 'Dear Sir or Madam,',
	'complimentary_close' => 'Best regards,',
		
	
	// Body: Resumed message
	'agent_new_ticket' => '<b style="color: #fa4f10;">:agent</b> has created this ticket and assigned it to you.',
	'user_new_ticket'  => '<b style="color: #fa4f10;">:user</b> has created this ticket and it has been assigned to you by category automatic assignation configuration.',
	'closed_ticket'    => '<b style="color: #fa4f10;">:user</b> has closed this ticket.',
	'updated_status'   => '<b style="color: #fa4f10;">:user</b> has changed this ticket status.',
	'updated_agent'    => '<b style="color: #fa4f10;">:user</b> has assigned this ticket to you.',
	
    'comment'     => 'New Comment',
    'status'      => 'Status Changed',
    'transfer'    => 'Ticket Transferred',
	
	// Body: Ticket link
	'view-ticket-title'   => 'Click here to view your ticket.',
	'view-ticket-text'    => 'View ticket',
];