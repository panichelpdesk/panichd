<?php

namespace Kordy\Ticketit\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Kordy\Ticketit\Models\Category;
use Kordy\Ticketit\Models\Tag;

class CategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $categories = Category::with('tags')->get();		

        return view('ticketit::admin.category.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('ticketit::admin.category.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $new_tags=$this->validation_and_new_tags($request);

        $category = new Category();
        $category=$category->create(['name' => $request->name, 'color' => $request->color]);

		$this->sync_category_tags($request, $new_tags, $category);
		
        Session::flash('status', trans('ticketit::lang.category-name-has-been-created', ['name' => $request->name]));

        return redirect()->action('\Kordy\Ticketit\Controllers\CategoriesController@index');
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
        return 'All category related agents here';
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
        $category = Category::with(['tags'=>function($q){
			$q->withCount('tickets');
		}])->findOrFail($id);

        return view('ticketit::admin.category.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int     $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
		$new_tags=$this->validation_and_new_tags($request);
		
        $category = Category::findOrFail($id);
        $category->update(['name' => $request->name, 'color' => $request->color]);
		
		$this->sync_category_tags($request, $new_tags, $category);
	
        Session::flash('status', trans('ticketit::lang.category-name-has-been-modified', ['name' => $request->name]));
			
        return redirect()->action('\Kordy\Ticketit\Controllers\CategoriesController@index');
    }
	
	/**
     * Does the request validation
     *
     * @param Request $request
	 *
	 * Return Array
	*/
	protected function validation_and_new_tags($request){
		$rules=[
            'name'      => 'required',
            'color'     => 'required',
        ];
		
		// Add validation for new tags like it were fields
		$new_tags=array();
		if ($request->input('new_tags')){
			$i=0;
			foreach ($request->input('new_tags') as $tag){
				$new_tags[]=$tag;
				$request['tag'.++$i]=$tag;
				$rules['tag'.$i]="regex:/^[\pL\s]+$/u";
			}			
		}
		
        $this->validate($request, $rules);
		
		return $new_tags;
	}
	
	/**
     * Syncs tags for category instance
     *
	 * @param $request
	 * @param $new_tags Array
     * @param $category instance of Kordy\Ticketit\Models\Category
	 *
	*/
	protected function sync_category_tags($request,$new_tags, $category){
		// Get category tags
		$tags=$category->tags()->pluck('id')->toArray();
		
		// Detach marked current tags
		$a_detach = [];
		for ($i=0;$i<$request->input('tags_count');$i++){			
			if ($request->has('jquery_delete_tag_'.$i)){
				// Exclude for sync
				$a_detach[]=$request->input('jquery_delete_tag_'.$i);							
			} 
		}
		if ($a_detach){
			// Detach on categories
			$tags=array_diff($tags,$a_detach);
		}
		
		// Add new tags
		foreach ($new_tags as $tag){
			$new=Tag::whereHas('categories',function($q)use($category){
				$q->where('id',$category->id);
			})->where('name',$tag)->firstOrCreate(['name'=>$tag]);
			
			$tags[]=$new->id;
		}		
		
		// Sync all category tags
		$category->tags()->sync($tags);
		
		// Detach deleted tags that have Tickets
		Tag::whereIn('id',$a_detach)->each(function($tag){
			$tag->tickets()->detach();
		});
		
		// Delete orphan tags (Without any related categories or tickets)
		Tag::doesntHave('categories')->doesntHave('tickets')->delete();		
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
        $category = Category::findOrFail($id);
        $name = $category->name;
        $category->delete();

		// Delete orphan tags (Without any related categories or tickets)
		Tag::doesntHave('categories')->doesntHave('tickets')->delete();
		
        Session::flash('status', trans('ticketit::lang.category-name-has-been-deleted', ['name' => $name]));

        return redirect()->action('\Kordy\Ticketit\Controllers\CategoriesController@index');
    }
}
