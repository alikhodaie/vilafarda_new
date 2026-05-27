<?php

namespace App\Http\Middleware;

use Closure;
use Jenssegers\Agent\Agent;

class DetectMobile
{
    public function handle($request, Closure $next)
    {
        $agent = new Agent();

        if ($agent->isMobile()) {
            //flag for mobile or desktop checking
            $request->merge(['is_mobile' => true]);
        } else {
            $request->merge(['is_mobile' => false]);
        }

        return $next($request);
    }
}
