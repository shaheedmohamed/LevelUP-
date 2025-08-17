<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class LogController extends Controller
{
    public function index(Request $request)
    {
        $logs = DB::table('login_logs')
            ->leftJoin('users', 'users.id', '=', 'login_logs.user_id')
            ->select('login_logs.*', 'users.name')
            ->orderByDesc('login_logs.id')
            ->limit(50)
            ->get();

        return response()->json($logs);
    }
}
