<?php
namespace Modules\Rapat\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PimpinanRapatMiddleware
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
        $user        = Auth::user();
        $agendaRapat = $request->rapatAgenda;
        if ($user->hasAnyRole(['pimpinan', 'pejabat', 'sekretaris'])) {
            return $next($request);
        }
        if ($user->hasAnyRole(['pimpinan', 'pejabat', 'sekretaris']) && $agendaRapat->pegawai_username === $user->pegawai->username) {
            return $next($request);
        }
        if (Auth::user()->pegawai->ketuaPanitia->isNotEmpty()) {
            return $next($request);
        }
        abort(403);
    }
}
