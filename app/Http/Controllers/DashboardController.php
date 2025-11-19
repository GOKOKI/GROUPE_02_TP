<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Document;
use App\Models\Professor;
use App\Models\Student;
use App\Models\ThesisDefenseReport;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function admin(): View
    {
        $stats = [
            'total_users' => User::count(),
            'total_students' => Student::count(),
            'total_professors' => Professor::count(),
            'total_departments' => Department::count(),
            'total_documents' => Document::count(),
            'total_thesis_defenses' => ThesisDefenseReport::count(),
            'scheduled_defenses' => ThesisDefenseReport::where('status', 'scheduled')->count(),
            'completed_defenses' => ThesisDefenseReport::where('status', 'completed')->count(),
        ];

        $recentDefenses = ThesisDefenseReport::with(['student.user', 'supervisor.user'])
            ->latest()
            ->paginate(5, ['*'], 'defenses_page');

        $recentDocuments = Document::with(['user', 'documentable'])
            ->latest()
            ->paginate(5, ['*'], 'documents_page');

        $departmentStats = Department::withCount(['students', 'professors'])
            ->take(10)
            ->get()
            ->map(function ($department) {
                return [
                    'name' => $department->name,
                    'students' => $department->students_count,
                    'professors' => $department->professors_count,
                    'total' => $department->students_count + $department->professors_count,
                ];
            });

        $monthlyDefenses = collect();
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthlyDefenses->push([
                'month' => $date->format('M Y'),
                'scheduled' => ThesisDefenseReport::where('status', 'scheduled')
                    ->whereYear('defense_date', $date->year)
                    ->whereMonth('defense_date', $date->format('m'))
                    ->count(),
                'completed' => ThesisDefenseReport::where('status', 'completed')
                    ->whereYear('defense_date', $date->year)
                    ->whereMonth('defense_date', $date->format('m'))
                    ->count(),
            ]);
        }

        return view('dashboard.admin', compact(
            'stats',
            'recentDefenses',
            'recentDocuments',
            'departmentStats',
            'monthlyDefenses'
        ));
    }

    /**
     * Display the professor dashboard.
     */
    public function professor(): View
    {
        $user = auth()->user();
        $professor = $user->professor;

        $stats = [
            'supervised_theses' => $professor->supervisedTheses()->count(),
            'jury_memberships' => $professor->juryMemberships()->count(),
            'uploaded_documents' => Document::where('user_id', $user->id)->count(),
            'upcoming_defenses' => $professor->supervisedTheses()
                ->where('status', 'scheduled')
                ->where('defense_date', '>=', now())
                ->count(),
        ];

        $recentTheses = $professor->supervisedTheses()
            ->with('student.user')
            ->latest()
            ->take(5)
            ->get();

        $upcomingDefenses = $professor->supervisedTheses()
            ->with('student.user')
            ->where('status', 'scheduled')
            ->where('defense_date', '>=', now())
            ->orderBy('defense_date')
            ->take(5)
            ->get();

        return view('dashboard.professor', compact(
            'stats',
            'recentTheses',
            'upcomingDefenses'
        ));
    }

    /**
     * Display the student dashboard.
     */
    public function student(): View
    {
        $user = auth()->user();
        $student = $user->student;

        if (! $student) {
            abort(403, __('app.student_profile_not_found'));
        }

        $stats = [
            'thesis_defenses' => $student->thesisDefenseReports()->count(),
            'uploaded_documents' => Document::where('user_id', $user->id)->count(),
            'completed_defenses' => $student->thesisDefenseReports()->where('status', 'completed')->count(),
        ];

        $defenseHistory = $student->thesisDefenseReports()
            ->with('supervisor.user')
            ->latest()
            ->get();

        $recentDocuments = Document::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard.student', compact(
            'stats',
            'defenseHistory',
            'recentDocuments'
        ));
    }
}
