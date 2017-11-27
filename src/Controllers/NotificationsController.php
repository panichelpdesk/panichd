<?php

namespace Kordy\Ticketit\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Kordy\Ticketit\Helpers\LaravelVersion;
use Kordy\Ticketit\Models\Category;
use Kordy\Ticketit\Models\Comment;
use Kordy\Ticketit\Models\Setting;
use Kordy\Ticketit\Models\Ticket;

class NotificationsController extends Controller
{
    protected $category;
	protected $subject;
	
	public function __construct(Category $category)
	{
		$this->category = $category;
		$this->subject = '['.trans('ticketit::email/globals.notify-ticket-category', ['name' => $category->name]).'] #';
	}
	
	public function newTicket(Ticket $ticket)
    {
        $notification_owner = auth()->user();
        $template = 'ticketit::emails.new_ticket';
        
		// Affects only agent notification.
		$subject = $this->subject.$ticket->id.' '.trans('ticketit::email/globals.notify-created-by', ['name' => $ticket->user->name] ).trans('ticketit::lang.colon').$ticket->subject;
		
		$data = [
            'ticket'             => serialize($ticket),
            'notification_owner' => serialize($notification_owner),
        ];

        $this->sendNotification($template, $data, $ticket, $notification_owner, $subject, 'newTicket');
    }

    public function ticketStatusUpdated(Ticket $ticket, Ticket $original_ticket)
    {
        $notification_owner = auth()->user();
        $template = 'ticketit::emails.updated_ticket';
		$subject = $this->subject.$ticket->id.' ';
		if (!strtotime($original_ticket->completed_at) and strtotime($ticket->completed_at)) {
			$subject .= trans('ticketit::email/globals.notify-closed-by', ['agent' => $notification_owner->name]).trans('ticketit::lang.colon').$ticket->subject;	
			$notification_type = 'close';
		}else{
			$subject .= trans('ticketit::email/globals.notify-status-updated-by', ['agent' => $notification_owner->name]).trans('ticketit::lang.colon').$ticket->subject;
			$notification_type = 'status';
		}
        
		$data = [
            'ticket'             => serialize($ticket),
            'notification_owner' => serialize($notification_owner),
            'original_ticket'    => serialize($original_ticket),
			'notification_type'             => $notification_type
        ];
        
        $this->sendNotification($template, $data, $ticket, $notification_owner, $subject, $notification_type);
    }

    public function ticketAgentUpdated(Ticket $ticket, Ticket $original_ticket)
    {
        $notification_owner = auth()->user();
        $template = 'ticketit::emails.updated_ticket';
        $subject = $this->subject.$ticket->id.' '.trans('ticketit::email/globals.notify-assigned-to-you-by', ['agent' => $notification_owner->name]).trans('ticketit::lang.colon').$ticket->subject;
		$data = [
            'ticket'             => serialize($ticket),
            'notification_owner' => serialize($notification_owner),
            'original_ticket'    => serialize($original_ticket),
			'notification_type'             => 'agent'
        ];

        $this->sendNotification($template, $data, $ticket, $notification_owner, $subject, 'agent');
    }

	public function newComment(Comment $comment)
    {
        if (in_array($comment->type, ['reply', 'note'])){
			$ticket = $comment->ticket;
			$notification_owner = $comment->user;
			$template = 'ticketit::emails.comment';
			$subject = $this->subject.$ticket->id.' '
				.trans('ticketit::email/globals.'.($comment->type == 'reply' ? 'notify-new-reply-by' : 'notify-new-note-by'), ['name' => $comment->user->name] )
				.trans('ticketit::lang.colon').$ticket->subject;
			$data = [
				'comment' => serialize($comment),
				'ticket' => serialize($ticket),
				'notification_owner' => serialize($notification_owner),
				'notification_type' => $comment->type
			];
		
			$this->sendNotification($template, $data, $ticket, $notification_owner, $subject, 'comment_'.$comment->type);
		}        
    }

