<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $students = Student::with(['user', 'department'])->paginate(15);

        return view('students.index', compact('students'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $this->authorize('create', Student::class);

        $departments = Department::all();

        return view('students.create', compact('departments'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', Student::class);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'department_id' => 'required|exists:departments,id',
            'student_number' => 'required|string|max:20|unique:students',
            'phone' => 'nullable|string|max:20',
            'enrollment_date' => 'required|date',
            'level' => 'nullable|string|max:255',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'student',
        ]);

        Student::create([
            'user_id' => $user->id,
            'department_id' => $validated['department_id'],
            'student_number' => $validated['student_number'],
            'phone' => $validated['phone'],
            'enrollment_date' => $validated['enrollment_date'],
            'level' => $validated['level'],
        ]);

        return redirect()->route('students.index')
            ->with('success', __('app.student_created_successfully'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Student $student): View
    {
        $this->authorize('view', $student);

        $student->load(['user', 'department', 'documents', 'thesisDefenseReports']);

        return view('students.show', compact('student'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Student $student): View
    {
        $this->authorize('update', $student);

        $departments = Department::all();

        return view('students.edit', compact('student', 'departments'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Student $student): RedirectResponse
    {
        $this->authorize('update', $student);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$student->user_id,
            'department_id' => 'required|exists:departments,id',
            'student_number' => 'required|string|max:20|unique:students,student_number,'.$student->id,
            'phone' => 'nullable|string|max:20',
            'enrollment_date' => 'required|date',
            'level' => 'nullable|string|max:255',
        ]);

        $student->user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        $student->update([
            'department_id' => $validated['department_id'],
            'student_number' => $validated['student_number'],
            'phone' => $validated['phone'],
            'enrollment_date' => $validated['enrollment_date'],
            'level' => $validated['level'],
        ]);

        return redirect()->route('students.index')
            ->with('success', __('app.student_updated_successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student): RedirectResponse
    {
        $this->authorize('delete', $student);

        $student->user->delete(); // This will cascade delete the student due to foreign key

        return redirect()->route('students.index')
            ->with('success', __('app.student_deleted_successfully'));
    }
}
