<?php

namespace Modules\Rapat\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotulisMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        $agendaRapat = $request->rapatAgenda;
        if ($agendaRapat && ($agendaRapat->notulis_id === $user->id) || $agendaRapat && ($agendaRapat->pimpinan_id === $user->id)) {
            return $next($request);
        }
        abort(403);
    }
}
