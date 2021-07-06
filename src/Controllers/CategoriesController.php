<?php

namespace PanicHD\PanicHD\Controllers;

use App\Http\Controllers\Controller;
use Cache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use PanicHD\PanicHD\Helpers\LaravelVersion;
use PanicHD\PanicHD\Models;
use PanicHD\PanicHD\Models\Category;
use PanicHD\PanicHD\Models\Tag;

class CategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $categories = Category::
            with('closingReasons')
            ->with(['tags' => function($q){
                $q->withCount('tickets');
            }])
            ->get();

        return view('panichd::admin.category.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $status_lists = $this->Statuses();

        return view('panichd::admin.category.create', compact('status_lists'));
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
        list($request, $reason_rules, $reason_messages, $a_reasons) = $this->add_reasons_to($request);

        list($request, $tag_rules, $tag_messages, $a_tags_new, $a_tags_update) = $this->add_tags_to($request);

        // Do Laravel validation
        $this->do_validate($request, array_merge($reason_rules, $tag_rules), array_merge($reason_messages, $tag_messages));

        $category = new Category();

        $category->name = $request->name;
        $category->color = $request->color;
        $category = $this->category_email_fields($request, $category);
        $category->create_level = $request->create_level;

        $category->save();

        $this->sync_reasons($request, $category, $a_reasons);

        $this->sync_category_tags($request, $category, $a_tags_new, $a_tags_update);

        Session::flash('status', trans('panichd::lang.category-name-has-been-created', ['name' => $request->name]));

        \Cache::forget('panichd::categories');

        return redirect()->action('\PanicHD\PanicHD\Controllers\CategoriesController@index');
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
        $category = Category::with([
            'tags'=> function ($q) {
                $q->withCount('tickets');
            },
        ])->with('closingReasons.status')->findOrFail($id);

        $status_lists = $this->Statuses();

        return view('panichd::admin.category.edit', compact('category', 'status_lists'));
    }

    /**
     * Returns statuses list
     * Decouple it with list().
     *
     * @return array
     */
    protected function Statuses()
    {
        $statuses = Cache::remember('panichd::statuses', 60, function () {
            return Models\Status::all();
        });

        if (LaravelVersion::min('5.3.0')) {
            return $statuses->pluck('name', 'id');
        } else {
            return $statuses->lists('name', 'id');
        }
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
        list($request, $reason_rules, $reason_messages, $a_reasons) = $this->add_reasons_to($request);

        list($request, $tag_rules, $tag_messages, $a_tags_new, $a_tags_update) = $this->add_tags_to($request);

        // Do Laravel validation
        $this->do_validate($request, array_merge($reason_rules, $tag_rules), array_merge($reason_messages, $tag_messages));

        $category = Category::findOrFail($id);

        $category->name = $request->name;
        $category->color = $request->color;
        $category = $this->category_email_fields($request, $category);
        $category->create_level = $request->create_level;

        $category->save();

        $this->sync_reasons($request, $category, $a_reasons);

        $this->sync_category_tags($request, $category, $a_tags_new, $a_tags_update);

        Session::flash('status', trans('panichd::lang.category-name-has-been-modified', ['name' => $request->name]));

        \Cache::forget('panichd::categories');

        return redirect()->action('\PanicHD\PanicHD\Controllers\CategoriesController@index');
    }

    /**
     * Adds reason fields to $request.
     *
     * @param Request $request
     *
     * Return Array
     */
    protected function add_reasons_to($request)
    {
        $reason_rules = $reason_messages = $a_new = $a_update = $a_delete = [];
        $regex_text = trans('panichd::lang.regex-text-inline');

        $min_chars = '5';

        if ($request->exists('reason_ordering')) {
            foreach ($request->input('reason_ordering') as $ordering=>$i) {
                if ($request->input('jquery_delete_reason_'.$i) != '') {
                    $a_delete[] = $request->input('jquery_reason_id_'.$i);
                } elseif ($request->input('jquery_reason_id_'.$i) != '') {
                    $reason = [
                        'ordering'=> $ordering,
                    ];
                    if ($request->exists('jquery_reason_text_'.$i)) {
                        $reason['text'] = $request->input('jquery_reason_text_'.$i);
                        $reason_rules['jquery_reason_text_'.$i] = "required|min:$min_chars|regex:".$regex_text;

                        // Reason message
                        $reason_messages['jquery_reason_text_'.$i.'.required'] = trans('panichd::admin.category-reason-is-empty', ['number' => $i + 1]);
                        $reason_messages['jquery_reason_text_'.$i.'.min'] = trans('panichd::admin.category-reason-too-short', ['number' => $i + 1, 'name'=>$reason['text'], 'min' => $min_chars]);
                    }

                    if ($request->exists('jquery_reason_status_id_'.$i)) {
                        $reason['status_id'] = $request->input('jquery_reason_status_id_'.$i);
                        $reason_rules['jquery_reason_status_id_'.$i] = 'required|exists:panichd_statuses,id';

                        // Reason message
                        $reason_messages['jquery_reason_status_id_'.$i.'.required'] = trans('panichd::admin.category-reason-no-status', ['number' => $i + 1, 'name'=>$reason['text']]);
                    }

                    if ($request->input('jquery_reason_id_'.$i) == 'new') {
                        $a_new[] = $reason;
                    } else {
                        $a_update[$request->input('jquery_reason_id_'.$i)] = $reason;
                    }
                }
            }
        }

        $a_reasons = ['new'=>$a_new, 'update'=>$a_update, 'delete'=>$a_delete];

        return [$request, $reason_rules, $reason_messages, $a_reasons];
    }

    /**
     * Adds tag fields to $request.
     *
     * @param Request $request
     *
     * Return Array
     */
    protected function add_tags_to($request)
    {
        $a_kept_tags = $a_key_names = $a_names = $tag_rules = $tag_messages = [];

        // Allow alphanumeric and the following: ? @ / - _
        $tag_rule = 'required|regex:'.trans('panichd::admin.tag-regex');

        $c_tags = Tag::all();

        $a_tags_update = [];
        for ($i = 0; $i < $request->input('tags_count'); $i++) {
            $tag = $c_tags->first(function ($q) use ($request, $i) {
                return $q->id == $request->{'jquery_tag_id_'.$i};
            });

            if ($request->has('jquery_tag_id_'.$i) and !$request->has('jquery_delete_tag_'.$i) and !is_null($tag)) {
                // Tag has been kept in category
                $a_kept_tags[$i] = $tag;

                if ($request->exists('jquery_tag_name_'.$i)) {
                    // Add new name for rule preparation
                    $a_key_names[$i] = $request->{'jquery_tag_name_'.$i};
                } else {
                    // Add current name (has not changed) for rule preparation
                    $a_key_names[$i] = $tag->name;
                }
            }
        }

        // Names for new tags rule preparation
        $a_names = array_values($a_key_names);

        foreach ($a_kept_tags as $i => $tag) {
            // Add validation for renamed tags
            if ($request->exists('jquery_tag_name_'.$i)) {
                // New tag name
                $new_name = $request->input('jquery_tag_name_'.$i);

                $request->merge(['jquery_tag_name_'.$i=>$new_name]);
                $a_tags_update[$request->input('jquery_tag_id_'.$i)]['name'] = $new_name;
                $request['jquery_tag_name_'.$i] = $new_name;

                // Rule for an updated tag
                $a_this_not_in = $a_key_names;
                unset($a_this_not_in[$i]);
                $tag_rules['jquery_tag_name_'.$i] = $tag_rule.($a_this_not_in ? '|not_in:'.implode(',', array_values($a_this_not_in)) : '');

                // Add specific validation error messages
                $tag_messages['jquery_tag_name_'.$i.'.required'] = trans('panichd::admin.update-tag-validation-empty', ['name' => $tag->name]);
                $tag_messages['jquery_tag_name_'.$i.'.regex'] = trans('panichd::admin.category-tag-not-valid-format', ['tag'=>$new_name]);
                $tag_messages['jquery_tag_name_'.$i.'.not_in'] = trans('panichd::admin.tag-validation-two', ['name' => $new_name]);
            }

            // Add colors for tag update
            if ($request->input('jquery_tag_color_'.$i) != '') {
                $a_tags_update[$request->input('jquery_tag_id_'.$i)]['color'] = $request->input('jquery_tag_color_'.$i);
            }
        }

        // Add validation for new tags
        $a_tags_new = [];
        if ($request->filled('new_tags')) {
            foreach ($request->input('new_tags') as $id) {
                if (!$request->has('jquery_delete_tag_'.$id)) {
                    // Rule for new tag
                    $tag_rules['jquery_tag_name_'.$id] = $tag_rule.($a_names ? '|not_in:'.implode(',', $a_names) : '');

                    // Add tag name to array
                    $a_names[] = $request->{'jquery_tag_name_'.$id};

                    // Add specific validation error messages
                    $tag_messages['jquery_tag_name_'.$id.'.required'] = trans('panichd::admin.new-tag-validation-empty');
                    $tag_messages['jquery_tag_name_'.$id.'.regex'] = trans('panichd::admin.category-tag-not-valid-format', ['tag' => $request->{'jquery_tag_name_'.$id}]);
                    $tag_messages['jquery_tag_name_'.$id.'.not_in'] = trans('panichd::admin.tag-validation-two', ['name' => $request->{'jquery_tag_name_'.$id}]);

                    $a_tags_new[] = [
                        'name'  => $request->{'jquery_tag_name_'.$id},
                        'color' => $request->{'jquery_tag_color_'.$id},
                    ];
                }
            }
        }

        return [$request, $tag_rules, $tag_messages, $a_tags_new, $a_tags_update];
    }

    /**
     * Does the request validation.
     *
     * @param Request $request
     */
    protected function do_validate($request, $rules, $reason_messages)
    {
        $rules = array_merge($rules, [
            'name'         => 'required',
            'color'        => 'required',
            'create_level' => 'required|in:1,2,3',
        ]);

        if ($request->email_scope != 'default') {
            $rules = array_merge($rules, [
                'email_name'   => 'required|string',
                'email'        => 'required|email',
            ]);
        }

        $this->validate($request, $rules, $reason_messages);
    }

    /*
     * Returns category instance with email fields updated in object
    */
    protected function category_email_fields($request, $category)
    {
        if ($request->email_scope != 'default' and $request->input('email_name') != '' and $request->input('email') != '') {
            $category->email_name = $request->email_name;
            $category->email = $request->email;

            if ($request->email_replies == 1) {
                $category->email_replies = 1;
            } else {
                $category->email_replies = 0;
            }
        } else {
            $category->email_name = null;
            $category->email = null;
            $category->email_replies = 0;
        }

        return $category;
    }

    /**
     * Syncs reasons for category.
     *
     * @param $request
     * @param $a_tags_new Array
     * @param $category instance of PanicHD\PanicHD\Models\Category
     */
    protected function sync_reasons($request, $category, $a_reasons)
    {
        // Add new reasons
        foreach ($a_reasons['new'] as $fields) {
            $new = new Models\Closingreason();
            $new->text = $fields['text'];
            $new->status_id = $fields['status_id'];
            $new->category_id = $category->id;
            $new->ordering = $fields['ordering'];
            $new->save();
        }

        // Update reasons
        foreach ($a_reasons['update'] as $id=>$fields) {
            $reason = Models\Closingreason::where('id', $id)->first();
            $update = false;
            if (isset($fields['text'])) {
                $reason->text = $fields['text'];
                $update = true;
            }
            if (isset($fields['status_id'])) {
                $reason->status_id = $fields['status_id'];
                $update = true;
            }
            if ($reason->ordering != $fields['ordering']) {
                $reason->ordering = $fields['ordering'];
                $update = true;
            }

            if ($update) {
                $reason->save();
            }
        }

        // Delete marked reasons
        if ($a_reasons['delete']) {
            Models\Closingreason::destroy($a_reasons['delete']);
        }
    }

    /**
     * Syncs tags for category instance.
     *
     * @param $request
     * @param $a_tags_new Array
     * @param $category instance of PanicHD\PanicHD\Models\Category
     */
    protected function sync_category_tags($request, $category, $a_tags_new, $a_tags_update)
    {
        // Update renamed tags
        foreach ($a_tags_update as $id=>$fields) {
            $tag = Tag::where('id', $id)->first();
            if (isset($fields['name'])) {
                $tag->name = $fields['name'];
            }
            if (isset($fields['color'])) {
                $a_colors = explode('_', $fields['color']);
                $tag->bg_color = $a_colors[0];
                $tag->text_color = $a_colors[1];
            }
            $tag->save();
        }

        // Get category tags
        $tags = $category->tags();
        $tags = version_compare(app()->version(), '5.3.0', '>=') ? $tags->pluck('id')->toArray() : $tags->lists('id')->toArray();

        // Detach checked tags to delete
        $a_detach = $a_rename = [];
        for ($i = 0; $i < $request->input('tags_count'); $i++) {
            if ($request->input('jquery_delete_tag_'.$i) != '') {
                // Exclude for sync
                $a_detach[] = $request->input('jquery_delete_tag_'.$i);
            }
        }
        if ($a_detach) {
            // Detach on categories
            $tags = array_diff($tags, $a_detach);
        }

        // Add new tags
        foreach ($a_tags_new as $a_tag) {
            $a_colors = explode('_', $a_tag['color']);

            $new_tag = Tag::create([
                'name'       => $a_tag['name'],
                'bg_color'   => $a_colors[0],
                'text_color' => $a_colors[1],
            ]);

            $tags[] = $new_tag->id;
        }

        // Sync all category tags
        $category->tags()->sync($tags);

        // Detach deleted tags that have Tickets
        Tag::whereIn('id', $a_detach)->each(function ($tag) {
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

        Session::flash('status', trans('panichd::lang.category-name-has-been-deleted', ['name' => $name]));

        \Cache::forget('panichd::categories');

        return redirect()->action('\PanicHD\PanicHD\Controllers\CategoriesController@index');
    }
}
