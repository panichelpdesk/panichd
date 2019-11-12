<?php

namespace PanicHD\PanicHD\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use PanicHD\PanicHD\Events\CommentCreated;
use PanicHD\PanicHD\Events\CommentUpdated;
use PanicHD\PanicHD\Models;
use PanicHD\PanicHD\Models\Setting;
use PanicHD\PanicHD\Models\Status;
use PanicHD\PanicHD\Traits\Attachments;
use PanicHD\PanicHD\Traits\Purifiable;
use Validator;

class CommentsController extends Controller
{
    use Attachments, Purifiable;

    protected $member;

    public function __construct()
    {
        $this->middleware('PanicHD\PanicHD\Middleware\UserAccessMiddleware', ['only' => ['store']]);
        $this->middleware('PanicHD\PanicHD\Middleware\AgentAccessMiddleware', ['only' => ['update', 'destroy']]);
    }

    // Initiate $member before any controller action
    public function callAction($method, $parameters)
    {
        $this->member = \PanicHDMember::findOrFail(auth()->user()->id);

        return parent::callAction($method, $parameters);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Previous tasks for comment validation.
     */
    protected function validation_common($request, $new_comment = true)
    {
        $a_content = $this->purifyHtml($request->get('content'));
        $request->merge(['content'=>$a_content['content']]);

        $fields = [
            'content'     => 'required|min:6',
        ];
        if ($new_comment) {
            $fields['ticket_id'] = 'required|exists:panichd_tickets,id';
        }

        if ($request->exists('attachments')) {
            $fields['attachments'] = 'array';
        }

        // Custom validation messages
        $custom_messages = [
            'content.required' => 'panichd::lang.validate-comment-required',
            'content.min'      => 'panichd::lang.validate-comment-min',
        ];
        foreach ($custom_messages as $field => $lang_key) {
            $trans = trans($lang_key);
            if ($lang_key == $trans) {
                unset($custom_messages[$field]);
            } else {
                $custom_messages[$field] = $trans;
            }
        }

        // Form validation
        $validator = Validator::make($request->all(), $fields, $custom_messages);
        $a_result_errors = [];

        if ($validator->fails()) {
            $a_result_errors = [
                'messages'=> (array) $validator->errors()->all(),
                'fields'  => (array) $validator->errors()->messages(),
            ];
        }

        $common_data = [
            'request'         => $request,
            'member'          => $this->member,
            'a_content'       => $a_content,
            'a_result_errors' => $a_result_errors,
        ];

        return $common_data;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $common_data = $this->validation_common($request);
        extract($common_data);

        // Create comment
        DB::beginTransaction();
        $comment = new Models\Comment();
        $ticket = Models\Ticket::findOrFail($request->get('ticket_id'));

        $category_level = $member->levelInCategory($ticket->category_id);
        $permission_level = ($member->currentLevel() > 1 and $category_level > 1) ? $category_level : 1;

        if ($ticket->hidden and $member->currentLevel() == 1) {
            session()->flash('warning', trans('panichd::lang.you-are-not-permitted-to-access'));

            return response()->json(array_merge(
                ['result' => 'error'],
                ['redirect'=> [route(Setting::grab('main_route').'.index')]]
            ));
        }

        $create_list_comment = false;
        if ($member->currentLevel() > 1 and $member->canManageTicket($ticket->id)) {
            // Filter response type
            $comment->type = in_array($request->get('response_type'), ['note', 'reply']) ? $request->get('response_type') : 'note';

            if ($request->input('complete_ticket') != '' and $member->canCloseTicket($ticket->id)) {
                if ($comment->type == 'reply' and $ticket->user_id == $member->id) {
                    // comment for assigned agent only
                    $comment->type = 'completetx';
                } else {
                    // Additional "close" comment entry
                    $create_list_comment = true;
                }
            }
        } else {
            $comment->type = 'reply';
        }

        // Close ticket + new status
        if ($member->canCloseTicket($ticket->id) and $request->input('complete_ticket') != '') {
            $ticket->completed_at = Carbon::now();
            $ticket->status_id = Status::where('id', $request->get('status_id'))->count() == 1 ? $request->get('status_id') : Setting::grab('default_close_status_id');
        }

        $comment->content = $a_content['content'];
        $comment->html = $a_content['html'];
        $comment->user_id = $member->id;
        $comment->ticket_id = $ticket->id;

        if ($ticket->agent_id != $this->member->id) {
            // Ticket and comment will be unread for assigned agent
            $ticket->read_by_agent = 0;
            $comment->read_by_agent = 0;
        }

        $comment->save();

        // Create additional list comment if has('complete_ticket') but $member != auth()
        if ($create_list_comment) {
            $list_comment = new Models\Comment();
            $list_comment->type = 'complete';

            $list_comment->content = $list_comment->html = '';
            $list_comment->ticket_id = $ticket->id;
            $list_comment->user_id = $member->id;
            $list_comment->save();
        }

        // Create attachments from embedded images
        $this->embedded_images_to_attachments($permission_level, $ticket, $comment);

        // Update parent ticket
        $ticket->updated_at = $comment->created_at;

        if ($member->currentLevel() > 1 and $member->canManageTicket($ticket->id) and $comment->type == 'reply' and $request->input('add_to_intervention') != '') {
            $ticket->intervention = $ticket->intervention.$comment->content;
            $ticket->intervention_html = $ticket->intervention_html.$comment->html;
        }

        $ticket->save();

        if (Setting::grab('ticket_attachments_feature')) {
            // Attached files
            $a_result_errors = $this->saveAttachments(compact('request', 'a_result_errors', 'ticket', 'comment'));
        }

        // If errors present
        if ($a_result_errors) {
            return response()->json(array_merge(
                ['result' => 'error'],
                $a_result_errors
            ));
        }

        DB::commit();
        event(new CommentCreated(clone $comment, $request));

        session()->flash('status', trans('panichd::lang.comment-has-been-added-ok'));

        return response()->json([
            'result' => 'ok',
            'url'    => route(Setting::grab('main_route').'.show', $ticket->id),
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int     $id
     *
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $common_data = $this->validation_common($request, false);
        extract($common_data);

        // Update comment
        $comment = Models\Comment::findOrFail($id);
        $original_comment = clone $comment;
        $ticket = Models\Ticket::findOrFail($comment->ticket_id);

        DB::beginTransaction();
        $comment->content = $a_content['content'];
        $comment->html = $a_content['html'];

        if ($ticket->agent_id != $this->member->id) {
            // Ticket and comment will be unread for assigned agent
            $ticket->read_by_agent = 0;
            $comment->read_by_agent = 0;
        }

        $comment->save();

        // Create attachments from embedded images
        $category_level = $member->levelInCategory($ticket->category_id);
        $permission_level = ($member->currentLevel() > 1 and $category_level > 1) ? $category_level : 1;
        $this->embedded_images_to_attachments($permission_level, $ticket, $comment);

        $ticket->touch();

        $ticket->save();

        if (Setting::grab('ticket_attachments_feature')) {
            // 1 - update existing attachment fields
            $a_result_errors = $this->updateAttachments($request, $a_result_errors, $comment->attachments()->get());

            // 2 - add new attachments
            $a_result_errors = $this->saveAttachments(compact('request', 'a_result_errors', 'ticket', 'comment'));

            if (!$a_result_errors) {
                // 3 - destroy checked attachments
                if ($request->input('delete_files') != '') {
                    $destroy_error = $this->destroyAttachmentIds($request->delete_files);

                    if ($destroy_error) {
                        $a_result_errors['messages'][] = $destroy_error;
                    }
                }
            }
        }

        // If errors present
        if ($a_result_errors) {
            return response()->json(array_merge(
                ['result' => 'error'],
                $a_result_errors
            ));
        }

        DB::commit();
        event(new CommentUpdated($original_comment, $comment));

        session()->flash('status', trans('panichd::lang.comment-has-been-updated'));

        return response()->json([
            'result' => 'ok',
            'url'    => route(Setting::grab('main_route').'.show', $ticket->id),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $comment = Models\Comment::findOrFail($id);

        $error = $comment->delete();

        if ($error) {
            return redirect()->back()->with('warning', trans('panichd::lang.comment-destroy-error', ['error' => $error]));
        } else {
            return back()->with('status', trans('panichd::lang.comment-has-been-deleted'));
        }
    }
}
