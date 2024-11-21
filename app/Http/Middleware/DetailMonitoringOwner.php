<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class DetailMonitoringOwner
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // dd($request);
        $currentTeacher = Auth::user();
        $monitoringExists = DB::table('monitorings')->where('id', $request->id)->exists();

        if ($monitoringExists) {
            $monitoring = DB::table('monitorings')->where('id', $request[0]['monitoring_id'])->get();

            // dd($monitoring);
            if ($currentTeacher->nik === $monitoring[0]->teachers_nik) {
                return $next($request);
            } else {
                return response()->json(['message' => 'you are not the owner']);
            }
        } else {
            return response()->json(['message' => 'data not found']);
        }

        // dd($monitoringExists);
        // fdsfsdfjkfhsj
    }
}
