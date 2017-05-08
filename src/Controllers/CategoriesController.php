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
        list($a_tags_new, $a_tags_renamed)=$this->validation_with_tags($request);

        $category = new Category();
        $category=$category->create(['name' => $request->name, 'color' => $request->color]);

		$this->sync_category_tags($request, $a_tags_new, $a_tags_renamed, $category);
		
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
		list($a_tags_new, $a_tags_renamed)=$this->validation_with_tags($request);
		
        $category = Category::findOrFail($id);
        $category->update(['name' => $request->name, 'color' => $request->color]);
		
		$this->sync_category_tags($request, $a_tags_new, $a_tags_renamed, $category);
	
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
	protected function validation_with_tags($request)
	{
		$rules=[
            'name'      => 'required',
            'color'     => 'required',
        ];
		// Allow alphanumeric and the following: ? @ / - _
		$tag_rule = "required|regex:/^[A-Za-z0-9?@\/\-_\s]+$/";
		
		// Add validation for new tags like it were fields
		$a_tags_new = array();
		if ($request->input('new_tags')){
			$i=0;
			foreach ($request->input('new_tags') as $tag){
				$a_tags_new[]=$tag;
				$request['tag'.++$i]=$tag;
				$rules['tag'.$i]=$tag_rule;
			}			
		}
		
		// Add validation for renamed tags
		$a_tags_renamed = [];
		for ($i=0;$i<$request->input('tags_count');$i++){			
			if ($request->exists('jquery_tag_name_'.$i) and $request->has('jquery_tag_id_'.$i) and !$request->has('jquery_delete_tag_'.$i)){
				\Debugbar::info('tag '.$i.' name "'.$request->input('jquery_tag_name_'.$i).'" length: '.strlen($request->input('jquery_tag_name_'.$i)));
				$tag = $request->input('jquery_tag_name_'.$i);
				$request->merge(['jquery_tag_name_'.$i=>$tag]);
				$a_tags_renamed[$request->input('jquery_tag_id_'.$i)]=$tag;
				$request['tag_name_'.$i]=$tag;
				$rules['jquery_tag_name_'.$i]=$tag_rule;			
			} 
		}		
		
        $this->validate($request, $rules);
		
		return [$a_tags_new, $a_tags_renamed];
	}
	
	/**
     * Syncs tags for category instance
     *
	 * @param $request
	 * @param $a_tags_new Array
     * @param $category instance of Kordy\Ticketit\Models\Category
	 *
	*/
	protected function sync_category_tags($request,$a_tags_new, $a_tags_renamed, $category)
	{
		// Update renamed tags
		foreach ($a_tags_renamed as $id=>$name){
			$tag=Tag::where('id',$id)->first();
			$tag->name=$name;
			$tag->save();
		}		
		
		// Get category tags
		$tags=$category->tags();
		$tags=version_compare(app()->version(), '5.3.0', '>=') ? $tags->pluck('id')->toArray() : $tags->lists('id')->toArray();
		
		// Detach checked tags to delete
		$a_detach = $a_rename = [];
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
		foreach ($a_tags_new as $tag){
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
