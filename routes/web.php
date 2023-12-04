<?php

use App\Http\Controllers\AuditController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\Populates\CampaignController;
use App\Http\Controllers\Reports\VoiceReportController;
use App\Http\Controllers\SolarLtEvaluation\SolarLtCategoryController;
use App\Http\Controllers\SolarLtEvaluation\SolarLtDatapointController;
use App\Http\Controllers\SolarLtEvaluation\SolarLtEvaluationController;
use App\Http\Controllers\TeamLeadAudits\AssignedVoiceAuditController;
use App\Http\Controllers\Users\RoleController;
use App\Http\Controllers\Users\UserController;
use App\Http\Controllers\Voice\DatapointCategoryController;
use App\Http\Controllers\Voice\DataPointController;
use App\Http\Controllers\Voice\VoiceAuditActionController;
use App\Http\Controllers\Voice\VoiceAuditAppealController;
use App\Http\Controllers\Voice\VoiceAuditController;
use App\Http\Controllers\Voice\VoiceCustomFieldController;
use App\Http\Controllers\Voice\VoiceEvaluationActionController;
use App\Http\Controllers\Voice\VoiceEvaluationController;
use App\Http\Controllers\Voice\VoiceEvaluationReviewController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

Route::middleware('auth')->get('/', function () {
    return view('welcome');
});

