<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function getUserInfo(Request $request)
    {
        $user = auth()->user();
        return response()->json([
            'success' => true,
            'phone' => $user->phone ?? null,  
            'email' => $user->email ?? null,
            'address' => $user->address ?? null
        ]);
    }
}