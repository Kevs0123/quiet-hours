<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureBookingStepCompleted
{
    /**
     * Block access to a booking step until the required earlier step
     * has been completed. Usage in routes: ->middleware('booking.step:2')
     */
    public function handle(Request $request, Closure $next, int $requiredStep): Response
    {
        $currentStep = (int) session('booking.step', 0);

        if ($currentStep < $requiredStep) {
            return redirect()
                ->route('booking.home')
                ->with('error', 'Please complete the previous booking step first.');
        }

        return $next($request);
    }
}
