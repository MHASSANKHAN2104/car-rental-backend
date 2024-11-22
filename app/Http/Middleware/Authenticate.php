<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo($request)
{
    // For API requests, return a JSON response instead of redirecting to a login page
    if ($request->expectsJson()) {
        abort(response()->json(['error' => 'Unauthorized'], 401));
    }

    // If for web requests, you may handle it differently (but not needed in your case)
    return route('login'); // Remove this if you're not using web-based login.
}

}
