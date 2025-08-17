<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function stats(Request $request)
    {
        $totalUsers = DB::table('users')->count();
        $admins = DB::table('users')->where('role','admin')->count();
        $new7d = DB::table('users')->where('created_at','>=', now()->subDays(7))->count();

        $signupsDaily = DB::table('users')
            ->selectRaw('DATE(created_at) as d, COUNT(*) as c')
            ->where('created_at','>=', now()->subDays(14))
            ->groupBy('d')->orderBy('d')->get();

        $lastLogins = DB::table('login_logs')->orderByDesc('id')->limit(10)->get();

        // Approximate online users: distinct users who had a successful login in the last 10 minutes
        $online = DB::table('login_logs')
            ->where('success', true)
            ->where('created_at', '>=', now()->subMinutes(10))
            ->distinct('user_id')
            ->count('user_id');

        return response()->json([
            'total_users' => $totalUsers,
            'admins' => $admins,
            'new_last_7d' => $new7d,
            'signups_daily' => $signupsDaily,
            'last_logins' => $lastLogins,
            'online' => $online,
        ]);
    }
}
