<?php

namespace PanicHD\PanicHD\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use PanicHD\PanicHD\Helpers\LaravelVersion;
use PanicHD\PanicHD\Models\Category;
use PanicHD\PanicHD\Models\Comment;
use PanicHD\PanicHD\Models\Setting;
use PanicHD\PanicHD\Models\Ticket;

class NotificationsController extends Controller
{
    protected $category;
	protected $subject;
	
	public function __construct(Category $category)
	{
		$this->category = $category;
		$this->subject = '['.trans('panichd::email/globals.notify-ticket-category', ['name' => $category->name]).'] #';
	}
	
	public function newTicket(Ticket $ticket)
    {
        $notification_owner = auth()->user();
        $template = 'panichd::emails.new_ticket';
        
		// Affects only agent notification.
		$subject = $this->subject.$ticket->id.' '.trans('panichd::email/globals.notify-created-by', ['name' => $ticket->user->name] ).trans('panichd::lang.colon').$ticket->subject;
		
		$data = [
            'ticket'             => serialize($ticket),
            'notification_owner' => serialize($notification_owner),
        ];
		
		// Notificate assigned agent
		$a_to = $this->defaultRecipients($ticket, $notification_owner, $subject, $template);
				
		// Notices only: Notificate Department email with specific template
		if (Setting::grab('departments_notices_feature') and ($ticket->owner->ticketit_department == '0' || $ticket->owner->ticketit_department != "" ) and !in_array($ticket->owner->email, [$notification_owner->email, $ticket->agent->email])){
			$a_to[] = [
				'recipient' => $ticket->owner,
				'subject' => $this->subject.$ticket->id.trans('panichd::lang.colon').$ticket->subject ,
				'template' => Setting::grab('email.owner.newticket.template')
			];
		}
		
		// Send notifications
		$this->sendNotification_exec($a_to, $template, $data, $subject);
    }
	
	public function ticketClosed(Ticket $original_ticket, Ticket $ticket)
	{
		$notification_owner = auth()->user();
		$subject = $this->subject.$ticket->id.' '.trans('panichd::email/globals.notify-closed-by', ['agent' => $notification_owner->name]).trans('panichd::lang.colon').$ticket->subject;
		$template = 'panichd::emails.closed_ticket';
		$data = [
            'ticket'             => serialize($ticket),
            'notification_owner' => serialize($notification_owner),
            'original_ticket'    => serialize($original_ticket)
        ];
		
		// Notificate assigned agent
		$a_to = $this->defaultRecipients($ticket, $notification_owner, $subject, $template);
		
		// Notificate ticket owner
		$a_to[] = [
			'recipient' => $ticket->owner,
			'subject' => $subject,
			'template' => $template
		];
		
		// Send notifications
		$this->sendNotification_exec($a_to, $template, $data, $subject);
	}
	
    public function ticketStatusUpdated(Ticket $original_ticket, Ticket $ticket)
    {
        $notification_owner = auth()->user();
        $template = 'panichd::emails.updated_ticket';
		$subject = $this->subject.$ticket->id.' ';
		$subject .= trans('panichd::email/globals.notify-status-updated-by', ['agent' => $notification_owner->name]).trans('panichd::lang.colon').$ticket->subject;
		$data = [
            'ticket'             => serialize($ticket),
            'notification_owner' => serialize($notification_owner),
            'original_ticket'    => serialize($original_ticket),
			'notification_type'  => 'status'
        ];
        
		// Notificate assigned agent
		$a_to = $this->defaultRecipients($ticket, $notification_owner, $subject, $template);
		
		// Notificate ticket owner
		if(!in_array($ticket->owner->email, [$notification_owner->email, $ticket->agent->email])){
			$a_to[] = [
				'recipient' => $ticket->owner,
				'subject' => $subject,
				'template' => $template
			];
		}
		
        // Send notifications
		$this->sendNotification_exec($a_to, $template, $data, $subject);
    }

