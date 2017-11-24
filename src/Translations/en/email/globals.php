<?php

return [
	// Email subject
	'notify-ticket-category'           => ':name Tickets',
	
	'notify-created-by'                => 'New from :name',
	'notify-closed-by'                 => 'closed by :agent',
	'notify-status-updated-by'         => 'status changed by :agent',
	'notify-ticket-closed-by'          => 'ticket closed by :agent',
	'notify-assigned-to-you-by'        => 'assigned by :agent',
	'notify-new-reply-by'              => 'new reply from :name',
	'notify-new-note-by'               => 'new internal note from :name',	
		
	// Body: General
	'salutation'          => 'Dear Sir or Madam,',
	'complimentary_close' => 'Best regards,',
		
	
	// Body: Resumed message
	'agent_new_ticket' => '<b style="color: #fa4f10;">:agent</b> has created this ticket and assigned it to you.',
	'user_new_ticket'  => '<b style="color: #fa4f10;">:user</b> has created this ticket and it has been assigned to you by category automatic assignation configuration.',
	'closed_ticket'    => '<b style="color: #fa4f10;">:user</b> has closed this ticket.',
	'updated_status'   => '<b style="color: #fa4f10;">:user</b> has changed this ticket status.',
	'updated_agent'    => '<b style="color: #fa4f10;">:user</b> has assigned this ticket to you.',
	
	'added_reply'      => '<b style="color: #fa4f10;">:user</b> has added a reply to this ticket.',
	'added_note'       => '<b style="color: #fa4f10;">:user</b> has added an <b>internal note</b> to this ticket.',
	'new_reply_title'  => 'New reply',
	'new_note_title'   => 'New internal note',
	
	// Body: Ticket link
	'view-ticket-title'   => 'Click here to view your ticket.',
	'view-ticket-text'    => 'View ticket',
];