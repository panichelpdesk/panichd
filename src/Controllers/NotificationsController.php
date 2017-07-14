<?php

namespace Kordy\Ticketit\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Kordy\Ticketit\Helpers\LaravelVersion;
use Kordy\Ticketit\Models\Comment;
use Kordy\Ticketit\Models\Setting;
use Kordy\Ticketit\Models\Ticket;

class NotificationsController extends Controller
{
    public function newComment(Comment $comment)
    {
        $ticket = $comment->ticket;
        $notification_owner = $comment->user;
        $template = 'ticketit::emails.comment';
        $data = ['comment' => serialize($comment), 'ticket' => serialize($ticket)];
		
		if (!in_array($comment->type, ['complete', 'reopen'])){
			$this->sendNotification($template, $data, $ticket, $notification_owner,
				trans('ticketit::lang.notify-new-comment-from').$notification_owner->name.trans('ticketit::lang.notify-on').$ticket->subject, 'comment_'.$comment->type);
		}        
    }

    public function ticketStatusUpdated(Ticket $ticket, Ticket $original_ticket)
    {
        $notification_owner = auth()->user();
        $template = 'ticketit::emails.status';
        $data = [
            'ticket'             => serialize($ticket),
            'notification_owner' => serialize($notification_owner),
            'original_ticket'    => serialize($original_ticket),
        ];

        if (strtotime($ticket->completed_at)) {
            $this->sendNotification($template, $data, $ticket, $notification_owner,
                $notification_owner->name.trans('ticketit::lang.notify-updated').$ticket->subject.trans('ticketit::lang.notify-status-to-complete'), 'status');
        } else {
            $this->sendNotification($template, $data, $ticket, $notification_owner,
                $notification_owner->name.trans('ticketit::lang.notify-updated').$ticket->subject.trans('ticketit::lang.notify-status-to').$ticket->status->name, 'status');
        }
    }

    public function ticketAgentUpdated(Ticket $ticket, Ticket $original_ticket)
    {
        $notification_owner = auth()->user();
        $template = 'ticketit::emails.transfer';
        $data = [
            'ticket'             => serialize($ticket),
            'notification_owner' => serialize($notification_owner),
            'original_ticket'    => serialize($original_ticket),
        ];

        $this->sendNotification($template, $data, $ticket, $notification_owner,
            $notification_owner->name.trans('ticketit::lang.notify-transferred').$ticket->subject.trans('ticketit::lang.notify-to-you'), 'agent');
    }

    public function newTicket(Ticket $ticket)
    {
        $notification_owner = auth()->user();
        $template = 'ticketit::emails.assigned';
        $data = [
            'ticket'             => serialize($ticket),
            'notification_owner' => serialize($notification_owner),
        ];

        $this->sendNotification($template, $data, $ticket, $notification_owner,
            $notification_owner->name.trans('ticketit::lang.notify-created-ticket').$ticket->subject, 'newTicket');
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
		
		$this->sendNotification_exec($a_to, 'ticketit::emails.comment', $data, $notification_owner, trans('ticketit::lang.notify-new-comment-from').$notification_owner->name.trans('ticketit::lang.notify-on').$ticket->subject);
		
		return back()->with('status','Notificacions reenviades correctament');
	}
	
	
    /**
     * Send email notifications from the action owner to other involved users.
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
			$a_to[] = $ticket->agent;
		}
		
		if (in_array($type,['comment_reply','status']) 
			and $ticket->user->email != $notification_owner->email 
			and $ticket->agent->email != $ticket->user->email){
				$a_to[] = $ticket->user;
		}

		$this->sendNotification_exec($a_to, $template, $data, $notification_owner, $subject);
        
    }
	
	/**
     * Send email notifications from the action owner to other involved users.
     *
     * @param string $template
     * @param array  $data
     * @param object $ticket
     * @param object $notification_owner
     */
    public function sendNotification_exec($a_to, $template, $data, $notification_owner, $subject)
    {
		if (LaravelVersion::lt('5.4')) {
            foreach ($a_to as $to){
				$mail_callback = function ($m) use ($to, $notification_owner, $subject) {
					$m->to($to->email, $to->name);

					$m->replyTo($notification_owner->email, $notification_owner->name);

					$m->subject($subject);
				};

				if (Setting::grab('queue_emails') == 'yes') {
					Mail::queue($template, $data, $mail_callback);
				} else {
					Mail::send($template, $data, $mail_callback);
				}
			}
			
        } elseif (LaravelVersion::min('5.4')) {
            foreach ($a_to as $to){
				$mail = new \Kordy\Ticketit\Mail\TicketitNotification($template, $data, $notification_owner, $subject);

				if (Setting::grab('queue_emails') == 'yes') {
					Mail::to($to)->queue($mail);
				} else {
					Mail::to($to)->send($mail);
				}
			}
			
			
        }
	}
}