<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Models\User;
use App\Models\Setting;
use App\Http\Controllers\Admin\SubjectController as AdminSubjectController;
use App\Http\Controllers\Admin\BookController as AdminBookController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\StatsController;
use Illuminate\Support\Facades\DB;

Route::post('/register', [RegisterController::class, 'register']);
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LogoutController::class, 'logout'])->middleware('auth:sanctum');

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Self profile endpoints
Route::middleware('auth:sanctum')->group(function(){
    Route::get('/profile', [ProfileController::class, 'show']);
    Route::post('/profile', [ProfileController::class, 'update']);
});

// Admin routes
Route::middleware(['auth:sanctum', 'isAdmin'])->prefix('admin')->group(function () {
    Route::get('/stats', function (Request $request) {
        return response()->json([
            'ok' => true,
            'user' => $request->user()->only(['id','name','email','role']),
            'timestamp' => now()->toISOString(),
        ]);
    });

    Route::get('/users', function () {
        return User::query()
            ->select(['id','name','email','role','created_at'])
            ->orderByDesc('id')
            ->limit(50)
            ->get();
    });

    // Minimal logs endpoint (static for now)
    Route::get('/logs', function () {
        return response()->json([
            ['id' => 1, 'level' => 'info', 'message' => 'App started', 'time' => now()->subMinutes(10)->toISOString()],
            ['id' => 2, 'level' => 'warning', 'message' => 'High memory usage', 'time' => now()->subMinutes(5)->toISOString()],
            ['id' => 3, 'level' => 'error', 'message' => 'Failed job retry scheduled', 'time' => now()->toISOString()],
        ]);
    });

    // Settings endpoints (persisted in DB)
    Route::get('/settings', function () {
        $setting = Setting::query()->first();
        if (!$setting) {
            $setting = Setting::create([
                'site_name' => config('app.name', 'LevelUP'),
                'maintenance' => false,
            ]);
        }
        return response()->json($setting);
    });

    Route::put('/settings', function (Request $request) {
        $data = $request->validate([
            'site_name' => ['required','string','max:255'],
            'maintenance' => ['sometimes','boolean'],
        ]);
        $setting = Setting::query()->first();
        if (!$setting) {
            $setting = new Setting();
        }
        $setting->site_name = $data['site_name'];
        if (array_key_exists('maintenance', $data)) {
            $setting->maintenance = (bool) $data['maintenance'];
        }
        $setting->save();
        return response()->json($setting->refresh());
    });

    // Admin: Subjects
    Route::get('/subjects', [AdminSubjectController::class, 'index']);
    Route::post('/subjects', [AdminSubjectController::class, 'store']);

    // Admin: Books
    Route::get('/books', [AdminBookController::class, 'index']);
    Route::post('/books', [AdminBookController::class, 'store']);

    // Admin: Users management
    Route::get('/users', [AdminUserController::class, 'index']);
    Route::get('/users/{id}', [AdminUserController::class, 'show']);
    Route::post('/users/{id}', [AdminUserController::class, 'update']);
    Route::post('/users/{id}/password', [AdminUserController::class, 'resetPassword']);

    // Admin: Stats (real data)
    Route::get('/dashboard/kpis', [StatsController::class, 'kpis']);
    Route::get('/dashboard/charts/users-growth', [StatsController::class, 'usersGrowth']);
    Route::get('/dashboard/charts/visits-trend', [StatsController::class, 'visitsTrend']);
    Route::get('/dashboard/charts/daily-logins', [StatsController::class, 'dailyLogins']);
    Route::get('/dashboard/charts/sources', [StatsController::class, 'sources']);

    // Admin: Login history per user
    Route::get('/users/{id}/login-logs', function ($id) {
        $limit = (int) request('limit', 50);
        $limit = $limit > 0 && $limit <= 200 ? $limit : 50;
        $logs = DB::table('login_logs')
            ->where('user_id', $id)
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get();
        return response()->json($logs);
    });
});

// Public subjects endpoints
Route::get('/subjects', [SubjectController::class, 'index']);
Route::get('/subjects/{slug}', [SubjectController::class, 'show']);
