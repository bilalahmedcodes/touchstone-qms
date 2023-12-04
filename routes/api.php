<?php

use App\Http\Controllers\API\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/get-voice-audits', [ApiController::class,'getQANotes'])->name('get-voice-audits');
Route::post('/voice-audit-appeal', [ApiController::class, 'generateAppeal']);
Route::get('/update-user-in-voice-audits', [ApiController::class, 'updateVoiceAudit']);
Route::get('/getAllVoiceAudits', [ApiController::class, 'getAllVoiceAudits']);
Route::get('/updateAudit', [ApiController::class, 'updateAudit']);