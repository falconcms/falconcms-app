<?php

use FalconSlider\Http\Controllers\SliderController;
use Illuminate\Support\Facades\Route;

// Admin routes — plugin route files load before the CMS web.php, so declare the
// admin middleware stack ourselves (login + permission enforcement).
Route::prefix('admin')
    ->name('admin.')
    ->middleware([
        'web',
        \FalconCms\Core\Http\Middleware\SecurityHeadersMiddleware::class,
        \FalconCms\Core\Http\Middleware\AdminMiddleware::class,
    ])
    ->group(function () {
        // URL path is /admin/falcon-slider; route names stay admin.sliders.* so all
        // route('admin.sliders.*') references keep working (they now generate the new path).
        Route::get('falcon-slider', [SliderController::class, 'index'])->name('sliders.index');
        Route::post('falcon-slider', [SliderController::class, 'store'])->name('sliders.store');
        Route::post('falcon-slider/import', [SliderController::class, 'import'])->name('sliders.import');
        Route::post('falcon-slider/bulk', [SliderController::class, 'bulk'])->name('sliders.bulk');
        Route::get('falcon-slider/{slider}/export', [SliderController::class, 'export'])->name('sliders.export');
        Route::post('falcon-slider/{slider}/duplicate', [SliderController::class, 'duplicate'])->name('sliders.duplicate');
        Route::post('falcon-slider-templates', [SliderController::class, 'saveTemplate'])->name('sliders.templates.save');
        Route::delete('falcon-slider-templates/{template}', [SliderController::class, 'deleteTemplate'])->name('sliders.templates.delete');
        Route::get('falcon-slider/{slider}/edit', [SliderController::class, 'edit'])->name('sliders.edit');
        Route::get('falcon-slider/{slider}/preview', [SliderController::class, 'preview'])->name('sliders.preview');
        Route::post('falcon-slider/{slider}/preview-live', [SliderController::class, 'previewLive'])->name('sliders.previewLive');
        Route::get('falcon-slider/{slider}/revisions', [SliderController::class, 'revisions'])->name('sliders.revisions');
        Route::put('falcon-slider/{slider}', [SliderController::class, 'update'])->name('sliders.update');
        Route::delete('falcon-slider/{slider}', [SliderController::class, 'destroy'])->name('sliders.destroy');
    });