	/**
     * Send new email notification.
     *
	 */
	public function notificationResend(Request $request)
	{
		$comment=Comment::findOrFail($request->input('comment_id'));
		$ticket=$comment->ticket;
		$notification_owner = $comment->user;
		$template = 'ticketit::emails.comment';
		$subject = trans('ticketit::lang.email-resend-abbr') . trans('ticketit::lang.colon') . $this->subject.$ticket->id . ' ' . trans('ticketit::email/globals.notify-new-note-by', ['name' => $comment->user->name]) . trans('ticketit::lang.colon') . $ticket->subject;
			// trans('ticketit::email/globals.notify-new-comment-from').$notification_owner->name.trans('ticketit::email/globals.notify-on').$ticket->subject;
		$data = [
			'comment' => serialize($comment), 
			'ticket' => serialize($ticket),
			'notification_owner' => serialize($notification_owner),
			'notification_type' => $comment->type
		];
		
		$a_to = [];
		
		if ($request->has('to_agent')){
			$a_to[] = [
				'recipient' => $ticket->agent
			];
		}
		if ($request->has('to_owner') and (!$request->has('to_agent') or ($request->has('to_agent') and $ticket->owner->email!=$ticket->agent->email))){
			$a_to[] = [
				'recipient' => $ticket->owner
			];
		}
		
		$this->sendNotification_exec($a_to, $template, $data, $subject);
		
		return back()->with('status','Notificacions reenviades correctament');
	}
	
	
    /**
     * Prepare notification recipients and call sendNotification_exec() to send messages.
     *
     * @param string $template
     * @param array  $data
     * @param object $ticket
     * @param object $notification_owner
     */
    public function sendNotification($template, $data, $ticket, $notification_owner, $subject, $notification_type)
    {
        // Email recipients
		$a_to=[];

		// Email to ticket->agent
		if ($ticket->agent->email != $notification_owner->email){
			$a_to[] = [
				'recipient' => $ticket->agent,
			];
		}
		
		// Email to ticket->owner
		if ($ticket->owner->email != $notification_owner->email and $ticket->agent->email != $ticket->owner->email){
			if (in_array($notification_type,['comment_reply','close','status'])){
				$a_to[] = [
					'recipient' => $ticket->owner
				];
			}elseif($notification_type=="newTicket"){
				if (Setting::grab('departments_notices_feature') and ($ticket->owner->ticketit_department == '0' || $ticket->owner->ticketit_department != "" )){
					$a_to[] = [
						'recipient' => $ticket->owner,
						'subject' => $ticket->subject . ' [' .  trans('ticketit::lang.ticket') . ' ' . trans('ticketit::lang.table-id') . $ticket->id .']',
						'template' => Setting::grab('email.owner.newticket.template')
					];
				}
			}
		}		

		$this->sendNotification_exec($a_to, $template, $data, $subject);
        
    }
	
	/**
     * Send email notifications from specified mailbox to to other involved users.
     *
     * @param string $template
     * @param array  $data
     * @param object $ticket
     */
    public function sendNotification_exec($a_to, $template, $data, $subject)
    {
		$email_replyto = new \stdClass();
		
		if(Setting::grab('email.account.name') != "default" and Setting::grab('email.account.mailbox') != "default"){
			// ReplyTo: Use tickets email account
			$email_replyto->email_name = Setting::grab('email.account.name');
			$email_replyto->email = Setting::grab('email.account.mailbox');
		}else{
			// ReplyTo: Use Laravel general account
			$email_replyto->email_name = env('MAIL_FROM_NAME');
			$email_replyto->email = env('MAIL_FROM_ADDRESS');
		}
		
		// From: Use same as ReplyTo
		$email_from = $email_replyto;
		
		if ($this->category->email_name != "" and $this->category->email != ""){
			// From: Use category email account
			$email_from->email_name = $this->category->email_name;
			$email_from->email = $this->category->email;
			
			if ($this->category->email_replies == 1){
				// ReplyTo: Use category email account
				$email_replyto = $email_from;
			}
		}
		
		// Add Email From to template
		$data = array_merge ($data, [ 'email_from' => serialize($email_from) ]);
		
		// Send emails
		if (LaravelVersion::lt('5.4')) {
            foreach ($a_to as $to){
				// Pass recipient user object to every generated notification email
				$data = array_merge ($data, ['recipient' => $to['recipient']]);
				
				$mail_subject = isset($to['subject']) ? $to['subject'] : $subject;
				$mail_template = isset($to['template']) ? $to['template'] : $template;
				
				$mail_callback = function ($m) use ($to, $email_from, $email_replyto, $mail_subject) {
					$m->to($to['recipient']->email, $to['recipient']->name);
					
					$m->from($email_from->email, $email_from->email_name);
					$m->replyTo($email_replyto->email, $email_replyto->email_name);

					$m->subject($mail_subject);
				};

				if (Setting::grab('queue_emails') == 'yes') {
					Mail::queue($mail_template, $data, $mail_callback);
				} else {
					Mail::send($mail_template, $data, $mail_callback);
				}
			}
			
        } elseif (LaravelVersion::min('5.4')) {
            foreach ($a_to as $to){
				// Pass recipient user object to every generated notification email
				$data = array_merge ($data, ['recipient' => $to['recipient']]);
				
				$mail_subject = isset($to['subject']) ? $to['subject'] : $subject;
				$mail_template = isset($to['template']) ? $to['template'] : $template;
				
				$mail = new \Kordy\Ticketit\Mail\TicketitNotification($mail_template, $data, $email_from, $email_replyto, $subject);

				if (Setting::grab('queue_emails') == 'yes') {
					Mail::to($to['recipient'])->queue($mail);
				} else {
					Mail::to($to['recipient'])->send($mail);
				}
			}
			
			
        }
	}
}