<?php

use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Livewire\Settings\TwoFactor;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('dashboard', function () {
    $user = auth()->user();

    if ($user->isAdmin()) {
        return redirect()->route('admin.dashboard');
    } elseif ($user->isProfessor()) {
        return redirect()->route('professor.dashboard');
    } else {
        return redirect()->route('student.dashboard');
    }
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('profile.edit');
    Route::get('settings/password', Password::class)->name('user-password.edit');
    Route::get('settings/appearance', Appearance::class)->name('appearance.edit');

    Route::get('settings/two-factor', TwoFactor::class)
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');

    Route::resource('departments', \App\Http\Controllers\DepartmentController::class);

    Route::resource('professors', \App\Http\Controllers\ProfessorController::class);

    Route::resource('students', \App\Http\Controllers\StudentController::class);

    Route::resource('documents', \App\Http\Controllers\DocumentController::class);
    Route::get('documents/{document}/download', [\App\Http\Controllers\DocumentController::class, 'download'])->name('documents.download');

    Route::resource('thesis-defense-reports', \App\Http\Controllers\ThesisDefenseReportController::class);

    Route::resource('jury-members', \App\Http\Controllers\JuryMemberController::class)->except(['index', 'show']);

    Route::resource('system-settings', \App\Http\Controllers\SystemSettingController::class)->middleware('role:admin');

    Route::get('admin/dashboard', [\App\Http\Controllers\DashboardController::class, 'admin'])->middleware('role:admin')->name('admin.dashboard');
    Route::get('professor/dashboard', [\App\Http\Controllers\DashboardController::class, 'professor'])->middleware('role:professor')->name('professor.dashboard');
    Route::get('student/dashboard', [\App\Http\Controllers\DashboardController::class, 'student'])->middleware('role:student')->name('student.dashboard');
});