    public function ticketAgentUpdated(Ticket $original_ticket, Ticket $ticket)
    {
        $notification_owner = auth()->user();
        $template = 'panichd::emails.updated_ticket';
        $subject = $this->subject.$ticket->id.' '.trans('panichd::email/globals.notify-assigned-to-you-by', ['agent' => $notification_owner->name]).trans('panichd::lang.colon').$ticket->subject;
		$data = [
            'ticket'             => serialize($ticket),
            'notification_owner' => serialize($notification_owner),
            'original_ticket'    => serialize($original_ticket),
			'notification_type'  => 'agent'
        ];
		
		// Notificate assigned agent
		$a_to = $this->defaultRecipients($ticket, $notification_owner, $subject, $template);
		
        // Send notifications
		$this->sendNotification_exec($a_to, $template, $data, $subject);
    }

	public function newComment(Comment $comment)
    {
        if (in_array($comment->type, ['reply', 'note'])){
			$ticket = $comment->ticket;
			$notification_owner = $comment->owner;
			$template = 'panichd::emails.new_comment';
			$subject = $this->subject.$ticket->id.' '
				.trans('panichd::email/globals.'.($comment->type == 'reply' ? 'notify-new-reply-by' : 'notify-new-note-by'), ['name' => $comment->owner->name] )
				.trans('panichd::lang.colon').$ticket->subject;
			$data = [
				'comment' => serialize($comment),
				'ticket' => serialize($ticket),
				'notification_owner' => serialize($notification_owner),
				'notification_type' => $comment->type
			];
			
			// Notificate assigned agent
			$a_to = $this->defaultRecipients($ticket, $notification_owner, $subject, $template);
			
			// Notificate ticket owner
			if ($comment->type == 'reply' and !in_array($ticket->owner->email, [$notification_owner->email, $ticket->agent->email])){
				$a_to[] = [
					'recipient' => $ticket->owner,
					'subject' => $subject,
					'template' => $template
				];
			}
			
			// Send notifications
			$this->sendNotification_exec($a_to, $template, $data, $subject);
		}        
    }
	
	public function commentUpdate(Comment $original_comment, Comment $comment)
    {
        if ($comment->type == 'note'){
			$ticket = $comment->ticket;
			$notification_owner = auth()->user();
			$template = 'panichd::emails.updated_comment';
			$subject = $this->subject.$ticket->id.' '
				.trans('panichd::email/globals.notify-note-updated-by', ['name' => $notification_owner->name] )
				.trans('panichd::lang.colon').$ticket->subject;
			$data = [
				'comment' => serialize($comment),
				'original_comment' => serialize($original_comment),
				'ticket' => serialize($ticket),
				'notification_owner' => serialize($notification_owner)
			];
		
			$a_to=[];

			// Email to ticket->agent
			if ($ticket->agent->email != $notification_owner->email){
				$a_to[] = [
					'recipient' => $ticket->agent,
				];
			}
			
			// Email to comment->owner
			if ($comment->owner->email != $notification_owner->email and $comment->owner->email != $ticket->agent->email){
				$a_to[] = [
					'recipient' => $comment->owner,
				];
			}
			
			$this->sendNotification_exec($a_to, $template, $data, $subject);
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
		$template = 'panichd::emails.comment';
		$subject = trans('panichd::lang.email-resend-abbr') . trans('panichd::lang.colon') . $this->subject.$ticket->id . ' ' . trans('panichd::email/globals.notify-new-note-by', ['name' => $comment->user->name]) . trans('panichd::lang.colon') . $ticket->subject;
			// trans('panichd::email/globals.notify-new-comment-from').$notification_owner->name.trans('panichd::email/globals.notify-on').$ticket->subject;
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
	 * Create array with default notification recipients
	*/
	public function defaultRecipients($ticket, $notification_owner, $subject, $template)
	{
		$a_to = [];
		
		// Email to ticket->agent
		if ($ticket->agent->email != $notification_owner->email){
			$a_to[] = [
				'recipient' => $ticket->agent,
				'subject' => $subject,
				'template' => $template
			];
		}
		
		return $a_to;
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
			$email_replyto->email_name = config('mail.from.name');
			$email_replyto->email = config('mail.from.address');
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
				
				$mail = new \PanicHD\PanicHD\Mail\PanicHDNotification($mail_template, $data, $email_from, $email_replyto, $subject);

				if (Setting::grab('queue_emails') == 'yes') {
					Mail::to($to['recipient'])->queue($mail);
				} else {
					Mail::to($to['recipient'])->send($mail);
				}
			}
			
			
        }
	}
}