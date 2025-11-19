<?php

namespace App\Http\Controllers;

use App\Models\SystemSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SystemSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $this->authorize('viewAny', SystemSetting::class);

        $settings = SystemSetting::orderBy('key')->paginate(15);

        return view('system-settings.index', compact('settings'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $this->authorize('create', SystemSetting::class);

        return view('system-settings.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', SystemSetting::class);

        $validated = $request->validate([
            'key' => 'required|string|max:255|unique:system_settings',
            'value' => 'nullable|string',
            'type' => 'required|in:string,integer,boolean,json',
            'description' => 'nullable|string',
        ]);

        SystemSetting::create($validated);

        return redirect()->route('system-settings.index')
            ->with('success', __('app.system_setting_created_successfully'));
    }

    /**
     * Display the specified resource.
     */
    public function show(SystemSetting $systemSetting): View
    {
        $this->authorize('view', $systemSetting);

        return view('system-settings.show', compact('systemSetting'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SystemSetting $systemSetting): View
    {
        $this->authorize('update', $systemSetting);

        return view('system-settings.edit', compact('systemSetting'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SystemSetting $systemSetting): RedirectResponse
    {
        $this->authorize('update', $systemSetting);

        $validated = $request->validate([
            'value' => 'nullable|string',
            'type' => 'required|in:string,integer,boolean,json',
            'description' => 'nullable|string',
        ]);

        $systemSetting->update($validated);

        return redirect()->route('system-settings.index')
            ->with('success', __('app.system_setting_updated_successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SystemSetting $systemSetting): RedirectResponse
    {
        $this->authorize('delete', $systemSetting);

        $systemSetting->delete();

        return redirect()->route('system-settings.index')
            ->with('success', __('app.system_setting_deleted_successfully'));
    }
}
