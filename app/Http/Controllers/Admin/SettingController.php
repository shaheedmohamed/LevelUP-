<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $rows = DB::table('settings')->pluck('value','key');
        return response()->json($rows);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'site_slogan' => ['nullable','string','max:255'],
            'site_description' => ['nullable','string'],
        ]);
        foreach ($data as $key=>$value) {
            DB::table('settings')->updateOrInsert(
                ['key'=>$key],
                ['value'=>$value, 'updated_at'=>now(), 'created_at'=>now()]
            );
        }
        return response()->json(['success'=>true]);
    }
}
