<?php

namespace Kordy\Ticketit\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Kordy\Ticketit\Models;
use Kordy\Ticketit\Models\Agent;
use Kordy\Ticketit\Models\Setting;
use Kordy\Ticketit\Models\Status;
use Kordy\Ticketit\Traits\Purifiable;

class CommentsController extends Controller
{
    use Purifiable;

    public function __construct()
    {
        $this->middleware('Kordy\Ticketit\Middleware\IsAdminMiddleware', ['only' => ['edit', 'update', 'destroy']]);
        $this->middleware('Kordy\Ticketit\Middleware\ResAccessMiddleware', ['only' => 'store']);
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
     * Store a newly created resource in storage.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $a_content = $this->purifyHtml($request->get('content'));
        $request->merge(['content'=>$a_content['content']]);

        $this->validate($request, [
            'ticket_id'   => 'required|exists:ticketit,id',
            'content'     => 'required|min:6',
        ]);
		
		// Create comment
        $comment = new Models\Comment();
		$comment->type = 'reply';
		
		$ticket = Models\Ticket::findOrFail($request->get('ticket_id'));
		
		$agent = Agent::find(\Auth::user()->id);
		if ($agent){
			// Response: reply or note
			if ($agent->canManageTicket($request->get('ticket_id'))){
				$comment->type = in_array($request->get('response_type'), ['note','reply']) ? $request->get('response_type') : 'note';
			}
			
			// Close ticket + new status
			if ($agent->canCloseTicket($request->get('ticket_id')) and $request->has('complete_ticket')){
				$ticket->completed_at = Carbon::now();				
				$ticket->status_id = Status::where('id',$request->get('status_id'))->count()==1 ? $request->get('status_id') : Setting::grab('default_close_status_id');				
			}
		}			
		
        $comment->content = $a_content['content'];
        $comment->html = $a_content['html'];

        $comment->ticket_id = $request->get('ticket_id');
        $comment->user_id = \Auth::user()->id;
        $comment->save();

		// Update parent ticket        
        $ticket->updated_at = $comment->created_at;
        
		if ($request->has('add_to_intervention')){
			$ticket->intervention = $ticket->intervention.$a_content['content'];
			$ticket->intervention_html = $ticket->intervention_html.$a_content['html'];
		}
		
		$ticket->save();

        return back()->with('status', trans('ticketit::lang.comment-has-been-added-ok'));
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
		$comment->content = $a_content['content'];
        $comment->html = $a_content['html'];
		
		$comment->save();
						
		if ($request->has('add_to_intervention')){
			$ticket = Models\Ticket::findOrFail($comment->ticket_id);
			$ticket->intervention = $ticket->intervention.$a_content['content'];
			$ticket->intervention_html = $ticket->intervention_html.$a_content['html'];
			$ticket->save();			
		}		
		
		return back()->with('status', 'El comentari s\'ha actualitzat correctament');
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
        $comment->delete();

        return back()->with('status', 'El comentari s\'ha eliminat correctament');
    }
}
