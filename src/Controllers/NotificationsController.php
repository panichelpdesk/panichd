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
	
	public function newComment(Comment $comment)
    {
        $ticket = $comment->ticket;
        $notification_owner = $comment->user;
        $template = 'ticketit::emails.comment';
        $data = ['comment' => serialize($comment), 'ticket' => serialize($ticket)];
		
		if (!in_array($comment->type, ['complete', 'reopen'])){
			$this->sendNotification($template, $data, $ticket, $notification_owner,
				trans('ticketit::email/globals.notify-new-comment-from').$notification_owner->name.trans('ticketit::email/globals.notify-on').$ticket->subject, 'comment_'.$comment->type);
		}        
    }

    public function ticketStatusUpdated(Ticket $ticket, Ticket $original_ticket)
    {
        $notification_owner = auth()->user();
        $template = 'ticketit::emails.updated_ticket';
		$subject = $this->subject.$ticket->id.' ';
		if (!strtotime($original_ticket->completed_at) and strtotime($ticket->completed_at)) {
			$subject .= trans('ticketit::email/globals.notify-closed-by', ['agent' => $notification_owner->name]).trans('ticketit::lang.colon').$ticket->subject;	
			$change = 'close';
		}else{
			$subject .= trans('ticketit::email/globals.notify-status-updated-by', ['agent' => $notification_owner->name]).trans('ticketit::lang.colon').$ticket->subject;
			$change = 'status';
		}
        
		$data = [
            'ticket'             => serialize($ticket),
            'notification_owner' => serialize($notification_owner),
            'original_ticket'    => serialize($original_ticket),
			'change'             => $change
        ];
        
        $this->sendNotification($template, $data, $ticket, $notification_owner, $subject, $change);
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
			'change'             => 'agent'
        ];

        $this->sendNotification($template, $data, $ticket, $notification_owner, $subject, 'agent');
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

	/**
     * Send new email notification.
     *
	 */
	public function notificationResend(Request $request)
	{
		$comment=Comment::findOrFail($request->input('comment_id'));
		$ticket=$comment->ticket;
		$notification_owner = $comment->user;
		$data = ['comment' => serialize($comment), 'ticket' => serialize($ticket)];
		
		$a_to = [];
		
		if ($request->has('to_agent')){
			$a_to[] = $ticket->agent;
		}
		if ($request->has('to_owner') and (!$request->has('to_agent') or ($request->has('to_agent') and $ticket->user->email!=$ticket->agent->email))){
			$a_to[] = $ticket->user;
		}
		
		$this->sendNotification_exec($a_to, 'ticketit::emails.comment', $data, trans('ticketit::email/globals.notify-new-comment-from').$notification_owner->name.trans('ticketit::email/globals.notify-on').$ticket->subject);
		
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
    public function sendNotification($template, $data, $ticket, $notification_owner, $subject, $type)
    {
        /**
         * @var User
         */
        $a_to=[];
		
		if ($ticket->agent->email != $notification_owner->email){
			$a_to[] = ['recipient' => $ticket->agent];
		}
		
		if (in_array($type,['comment_reply','status'])){
			if ($ticket->user->email != $notification_owner->email and $ticket->agent->email != $ticket->user->email){
				$a_to[] = ['recipient' => $ticket->user];
			}
		}elseif($type=="newTicket"){
			if (Setting::grab('departments_notices_feature') and ($ticket->user->ticketit_department == '0' || $ticket->user->ticketit_department != "" )){
				$a_to[] = [
					'recipient' => $ticket->user,
					'subject' => $ticket->subject . ' [' .  trans('ticketit::lang.ticket') . ' ' . trans('ticketit::lang.table-id') . $ticket->id .']',
					'template' => Setting::grab('email.owner.newticket.template')
				];
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