// secured routes
Route::middleware('auth')->group(function () {
    // voice audits
    Route::prefix('voice-audits')
        ->get('/{voice_evaluation}', [VoiceAuditController::class, 'index'])
        ->name('voice-audits.index');
    Route::prefix('voice-audits')
        ->get('/create/{voice_evaluation}', [VoiceAuditController::class, 'create'])
        ->name('voice-audits.create');
    Route::prefix('voice-audits')
        ->get('/show/{voice_audit}', [VoiceAuditController::class, 'show'])
        ->name('voice-audits.show');
    Route::prefix('voice-audits')
        ->get('/qa-pending-sale-sheet/{voice_evaluation}', [VoiceAuditController::class, 'qaPendingSaleSheet'])
        ->name('voice-audits.qa-pending-sale-sheet');
    Route::prefix('voice-audits')
        ->get('/get-voice-audit-detail/{voice_audit}', [VoiceAuditController::class, 'getAuditDetail'])
        ->name('voice-audits.voice-audit-detail');
    Route::prefix('voice-audits')
        ->post('/assign-team-lead-voice-audits', [VoiceAuditController::class, 'assignTeamLeadAudit'])
        ->name('voice-audits.assign-voice-audits');
    Route::resource('/voice-audits', VoiceAuditController::class)->except(['create', 'index', 'show', 'qaPendingSaleSheet', 'getAuditDetails']);
    Route::prefix('compliance-voice-audits')
        ->get('/{voice_evaluation}', [AssignedVoiceAuditController::class, 'index'])
        ->name('compliance-voice-audits.index');
    Route::prefix('compliance-voice-audits')
        ->get('/create/{voice_evaluation}', [AssignedVoiceAuditController::class, 'create'])
        ->name('compliance-voice-audits.create');
    Route::prefix('compliance-voice-audits')
        ->get('/show/{assigned_voice_audit_detail}', [AssignedVoiceAuditController::class, 'show'])
        ->name('compliance-voice-audits.show');
    Route::prefix('compliance-voice-audits')
        ->get('/show-all-compliance-voice-audits/{voice_audit}', [AssignedVoiceAuditController::class, 'showAllComplianceAudits'])
        ->name('compliance-voice-audits.show-all');
    Route::resource('/compliance-voice-audits', AssignedVoiceAuditController::class)->except(['index', 'create', 'show', 'showAllComplianceAudits']);

    // voice audit appeals
    Route::prefix('voice-audit-appeals')
        ->get('/', [VoiceAuditAppealController::class, 'index'])
        ->name('voice-audit-appeals.index');
    Route::prefix('voice-audit-appeals')
        ->get('/show/{voice_audit}', [VoiceAuditAppealController::class, 'show'])
        ->name('voice-audit-appeals.show');
    Route::prefix('voice-audit-appeals')
        ->get('/edit/{voice_audit}', [VoiceAuditAppealController::class, 'edit'])
        ->name('voice-audit-appeals.edit');
    Route::prefix('voice-audit-appeals')
        ->delete('/destroy/{voice_audit}', [VoiceAuditAppealController::class, 'destroy'])
        ->name('voice-audit-appeals.destroy');

    // voice audit actions
    Route::prefix('voice-audit-actions')
        ->get('/', [VoiceAuditActionController::class, 'index'])
        ->name('voice-audit-actions.index');
    Route::prefix('voice-audit-actions')
        ->get('/show/{voice_audit}', [VoiceAuditActionController::class, 'show'])
        ->name('voice-audit-actions.show');

    // voice evaluation reviews
    Route::prefix('voice-evaluation-reviews')
        ->get('/{status?}', [VoiceEvaluationReviewController::class, 'index'])
        ->name('voice-evaluation-reviews.index');
    Route::prefix('voice-evaluation-reviews')
        ->get('/show/{voice_audit}/{status?}', [VoiceEvaluationReviewController::class, 'show'])
        ->name('voice-evaluation-reviews.show');
    Route::prefix('voice-evaluation-reviews')
        ->put('/update/{voice_audit}/{status?}', [VoiceEvaluationReviewController::class, 'update'])
        ->name('voice-evaluation-reviews.update');
    Route::prefix('voice-evaluation-reviews/update-actions')
        ->put('/update/{voice_audit}/{status?}', [VoiceEvaluationReviewController::class, 'updateAction'])
        ->name('voice-evaluation-reviews.update-actions');
    // my voice evaluation reviews
    Route::prefix('my-voice-reviews')
        ->get('/', [VoiceEvaluationReviewController::class, 'myEvaluationReviews'])
        ->name('voice-evaluation-reviews.my-reviews');
    Route::prefix('my-voice-reviews')
        ->get('/show/{voice_audit}', [VoiceEvaluationReviewController::class, 'myEvaluationReviewShow'])
        ->name('voice-evaluation-reviews.my-reviews-show');
    // appeals
    Route::prefix('my-voice-appeals')
        ->get('/', [VoiceEvaluationReviewController::class, 'appeals'])
        ->name('voice-evaluation-reviews.my-appeals');
    Route::prefix('my-voice-appeals')
        ->get('/show/{voice_audit}', [VoiceEvaluationReviewController::class, 'appealShow'])
        ->name('voice-evaluation-reviews.my-appeals-show');
    // actions
    Route::prefix('my-voice-actions')
        ->get('/', [VoiceEvaluationReviewController::class, 'actions'])
        ->name('voice-evaluation-reviews.my-actions');
    Route::prefix('my-voice-actions')
        ->get('/show/{voice_audit}', [VoiceEvaluationReviewController::class, 'actionShow'])
        ->name('voice-evaluation-reviews.my-actions-show');

    Route::prefix('export')->group(function () {
        Route::get('/voice-audits', [ExportController::class, 'voiceAudits'])->name('export.voice-audits');
        Route::get('/compliance-voice-audits', [ExportController::class, 'complianceVoiceAudits'])->name('export.compliance-voice-audits');
        Route::get('/evaluations/{status?}', [ExportController::class, 'evaluations'])->name('export.evaluations-export');
        Route::get('/appeals', [ExportController::class, 'appeals'])->name('export.appeals-export');
        Route::get('/actions', [ExportController::class, 'actions'])->name('export.actions-export');
    });

    Route::prefix('voice-reports')->group(function () {
        Route::get('/timesheet', [VoiceReportController::class, 'timesheet'])->name('voice-reports.timesheet');
        Route::get('/evaluators', [VoiceReportController::class, 'evaluators'])->name('voice-reports.evaluators');
        Route::get('/campaigns', [VoiceReportController::class, 'campaigns'])->name('voice-reports.campaigns');
        Route::get('/team-leads', [VoiceReportController::class, 'teamLeads'])->name('voice-reports.team-leads');
        Route::get('/associates', [VoiceReportController::class, 'associates'])->name('voice-reports.associates');
        Route::get('/fatals', [VoiceReportController::class, 'fatals'])->name('voice-reports.fatals');
    });

    Route::prefix('solar-lts')->group(function () {
        Route::get('/voice-evaluations/', [SolarLtEvaluationController::class, 'index'])->name('solar-lts.voice-evaluations.index');

        /** Below are all the routes that deal with categories */
        Route::get('/voice-evaluations/categories/create', [SolarLtCategoryController::class, 'create'])->name('solar-lts.voice-evaluations.categories.create');
        Route::post('/voice-evaluations/categories/store', [SolarLtCategoryController::class, 'store'])->name('solar-lts.voice-evaluations.categories.store');
        Route::get('/voice-evaluations/categories/edit/{category}', [SolarLtCategoryController::class, 'edit'])->name('solar-lts.voice-evaluations.categories.edit');
        Route::put('/voice-evaluations/categories/update/{category}', [SolarLtCategoryController::class, 'update'])->name('solar-lts.voice-evaluations.categories.update');
        Route::get('/voice-evaluations/categories/delete/{category}', [SolarLtCategoryController::class, 'destroy'])->name('solar-lts.voice-evaluations.categories.destroy');

        /** Below are all the routes that deal with Datapoints */
        Route::get('/voice-evaluations/datapoints/create/{category}', [SolarLtDatapointController::class, 'create'])->name('solar-lts.voice-evaluations.datapoints.create');
        Route::post('/voice-evaluations/datapoints/store/{category}', [SolarLtDatapointController::class, 'store'])->name('solar-lts.voice-evaluations.datapoints.store');
        Route::get('/voice-evaluations/datapoints/edit/{datapoint}', [SolarLtDatapointController::class, 'edit'])->name('solar-lts.voice-evaluations.datapoints.edit');
        Route::put('/voice-evaluations/datapoints/update/{datapoint}', [SolarLtDatapointController::class, 'update'])->name('solar-lts.voice-evaluations.datapoints.update');
        Route::get('/voice-evaluations/datapoints/delete/{datapoint}', [SolarLtDatapointController::class, 'destroy'])->name('solar-lts.voice-evaluations.datapoints.destroy');

        Route::prefix('audit')->group(function () {
            Route::get('/', [AuditController::class, 'index'])->name('solar-lts.audit.index');
            Route::get('/create', [AuditController::class, 'create'])->name('solar-lts.audit.create');
            Route::get('/get-user-detail/{id}', [AuditController::class, 'getUserDetail'])->name('solar-lts.audit.get-user-detail');
            Route::post('/store', [AuditController::class, 'store'])->name('solar-lts.audit.store');
            Route::get('/edit/{audit}', [AuditController::class, 'edit'])->name('solar-lts.audit.edit');
            Route::put('/update/{audit}', [AuditController::class, 'update'])->name('solar-lts.audit.update');
            Route::get('/delete/{audit}', [AuditController::class, 'destroy'])->name('solar-lts.audit.destroy');
            Route::get('/audit-report', [AuditController::class, 'report'])->name('solar-lts.audit.report');
            Route::post('/audit-report', [AuditController::class, 'getDataByDate']);
        });
    });
    Route::middleware('admin')->group(function () {
        // voice audits & setup

        // voice evaluation actions
        Route::resource('/voice-evaluation-actions', VoiceEvaluationActionController::class);
        // voice evaluations
        Route::resource('/voice-evaluations', VoiceEvaluationController::class);
        // data points
        Route::prefix('datapoints')
            ->get('/create/{datapoint_category}', [DataPointController::class, 'create'])
            ->name('datapoints.create');
        Route::resource('/datapoints', DataPointController::class)->except(['create', 'index']);
        // data points categories
        Route::get('/datapoint-categories/create/{voice_evaluation}', [DatapointCategoryController::class, 'create'])->name('datapoint-categories.create');
        Route::resource('/datapoint-categories', DatapointCategoryController::class)->except(['create', 'index']);
        /* Route::post('datapoint-categories/get-project-detail/{id}', [DatapointCategoryController::class, 'getProjectList'])->name('datapoint-categories.get-project-details'); */
        // custom fields
        Route::prefix('voice-custom-fields')
            ->get('/create/{voice_evaluation}', [VoiceCustomFieldController::class, 'create'])
            ->name('voice-custom-fields.create');
        Route::resource('/voice-custom-fields', VoiceCustomFieldController::class)->except(['create', 'index']);

        // campaigns
        Route::resource('/campaigns', CampaignController::class);

        // roles
        Route::resource('/roles', RoleController::class);

        // users
        Route::prefix('users')->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('users.index');
            Route::get('/create', [UserController::class, 'create'])->name('users.create');
            Route::post('/store', [UserController::class, 'store'])->name('users.store');
            Route::get('/edit/{user}', [UserController::class, 'edit'])->name('users.edit');
            Route::put('/edit/{user}', [UserController::class, 'update'])->name('users.update');
            Route::get('/delete/{user}', [UserController::class, 'destroy'])->name('users.delete');
        });
    });

    Route::get('get-campaign-users/{campaign_id}', [UserController::class, 'campaignUsers'])->name('campaign-users');

    Route::get('get-user-detail/{user_id}', [UserController::class, 'getDetail'])->name('user-detail');

    Route::get('/home', [HomeController::class, 'index'])->name('home');

    Route::get('logout', [LoginController::class, 'logout'])->name('logout');

    Route::get('/', [HomeController::class, 'index'])->name('main');
});

// unsecure routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'index'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
});

Route::get('/test', [HomeController::class, 'test'])->name('test');
