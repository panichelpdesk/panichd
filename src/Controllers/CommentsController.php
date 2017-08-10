<?php

namespace Kordy\Ticketit\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Kordy\Ticketit\Models;
use Kordy\Ticketit\Models\Agent;
use Kordy\Ticketit\Models\Setting;
use Kordy\Ticketit\Models\Status;
use Kordy\Ticketit\Traits\Purifiable;
use Log;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class CommentsController extends Controller
{
    use Purifiable;

    public function __construct()
    {
        $this->middleware('Kordy\Ticketit\Middleware\ResAccessMiddleware', ['only' => ['store','edit', 'update', 'destroy']]);
        
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
				
		$fields = [
            'ticket_id'   => 'required|exists:ticketit,id',
            'content'     => 'required|min:6',
            'attachments' => 'array',
        ];
		
		if ($request->exists('attachments')){
			$fields['attachments'] = 'array';
		}
		
        $this->validate($request, $fields);
		
		// Create comment
		DB::beginTransaction();
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
		
        $comment->ticket_id = $request->get('ticket_id');
        $comment->user_id = \Auth::user()->id;
		$comment->content = $a_content['content'];
        $comment->html = $a_content['html'];
		$comment->save();
		
		// Update parent ticket        
        $ticket->updated_at = $comment->created_at;
        
		if ($request->has('add_to_intervention')){
			$ticket->intervention = $ticket->intervention.$a_content['content'];
			$ticket->intervention_html = $ticket->intervention_html.$a_content['html'];
		}
		
		$ticket->save();
		
		if ($request->exists('attachments')){
			foreach ($request->attachments as $uploadedFile) {
				/** @var UploadedFile $uploadedFile */
				if (is_null($uploadedFile)) {
					// No files attached
					break;
				}
			}
			
			if (!$uploadedFile instanceof UploadedFile) {
				Log::error('File object expected, given: '.print_r($uploadedFile, true));
				throw new InvalidArgumentException();
			}

			$attachments_path = Setting::grab('attachments_path');
			$file_name = auth()->user()->id.'_'.$comment->ticket_id.'_'.$comment->id.md5(Str::random().$uploadedFile->getClientOriginalName());
			$file_directory = storage_path($attachments_path);

			$attachment = new Models\Attachment();
			$attachment->ticket_id = $comment->ticket_id;
			$attachment->comment_id = $comment->id;
			$attachment->uploaded_by_id = $comment->user_id;
			$attachment->original_filename = $uploadedFile->getClientOriginalName() ?: '';
			$attachment->bytes = $uploadedFile->getSize();
			$attachment->mimetype = $uploadedFile->getMimeType() ?: '';
			$attachment->file_path = $file_directory.DIRECTORY_SEPARATOR.$file_name;
			$attachment->save();

			// Should be called when you no need anything from this file, otherwise it fails with Exception that file does not exists (old path being used)
			$uploadedFile->move(storage_path($attachments_path), $file_name);
          
		}
		
		DB::commit();

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
        $comment->delete();

        return back()->with('status', trans('ticketit::lang.comment-has-been-deleted'));
    }
}
