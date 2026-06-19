<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class InjectDemoUI
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if (!config('demo.enabled')) {
            return $response;
        }

        if (!$request->is('admin*')) {
            return $response;
        }

        if (!method_exists($response, 'getContent')) {
            return $response;
        }

        $content = $response->getContent();
        if (str_contains($content, '</body>')) {
            $script = '<script src="/js/demo-restrictions.js?v=3"></script>';
            $response->setContent(str_replace('</body>', $script . '</body>', $content));
        }

        return $response;
    }
}
