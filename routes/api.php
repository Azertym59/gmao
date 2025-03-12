Route::prefix('print')->group(function () {
    Route::post('/job/create', [App\Http\Controllers\Api\PrintController::class, 'createJob']);
    Route::post('/test', [App\Http\Controllers\Api\PrintController::class, 'testPrint']);
    Route::get('/jobs/pending', [App\Http\Controllers\Api\PrintController::class, 'getPendingJobs']);
    Route::post('/job/{id}/status', [App\Http\Controllers\Api\PrintController::class, 'updateJobStatus']);
});