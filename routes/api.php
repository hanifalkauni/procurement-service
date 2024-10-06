<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProcurementRequestController;

Route::group(['middleware' => 'auth.procurement'], function () {
    Route::post('procurement-request', [ProcurementRequestController::class, 'createProcurementRequest']);
    Route::get('procurement-requests', [ProcurementRequestController::class, 'listProcurementRequests']);

    Route::put('procurement-request/{id}/approve', [ProcurementRequestController::class, 'approveProcurementRequest']);
    Route::put('procurement-request/{id}/reject', [ProcurementRequestController::class, 'rejectProcurementRequest']);
});


