<?php

namespace App\Http\Controllers;

use App\Models\Professor;
use App\Models\Student;
use App\Models\ThesisDefenseReport;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ThesisDefenseReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = ThesisDefenseReport::with(['student.user', 'supervisor.user', 'juryMembers.professor.user']);

        // Filter by status if specified
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter by student if specified
        if ($request->has('student_id') && $request->student_id) {
            $query->where('student_id', $request->student_id);
        }

        // Filter by supervisor if specified
        if ($request->has('supervisor_id') && $request->supervisor_id) {
            $query->where('supervisor_id', $request->supervisor_id);
        }

        $thesisDefenseReports = $query->latest()->paginate(15);

        $students = Student::with('user')->get();
        $professors = Professor::with('user')->get();

        return view('thesis-defense-reports.index', compact('thesisDefenseReports', 'students', 'professors'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $this->authorize('create', ThesisDefenseReport::class);

        $students = Student::with('user')->get();
        $professors = Professor::with('user')->get();

        return view('thesis-defense-reports.create', compact('students', 'professors'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', ThesisDefenseReport::class);

        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'supervisor_id' => 'required|exists:professors,id',
            'title' => 'required|string|max:255',
            'abstract' => 'nullable|string',
            'defense_date' => 'required|date|after:today',
            'defense_time' => 'required|date_format:H:i',
            'room' => 'nullable|string|max:255',
        ]);

        // Validate that supervisor is not the same as student (if they have the same user)
        $student = Student::find($validated['student_id']);
        $supervisor = Professor::find($validated['supervisor_id']);

        if ($student->user_id === $supervisor->user_id) {
            return back()->withErrors(['supervisor_id' => __('app.supervisor_cannot_be_same_as_student')])->withInput();
        }

        ThesisDefenseReport::create($validated);

        return redirect()->route('thesis-defense-reports.index')
            ->with('success', __('app.thesis_defense_report_created_successfully'));
    }

    /**
     * Display the specified resource.
     */
    public function show(ThesisDefenseReport $thesisDefenseReport): View
    {
        $this->authorize('view', $thesisDefenseReport);

        $thesisDefenseReport->load([
            'student.user',
            'supervisor.user',
            'juryMembers.professor.user',
        ]);

        return view('thesis-defense-reports.show', compact('thesisDefenseReport'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ThesisDefenseReport $thesisDefenseReport): View
    {
        $this->authorize('update', $thesisDefenseReport);

        $students = Student::with('user')->get();
        $professors = Professor::with('user')->get();

        return view('thesis-defense-reports.edit', compact('thesisDefenseReport', 'students', 'professors'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ThesisDefenseReport $thesisDefenseReport): RedirectResponse
    {
        $this->authorize('update', $thesisDefenseReport);

        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'supervisor_id' => 'required|exists:professors,id',
            'title' => 'required|string|max:255',
            'abstract' => 'nullable|string',
            'defense_date' => 'required|date',
            'defense_time' => 'required|date_format:H:i',
            'room' => 'nullable|string|max:255',
            'final_grade' => 'nullable|numeric|min:0|max:20',
            'status' => 'required|in:scheduled,completed,cancelled',
            'comments' => 'nullable|string',
        ]);

        // Validate that supervisor is not the same as student
        $student = Student::find($validated['student_id']);
        $supervisor = Professor::find($validated['supervisor_id']);

        if ($student->user_id === $supervisor->user_id) {
            return back()->withErrors(['supervisor_id' => __('app.supervisor_cannot_be_same_as_student')])->withInput();
        }

        $thesisDefenseReport->update($validated);

        return redirect()->route('thesis-defense-reports.index')
            ->with('success', __('app.thesis_defense_report_updated_successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ThesisDefenseReport $thesisDefenseReport): RedirectResponse
    {
        $this->authorize('delete', $thesisDefenseReport);

        $thesisDefenseReport->delete();

        return redirect()->route('thesis-defense-reports.index')
            ->with('success', __('app.thesis_defense_report_deleted_successfully'));
    }
}
