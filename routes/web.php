<?php

use App\Http\Controllers\InvitationController;
use App\Http\Controllers\InterviewBookingController;
use App\Livewire\CompanyOnboarding;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Central domain — no company subdomain
Route::domain('hireflow.test')->group(function () {
    Route::get('/', function () {
        return view('welcome');
    });

    Route::get('/register-company', CompanyOnboarding::class)->name('company.onboarding');

    Route::get('/invitation/accept', [InvitationController::class, 'accept'])
        ->name('invitation.accept');
});

// Tenant subdomains
Route::domain('{tenant}.hireflow.test')->middleware(['tenant'])->group(function () {

    // Public — no login required
    Route::get('/careers', \App\Livewire\CareersPage::class)->name('careers');
    Route::get('/jobs/{jobPosting}/apply', \App\Livewire\ApplicationForm::class)->name('jobs.apply');

    Route::get('/interviews/{application}/book', [InterviewBookingController::class, 'show'])->name('interview.book');
    Route::get('/interviews/slots/{slot}/confirm', [InterviewBookingController::class, 'book'])->name('interview.book.confirm');

    // Everything below requires login
    Route::middleware(['auth'])->group(function () {
        Route::get('/dashboard', function () {
            return view('dashboard');
        })->name('dashboard');

        Route::post('/logout', function () {
            Auth::logout();
            request()->session()->invalidate();
            request()->session()->regenerateToken();
            return redirect('/login');
        })->name('logout');

        Route::get('/team', \App\Livewire\TeamManagement::class)->name('team.management');

        Route::get('/jobs', [\App\Http\Controllers\JobPostingController::class, 'index'])->name('jobs.index');
        Route::get('/jobs/create', \App\Livewire\JobPostingForm::class)->name('jobs.create');
        Route::get('/jobs/{jobPosting}/edit', \App\Livewire\JobPostingForm::class)->name('jobs.edit');
        Route::get('/jobs/{jobPosting}/pipeline', \App\Livewire\PipelineBoard::class)->name('jobs.pipeline');
        Route::get('/search', \App\Livewire\CandidateSearch::class)->name('search');
        Route::get('/api-tokens',\App\Livewire\ApiTokenManager::class)->name('api-tokens');
        Route::get('/webhooks', \App\Livewire\WebhookManager::class)->name('webhooks');
        Route::get('/candidates/{application}', [\App\Http\Controllers\CandidateController::class, 'show'])->name('candidates.show');
        Route::get('/candidates/{application}/scorecard', \App\Livewire\ScoreCardForm::class)->name('scorecard.create');
        Route::get('/candidates/{application}/consensus', \App\Livewire\ConsensusView::class)->name('consensus.view');
        Route::get('/candidates/{application}/interviews', \App\Livewire\InterviewSlotManager::class)->name('interviews.manage');
    });
});

require __DIR__.'/auth.php';
