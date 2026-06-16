<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use FalconCms\Core\Http\Middleware\BuilderShortcodeMiddleware;

/**
 * BuilderServiceProvider
 *
 * Isolated service provider for all page-builder shortcode sync logic.
 * Does NOT touch any existing files, controllers, or permission code.
 *
 * New files live inside the package:
 *   vendor/tareqcodex/lazy-cms-rebuild/src/Services/BuilderShortcodeConverter.php
 *   vendor/tareqcodex/lazy-cms-rebuild/src/Http/Middleware/BuilderShortcodeMiddleware.php
 *   vendor/tareqcodex/lazy-cms-rebuild/public/assets/js/lazy-builder-converter.js
 *
 * Responsibilities:
 *  1. Registers BuilderShortcodeMiddleware on the 'web' middleware group so
 *     that [lazy_section] shortcodes submitted via the rich editor are
 *     automatically converted to builder JSON before reaching the controller.
 *
 *  2. Injects lazy-builder-converter.js into every admin page via the
 *     lazy_admin_footer action hook (defined in the package's helpers.php).
 *     The script handles display-side (JSON → shortcodes) and form-submit-side
 *     (shortcodes → JSON) conversions entirely in the browser.
 */
class BuilderServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // 1. Server-side middleware: converts [lazy_section] shortcodes → JSON on save
        $this->app['router']->pushMiddlewareToGroup('web', BuilderShortcodeMiddleware::class);

        // 2. Client-side script injection via the package's admin footer hook
        if (function_exists('add_lazy_action')) {
            add_lazy_action('lazy_admin_footer', function () {
                $jsPath = public_path('vendor/cms-dashboard/js/lazy-builder-converter.js');
                $src    = asset('vendor/cms-dashboard/js/lazy-builder-converter.js');
                $ver    = file_exists($jsPath) ? filemtime($jsPath) : '1';
                echo '<script src="' . $src . '?v=' . $ver . '"></script>';
            }, 20);
        }
    }
}
