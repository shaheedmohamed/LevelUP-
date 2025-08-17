<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::query()
            ->select(['id','name','email','phone','role','created_at'])
            ->orderByDesc('id')
            ->paginate(20);
        return response()->json($users);
    }

    public function updateRole(Request $request, User $user)
    {
        $data = $request->validate([
            'role' => ['required', Rule::in(['admin','user'])],
        ]);
        $user->role = $data['role'];
        $user->save();
        return response()->json(['success'=>true,'user'=>$user]);
    }

    public function destroy(Request $request, User $user)
    {
        $user->delete();
        return response()->json(['success'=>true]);
    }
}
