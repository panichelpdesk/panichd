<?php

namespace PanicHD\PanicHD\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use PanicHD\PanicHD\Helpers\LaravelVersion;
use PanicHD\PanicHD\Models\Category;
use PanicHD\PanicHD\Models\Comment;
use PanicHD\PanicHD\Models\CommentNotification;
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
        $notification_owner = \PanicHDMember::find(auth()->user()->id);
        $template = 'panichd::emails.new_ticket';

        // Affects only agent notification.
        $subject = $this->subject.$ticket->id.' '.trans('panichd::email/globals.notify-created-by', ['name' => $ticket->user->name]).trans('panichd::lang.colon').$ticket->subject;

        $data = [
            'ticket'             => serialize($ticket),
            'notification_owner' => serialize($notification_owner),
            'current_level'      => $notification_owner->currentLevel(),
        ];

        // Notificate assigned agent
        $a_to = $this->defaultRecipients($ticket, $notification_owner, $subject, $template);

        // Not hidden notices only: Notificate Department email with specific template
        if (!$ticket->hidden and Setting::grab('departments_notices_feature')
            and ($ticket->owner->ticketit_department == '0' || $ticket->owner->ticketit_department != '')
            and $ticket->owner->email
            and !in_array($ticket->owner->email, [$notification_owner->email, $ticket->agent->email])) {
            $a_to[] = [
                'recipient' => $ticket->owner,
                'subject'   => $this->subject.$ticket->id.trans('panichd::lang.colon').$ticket->subject,
                'template'  => Setting::grab('email.owner.newticket.template'),
            ];
        }

        // Send notifications
        $this->sendNotification($a_to, $data);
    }

    public function ticketClosed(Ticket $original_ticket, Ticket $ticket)
    {
        $notification_owner = auth()->user();
        $subject = $this->subject.$ticket->id.' '.trans('panichd::email/globals.notify-closed-by', ['agent' => $notification_owner->name]).trans('panichd::lang.colon').$ticket->subject;
        $template = 'panichd::emails.closed_ticket';
        $data = [
            'ticket'             => serialize($ticket),
            'notification_owner' => serialize($notification_owner),
            'original_ticket'    => serialize($original_ticket),
        ];

        // Notificate assigned agent
        $a_to = $this->defaultRecipients($ticket, $notification_owner, $subject, $template);

        // Notificate ticket owner
        if (Setting::grab('list_owner_notification') and !$ticket->hidden and $ticket->owner->email
            and !in_array($ticket->owner->email, [$notification_owner->email, $ticket->agent->email])) {
            $a_to[] = [
                'recipient' => $ticket->owner,
                'subject'   => $subject,
                'template'  => $template,
            ];
        }

        // Send notifications
        $this->sendNotification($a_to, $data);
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
            'notification_type'  => 'status',
        ];

        // Notificate assigned agent
        $a_to = $this->defaultRecipients($ticket, $notification_owner, $subject, $template);

        // Notificate ticket owner
        if (Setting::grab('status_owner_notification') and !$ticket->hidden and $ticket->owner->email
            and !in_array($ticket->owner->email, [$notification_owner->email, $ticket->agent->email])) {
            $a_to[] = [
                'recipient' => $ticket->owner,
                'subject'   => $subject,
                'template'  => $template,
            ];
        }

        // Send notifications
        $this->sendNotification($a_to, $data);
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
            'notification_type'  => 'agent',
        ];

        // Notificate assigned agent
        $a_to = $this->defaultRecipients($ticket, $notification_owner, $subject, $template);

        // Send notifications
        $this->sendNotification($a_to, $data);
    }

    public function newComment(Comment $comment, $request)
    {
        if (in_array($comment->type, ['reply', 'note'])) {
            $ticket = $comment->ticket;
            $notification_owner = $comment->owner;
            $template = 'panichd::emails.new_comment';
            $subject = $this->subject.$ticket->id.' '
                .trans('panichd::email/globals.'.($comment->type == 'reply' ? 'notify-new-reply-by' : 'notify-new-note-by'), ['name' => $comment->owner->name])
                .trans('panichd::lang.colon').$ticket->subject;
            $data = [
                'comment'            => serialize($comment),
                'ticket'             => serialize($ticket),
                'notification_owner' => serialize($notification_owner),
                'notification_type'  => $comment->type,
            ];

            $member = \PanicHDMember::find(auth()->user()->id);
            $category_level = $member->levelInCategory($ticket->category_id);
            $permission_level = ($member->currentLevel() > 1 and $category_level > 1) ? $category_level : 1;

            $a_recipients = [];

            if ($permission_level < 2 or !Setting::grab('custom_recipients')) {
                // Add default recipients
                foreach ($this->defaultRecipients($ticket, $notification_owner, $subject, $template) as $default) {
                    $a_recipients[] = $default['recipient']->id;
                }

                // Add ticket owner
                if (!$ticket->hidden and !is_null($ticket->owner) and $ticket->owner->email
                    and !in_array($ticket->owner->email, [$notification_owner->email, $ticket->agent->email])
                    and ($comment->type == 'reply' or ($comment->type != 'reply' and $ticket->owner->levelInCategory($ticket->category->id) > 1))) {
                    $a_recipients[] = $ticket->owner->id;
                }

                foreach ($ticket->comments()->whereIn('type', ['reply', 'note'])->with('owner', 'notifications')->get() as $comm) {
                    // Previous comment authors
                    if ($comm->owner->id != $notification_owner->id and !in_array($comm->owner->id, $a_recipients)
                        and $comm->owner->email
                        and ($comment->type == 'reply' or ($comment->type != 'reply' and $comm->owner->levelInCategory($ticket->category->id) > 1))) {
                        $a_recipients[] = $comm->owner->id;
                    }

                    // All previous comments recipients
                    foreach ($comm->notifications as $notification) {
                        $recipient = \PanicHDMember::find($notification->member_id);
                        if (!is_null($recipient) and $recipient->email
                            and ($comment->type == 'reply' or ($comment->type != 'reply' and $recipient->levelInCategory($ticket->category->id) > 1))
                            and $notification_owner->id != $recipient->id and !in_array($recipient->id, $a_recipients)) {
                            $a_recipients[] = $recipient->id;
                        }
                    }
                }
            }

            if ($permission_level > 1) {
                if (Setting::grab('custom_recipients')) {
                    /* About $a_recipients
                    *   $comment->a_recipients come from embedded comments i TicketsController
                    *   $request recipients come from a comment modal in Ticket card
                    */
                    $a_recipients = isset($comment->a_recipients) ? $comment->a_recipients : ($comment->type == 'note' ? $request->note_recipients : $request->reply_recipients);
                }

                if ($a_recipients and count($a_recipients) > 0) {
                    if ($request->input('add_in_user_notification_text') != '' or (isset($comment->add_in_user_notification_text))) {
                        // Element in request comes from Comment modal
                        // $comment property comes from an embedded comment when editing or creating a ticket
                        $data['add_in_user_notification_text'] = true;
                    }
                }
            }

            if ($a_recipients and count($a_recipients) > 0) {
                foreach ($a_recipients as $member_id) {
                    $recipient = \PanicHDMember::find($member_id);
                    if (!is_null($recipient) and $recipient->email) {
                        // Register the notified email
                        $notification = CommentNotification::create([
                            'comment_id' => $comment->id,
                            'name'       => $recipient->name,
                            'email'      => $recipient->email,
                            'member_id'  => $member_id,
                        ]);

                        // Add email to actual mail recipients
                        $a_to[] = [
                            'recipient' => $recipient,
                            'subject'   => $subject,
                            'template'  => $template,
                        ];
                    }
                }
            }

            // Send notifications
            if (isset($a_to)) {
                $this->sendNotification($a_to, $data);
            }
        }
    }

    public function commentUpdate(Comment $original_comment, Comment $comment)
    {
        if ($comment->type == 'note') {
            $ticket = $comment->ticket;
            $notification_owner = auth()->user();
            $template = 'panichd::emails.updated_comment';
            $subject = $this->subject.$ticket->id.' '
                .trans('panichd::email/globals.notify-note-updated-by', ['name' => $notification_owner->name])
                .trans('panichd::lang.colon').$ticket->subject;
            $data = [
                'comment'            => serialize($comment),
                'original_comment'   => serialize($original_comment),
                'ticket'             => serialize($ticket),
                'notification_owner' => serialize($notification_owner),
            ];

            // Send notification to all comment notified users
            foreach ($comment->notifications as $notification) {
                $recipient = \PanicHDMember::find($notification->member_id);
                if (!is_null($recipient) and $recipient->email) {
                    if ($recipient->email != $notification_owner->email) {
                        $a_to[] = [
                            'recipient' => $recipient,
                            'subject'   => $subject,
                            'template'  => $template,
                        ];
                    }
                }
            }

            if (isset($a_to)) {
                $this->sendNotification($a_to, $data);
            }
        }
    }

    /**
     * Send again the new email notification to checked recipients in form.
     */
    public function notificationResend(Request $request)
    {
        $comment = Comment::with('notifications')->findOrFail($request->input('comment_id'));
        $ticket = $comment->ticket;
        $notification_owner = $comment->user;
        $template = 'panichd::emails.new_comment';
        $subject = trans('panichd::lang.email-resend-abbr').trans('panichd::lang.colon').$this->subject.$ticket->id.' '.trans('panichd::email/globals.notify-new-note-by', ['name' => $comment->user->name]).trans('panichd::lang.colon').$ticket->subject;
        $data = [
            'comment'            => serialize($comment),
            'ticket'             => serialize($ticket),
            'notification_owner' => serialize($notification_owner),
            'notification_type'  => $comment->type,
        ];

        if ($request->input('recipients') != '') {
            foreach ($request->recipients as $recipient_key) {
                // Search by member_id or by email address
                $recipient = \PanicHDMember::whereNotNull('email')
                    ->where(function ($query) use ($recipient_key) {
                        $query->where('id', $recipient_key)->orWhere('email', $recipient_key);
                    })->first();

                if (!is_null($recipient)) {
                    $a_to[] = [
                        'recipient' => $recipient,
                        'subject'   => $subject,
                        'template'  => $template,
                    ];

                    $notification = $comment->notifications()->where('member_id', $recipient->id)->first();
                    if (is_null($notification)) {
                        // Register the notified email
                        $notification = CommentNotification::create([
                            'comment_id' => $comment->id,
                            'name'       => $recipient->name,
                            'email'      => $recipient->email,
                            'member_id'  => $recipient->id,
                        ]);

                        if ($comment->notifications->count() == 0) {
                            // No previous registered notifications
                            if ($ticket->agent->id == $comment->owner->id) {
                                // Message from agent to owner, so we register the non registered past owner notification
                                if ($ticket->owner->email) {
                                    $notification = CommentNotification::create([
                                        'comment_id' => $comment->id,
                                        'name'       => $ticket->owner->name,
                                        'email'      => $ticket->owner->email,
                                        'member_id'  => $ticket->owner->id,
                                    ]);
                                }
                            } elseif ($ticket->owner->id == $comment->owner->id) {
                                // Message from owner to agent, so we register the non registered past agent notification
                                if ($ticket->agent->email) {
                                    $notification = CommentNotification::create([
                                        'comment_id' => $comment->id,
                                        'name'       => $ticket->agent->name,
                                        'email'      => $ticket->agent->email,
                                        'member_id'  => $ticket->agent->id,
                                    ]);
                                }
                            }
                        }
                    }
                }
            }
        }

        // Load $this->category for sendNotification
        $this->category = $ticket->category;

        // Send notifications
        if (isset($a_to)) {
            $this->sendNotification($a_to, $data);

            return back()->with('status', trans('panichd::lang.notification-resend-confirmation'));
        } else {
            return back()->with('warning', trans('panichd::lang.notification-resend-no-recipients'));
        }
    }

    /**
     * Create array with default notification recipients.
     */
    public function defaultRecipients($ticket, $notification_owner, $subject, $template)
    {
        $a_to = [];

        // Email to ticket->agent
        if ($ticket->agent->email and $ticket->agent->email != $notification_owner->email) {
            $a_to[] = [
                'recipient' => $ticket->agent,
                'subject'   => $subject,
                'template'  => $template,
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
    public function sendNotification($a_to, $data)
    {
        $email_replyto = new \stdClass();

        if (Setting::grab('email.account.name') != 'default' and Setting::grab('email.account.mailbox') != 'default') {
            // ReplyTo: Use tickets email account
            $email_replyto->email_name = Setting::grab('email.account.name');
            $email_replyto->email = Setting::grab('email.account.mailbox');
        } else {
            // ReplyTo: Use Laravel general account
            $email_replyto->email_name = config('mail.from.name');
            $email_replyto->email = config('mail.from.address');
        }

        // From: Use same as ReplyTo
        $email_from = clone $email_replyto;

        if ($this->category->email_name != '' and $this->category->email != '') {
            // From: Use category email account
            $email_from->email_name = $this->category->email_name;
            $email_from->email = $this->category->email;

            if ($this->category->email_replies == 1) {
                // ReplyTo: Use category email account
                $email_replyto = $email_from;
            }
        }

        // Add Email From to template
        $data = array_merge($data, ['email_from' => serialize($email_from)]);

        // Send emails
        if (LaravelVersion::lt('5.4')) {
            foreach ($a_to as $to) {
                if ($to['recipient']->email != '') {
                    // Pass recipient user object to every generated notification email
                    $data = array_merge($data, ['recipient' => $to['recipient']]);

                    $mail_callback = function ($m) use ($to, $email_from, $email_replyto) {
                        $m->to($to['recipient']->email, $to['recipient']->name);

                        $m->from($email_from->email, $email_from->email_name);
                        $m->replyTo($email_replyto->email, $email_replyto->email_name);

                        $m->subject($to['subject']);
                    };

                    if (Setting::grab('queue_emails')) {
                        Mail::queue($to['template'], $data, $mail_callback);
                    } else {
                        Mail::send($to['template'], $data, $mail_callback);
                    }
                }
            }
        } elseif (LaravelVersion::min('5.4')) {
            foreach ($a_to as $to) {
                if ($to['recipient']->email != '') {
                    // Pass recipient user object to every generated notification email
                    $data = array_merge($data, ['recipient' => $to['recipient']]);

                    $mail = new \PanicHD\PanicHD\Mail\PanicHDNotification($to['template'], $data, $email_from, $email_replyto, $to['subject']);

                    if (Setting::grab('queue_emails')) {
                        Mail::to($to['recipient'])->queue($mail);
                    } else {
                        Mail::to($to['recipient'])->send($mail);
                    }
                }
            }
        }
    }
}
