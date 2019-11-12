<?php

namespace PanicHD\PanicHD\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use PanicHD\PanicHD\Models\Configuration;
use PanicHD\PanicHD\Models\Setting;

class ConfigurationsController extends Controller
{
    /**
     * Display a listing of the Setting.
     *
     * @return Response
     */
    public function index()
    {
        $configurations = Configuration::all();
        $configurations_by_sections = ['init' => [], 'table' => [], 'features' => [], 'email' => [], 'tickets' => [], 'perms' => [], 'editor' => [], 'other' => []];
        $init_section = ['main_route', 'main_route_path', 'admin_route', 'admin_route_path', 'master_template', 'member_model_class', 'user_route', 'admin_button_text'];
        $table_section = ['subject_content_column', 'list_text_max_length', 'check_last_update_seconds', 'length_menu', 'max_agent_buttons', 'calendar_month_filter', 'paginate_items'];
        $features_section = ['departments_feature', 'departments_notices_feature', 'ticket_attachments_feature'];
        $email_section = ['status_notification', 'comment_notification', 'queue_emails', 'assigned_notification',
        'list_owner_notification', 'status_owner_notification', 'email.template', 'email.owner.newticket.template', 'email.account.name', 'email.account.mailbox', 'custom_recipients', ];
        $tickets_section = ['default_priority_id', 'default_status_id', 'default_close_status_id', 'default_reopen_status_id',
            'attachments_ticket_max_size', 'attachments_ticket_max_files_num', 'attachments_mimes',
            'attachments_path', 'thumbnails_path', 'oldest_year', 'html_replacements', 'use_default_status_id', 'delete_modal_type', ];
        $perms_section = ['agent_restrict', 'close_ticket_perm', 'reopen_ticket_perm'];
        $editor_section = ['editor_enabled', 'editor_html_highlighter', 'codemirror_theme',
            'summernote_locale', 'summernote_options_json_file', 'summernote_options_user', 'purifier_config', ];

        $last_configuration = session()->has('last_configuration') ? session('last_configuration') : '';

        // Split them into configurations sections for tabs
        foreach ($configurations as $config_item) {
            //trim long values (ex serialised arrays)
            $config_item->value = $config_item->getShortContent(25, 'value');
            $config_item->default = $config_item->getShortContent(25, 'default');

            if (in_array($config_item->slug, $init_section)) {
                $section = 'init';
            } elseif (in_array($config_item->slug, $table_section)) {
                $section = 'table';
            } elseif (in_array($config_item->slug, $features_section)) {
                $section = 'features';
            } elseif (in_array($config_item->slug, $email_section)) {
                $section = 'email';
            } elseif (in_array($config_item->slug, $tickets_section)) {
                $section = 'tickets';
            } elseif (in_array($config_item->slug, $perms_section)) {
                $section = 'perms';
            } elseif (in_array($config_item->slug, $editor_section)) {
                $section = 'editor';
            } else {
                $section = 'other';
            }

            // Add item to its section array
            $configurations_by_sections[$section][] = $config_item;

            // If list is loaded after configuration update or delete, open it's related tab
            if ($config_item->slug == $last_configuration) {
                $last_tab = $section;
            }
        }

        if (session()->has('last_configuration') and !isset($last_tab)) {
            // If last configuration is not listed, has to belong to tab "other"
            $last_tab = 'other';
        }

        // Default tab
        if (!isset($last_tab)) {
            $last_tab = 'init';
        }

        return view('panichd::admin.configuration.index', compact('configurations', 'configurations_by_sections', 'last_tab'));
    }

    /**
     * Show the form for creating a new Setting.
     *
     * @return Response
     */
    public function create()
    {
        return view('panichd::admin.configuration.create');
    }

    /**
     * Store a newly created Configuration in storage.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $input = $request->all();

        $configuration = new Configuration();
        $configuration->create($input);

        Session::flash('status', 'Setting saved successfully.');
        \Cache::forget('panichd::settings'); // refresh cached settings
        return redirect()->action('\PanicHD\PanicHD\Controllers\ConfigurationsController@index');
    }

    /**
     * Show the form for editing the specified Configuration.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $configuration = Configuration::findOrFail($id);
        $should_serialize = Setting::is_serialized($configuration->value);
        $default_serialized = Setting::is_serialized($configuration->default);

        return view('panichd::admin.configuration.edit', compact('configuration', 'should_serialize', 'default_serialized'));
    }

    /**
     * Update the specified Configuration in storage.
     *
     * @param int     $id
     * @param Request $request
     *
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $configuration = Configuration::findOrFail($id);

        $value = $request->value;

        if ($request->serialize) {
            //if(!Hash::check($request->password, auth()->user()->password)){
            if (!Auth::attempt($request->only('password'), false, false)) {
                return back()->withErrors([trans('panichd::admin.config-edit-auth-failed')]);
            }
            if (false === eval('$value = serialize('.$value.');')) {
                return back()->withErrors([trans('panichd::admin.config-edit-eval-error')]);
            }
        }

        $configuration->update(['value' => $value, 'lang' => $request->lang]);

        Session::flash('status', trans('panichd::admin.config-update-confirm', ['name' => $configuration->slug]));
        // refresh cached settings
        \Cache::forget('panichd::settings');
        \Cache::forget('panichd::settings.'.$configuration->slug);

        // Pass configuration slug to open it's tab
        Session::flash('last_configuration', $configuration->slug);

        return redirect()->action('\PanicHD\PanicHD\Controllers\ConfigurationsController@index');
    }

    /**
     * Update the specified Configuration in storage.
     *
     * @param int     $id
     * @param Request $request
     *
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, $id)
    {
        $configuration = Configuration::findOrFail($id);
        $clone = clone $configuration;

        $value = $request->value;

        $configuration->delete();

        Session::flash('status', trans('panichd::admin.config-delete-confirm', ['name' => $clone->slug]));
        // refresh cached settings
        \Cache::forget('panichd::settings');
        \Cache::forget('panichd::settings.'.$clone->slug);

        // Pass configuration slug to open it's tab
        Session::flash('last_configuration', $clone->slug);

        return redirect()->action('\PanicHD\PanicHD\Controllers\ConfigurationsController@index');
    }
}
