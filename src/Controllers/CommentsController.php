<?php

namespace Kordy\Ticketit\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use DB;
use Validator;
use Illuminate\Http\Request;
use InvalidArgumentException;
use Kordy\Ticketit\Models;
use Kordy\Ticketit\Models\Agent;
use Kordy\Ticketit\Models\Setting;
use Kordy\Ticketit\Models\Status;
use Kordy\Ticketit\Traits\Attachments;
use Kordy\Ticketit\Traits\Purifiable;

class CommentsController extends Controller
{
    use Attachments, Purifiable;

    public function __construct()
    {
        $this->middleware('Kordy\Ticketit\Middleware\UserAccessMiddleware', ['only' => ['store']]);
		$this->middleware('Kordy\Ticketit\Middleware\AgentAccessMiddleware', ['only' => ['update', 'destroy']]);		
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
	 * Previous tasks for comment validation
	*/
	protected function validation_common($request, $new_comment = true)
	{
		$a_content = $this->purifyHtml($request->get('content'));
        $request->merge(['content'=>$a_content['content']]);
				
		$fields = [
            'ticket_id'   => 'required|exists:ticketit,id',
            'content'     => 'required|min:6'
        ];
		
		if ($request->exists('attachments')){
			$fields['attachments'] = 'array';
		}
		
		// Custom validation messages
		$custom_messages = [
			'content.required' => 'ticketit::lang.validate-comment-required',
			'content.min' => 'ticketit::lang.validate-comment-min',
		];
		foreach ($custom_messages as $field => $lang_key){
			$trans = trans ($lang_key);
			if ($lang_key == $trans){
				unset($custom_messages[$field]);
			}else{
				$custom_messages[$field] = $trans;
			}
		}
		
        // Form validation
        $validator = Validator::make($request->all(), $fields, $custom_messages);
		$a_result_errors = [];
		
		if ($validator->fails()) {
			$a_result_errors = [
				'messages'=>(array)$validator->errors()->all(),
				'fields'=>(array)$validator->errors()->messages()
			];
		}
		
		$common_data = [
			'request' => $request,
			'a_content' => $a_content,
			'a_result_errors' => $a_result_errors
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
		$comment->type = 'reply';
		
		$ticket = Models\Ticket::findOrFail($request->get('ticket_id'));
		
		$agent = Agent::findOrFail(\Auth::user()->id);
		
		// Response: reply or note
		if ($agent->currentLevel() > 1 and $agent->canManageTicket($request->get('ticket_id'))){
			$comment->type = in_array($request->get('response_type'), ['note','reply']) ? $request->get('response_type') : 'note';
		}
		
		// Close ticket + new status
		if ($agent->canCloseTicket($request->get('ticket_id')) and $request->has('complete_ticket')){
			$ticket->completed_at = Carbon::now();				
			$ticket->status_id = Status::where('id',$request->get('status_id'))->count()==1 ? $request->get('status_id') : Setting::grab('default_close_status_id');				
		}
		
        $comment->ticket_id = $request->get('ticket_id');
        $comment->user_id = $agent->id;
		$comment->content = $a_content['content'];
        $comment->html = $a_content['html'];
		$comment->save();
		
		// Update parent ticket        
        $ticket->updated_at = $comment->created_at;
        
		if ($agent->currentLevel() > 1 and $agent->canManageTicket($request->get('ticket_id')) and $request->has('add_to_intervention')){
			$ticket->intervention = $ticket->intervention.$a_content['content'];
			$ticket->intervention_html = $ticket->intervention_html.$a_content['html'];
		}
		
		$ticket->save();
		
		if (Setting::grab('ticket_attachments_feature')){
			$attach_error = $this->saveAttachments($request, $ticket, $comment);
			if ($attach_error){
				$a_result_errors['messages'][] = $attach_error;
			}
		}
		
		// If errors present
		if ($a_result_errors){
			return response()->json(array_merge(
				['result' => 'error'],
				$a_result_errors
			));
		}
		
		DB::commit();
		
		session()->flash('status', trans('ticketit::lang.comment-has-been-added-ok'));
		
		return response()->json([
			'result' => 'ok',
			'url' => route(Setting::grab('main_route').'.show', $ticket->id)
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
        $a_content = $this->purifyHtml($request->get('content'));
        $request->merge(['content'=>$a_content['content']]);

        $this->validate($request, [
            'content'     => 'required|min:6',
        ]);
		
		// Update comment
		$comment=Models\Comment::findOrFail($id);
		
		DB::beginTransaction();
		$comment->content = $a_content['content'];
        $comment->html = $a_content['html'];
		
		$comment->save();
		$ticket = Models\Ticket::findOrFail($comment->ticket_id);
		
		if ($request->has('add_to_intervention')){			
			$ticket->intervention = $ticket->intervention.$a_content['content'];
			$ticket->intervention_html = $ticket->intervention_html.$a_content['html'];
			$ticket->save();			
		}
		
		if (Setting::grab('ticket_attachments_feature')){
			$attachment_errors = false;
			
			// 1 - destroy checked attachments
			if ($request->has('delete_files')) $attachment_errors = $this->destroyAttachmentIds($request->delete_files);
			
			// 2 - update existing attachment fields
			if (!$attachment_errors) $attachment_errors = $this->updateAttachments($request, $comment->attachments()->get());
			
			// 3 - add new attachments
			if (!$attachment_errors) $attachment_errors = $this->saveAttachments($request, $ticket, $comment);
			
			if ($attachment_errors){
				return redirect()->back()->with('warning', $attachment_errors);
			}
		}
		
		DB::commit();
		
		return back()->with('status', trans('ticketit::lang.comment-has-been-updated'));
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
        $comment=Models\Comment::findOrFail($id);

		if (Setting::grab('ticket_attachments_feature')){
			$attach_error = $this->destroyAttachmentsFrom($comment->ticket, $comment);
			if ($attach_error){
				return redirect()->back()->with('warning', $attach_error);
			}
		}
		
        $comment->delete();

        return back()->with('status', trans('ticketit::lang.comment-has-been-deleted'));
    }
}
