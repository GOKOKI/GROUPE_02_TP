<?php

namespace App\Http\Controllers;

use App\Models\JuryMember;
use App\Models\Professor;
use App\Models\ThesisDefenseReport;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class JuryMemberController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        $thesisDefenseReportId = $request->get('thesis_defense_report_id');
        $thesisDefenseReport = ThesisDefenseReport::with(['student.user', 'supervisor.user'])->findOrFail($thesisDefenseReportId);

        $this->authorize('update', $thesisDefenseReport);

        // Get professors who are not already jury members for this thesis
        $existingJuryMemberIds = $thesisDefenseReport->juryMembers->pluck('professor_id');
        $availableProfessors = Professor::with('user')
            ->whereNotIn('id', $existingJuryMemberIds)
            ->where('id', '!=', $thesisDefenseReport->supervisor_id) // Supervisor can't be jury member
            ->get();

        return view('jury-members.create', compact('thesisDefenseReport', 'availableProfessors'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'thesis_defense_report_id' => 'required|exists:thesis_defense_reports,id',
            'professor_id' => 'required|exists:professors,id',
            'role' => 'required|string|max:255',
        ]);

        $thesisDefenseReport = ThesisDefenseReport::findOrFail($validated['thesis_defense_report_id']);
        $this->authorize('update', $thesisDefenseReport);

        // Check if professor is already a jury member
        $existing = JuryMember::where('thesis_defense_report_id', $validated['thesis_defense_report_id'])
            ->where('professor_id', $validated['professor_id'])
            ->exists();

        if ($existing) {
            return back()->withErrors(['professor_id' => 'This professor is already a jury member for this thesis defense.'])->withInput();
        }

        // Check if professor is the supervisor
        if ($thesisDefenseReport->supervisor_id == $validated['professor_id']) {
            return back()->withErrors(['professor_id' => 'The supervisor cannot be a jury member.'])->withInput();
        }

        JuryMember::create($validated);

        return redirect()->route('thesis-defense-reports.show', $thesisDefenseReport)
            ->with('success', __('app.jury_member_added_successfully'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(JuryMember $juryMember): View
    {
        $this->authorize('update', $juryMember->thesisDefenseReport);

        return view('jury-members.edit', compact('juryMember'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, JuryMember $juryMember): RedirectResponse
    {
        $this->authorize('update', $juryMember->thesisDefenseReport);

        $validated = $request->validate([
            'role' => 'required|string|max:255',
            'grade' => 'nullable|numeric|min:0|max:20',
            'comments' => 'nullable|string',
        ]);

        $juryMember->update($validated);

        return redirect()->route('thesis-defense-reports.show', $juryMember->thesis_defense_report_id)
            ->with('success', __('app.jury_member_updated_successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(JuryMember $juryMember): RedirectResponse
    {
        $this->authorize('update', $juryMember->thesisDefenseReport);

        $juryMember->delete();

        return redirect()->route('thesis-defense-reports.show', $juryMember->thesis_defense_report_id)
            ->with('success', __('app.jury_member_removed_successfully'));
    }
}
