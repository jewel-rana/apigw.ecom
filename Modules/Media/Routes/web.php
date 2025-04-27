<?php

use Illuminate\Support\Facades\Route;
use Modules\Media\Http\Controllers\MediaController;

Route::prefix('dashboard')->group(function() {
    Route::delete('/media/delete', [MediaController::class, 'delete'])->name('media.delete');
    Route::post('media/jqupload', [MediaController::class, 'jqUpload'])->name('media.jqupload');
    Route::resource('media', MediaController::class);
});
