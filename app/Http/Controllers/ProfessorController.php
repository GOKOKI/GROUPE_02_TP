<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Professor;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class ProfessorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $professors = Professor::with(['user', 'department'])->paginate(15);

        return view('professors.index', compact('professors'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $this->authorize('create', Professor::class);

        $departments = Department::all();

        return view('professors.create', compact('departments'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', Professor::class);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'department_id' => 'required|exists:departments,id',
            'title' => 'nullable|string|max:255',
            'specialty' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'bio' => 'nullable|string',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'professor',
        ]);

        Professor::create([
            'user_id' => $user->id,
            'department_id' => $validated['department_id'],
            'title' => $validated['title'],
            'specialty' => $validated['specialty'],
            'phone' => $validated['phone'],
            'bio' => $validated['bio'],
        ]);

        return redirect()->route('professors.index')
            ->with('success', __('app.professor_created_successfully'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Professor $professor): View
    {
        $this->authorize('view', $professor);

        $professor->load(['user', 'department', 'documents', 'supervisedTheses']);

        return view('professors.show', compact('professor'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Professor $professor): View
    {
        $this->authorize('update', $professor);

        $departments = Department::all();

        return view('professors.edit', compact('professor', 'departments'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Professor $professor): RedirectResponse
    {
        $this->authorize('update', $professor);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$professor->user_id,
            'department_id' => 'required|exists:departments,id',
            'title' => 'nullable|string|max:255',
            'specialty' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'bio' => 'nullable|string',
        ]);

        $professor->user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        $professor->update([
            'department_id' => $validated['department_id'],
            'title' => $validated['title'],
            'specialty' => $validated['specialty'],
            'phone' => $validated['phone'],
            'bio' => $validated['bio'],
        ]);

        return redirect()->route('professors.index')
            ->with('success', __('app.professor_updated_successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Professor $professor): RedirectResponse
    {
        $this->authorize('delete', $professor);

        $professor->user->delete(); // This will cascade delete the professor due to foreign key

        return redirect()->route('professors.index')
            ->with('success', __('app.professor_deleted_successfully'));
    }
}
