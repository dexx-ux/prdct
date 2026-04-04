<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::where('role', '!=', 'admin')
            ->latest()
            ->paginate(10);

        return view('admin.users.index', compact('users'));
    }

    // ✅ SINGLE DELETE
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->status !== 'inactive') {
            return back()->with('error', 'Cannot delete active user.');
        }

        $user->delete();

        return back()->with('success', 'User deleted successfully.');
    }

    // ✅ BULK DELETE (NO stdClass ERROR)
public function destroySelected(Request $request)
{
    $request->validate([
        'user_ids' => 'required|string'
    ]);

    $userIds = explode(',', $request->user_ids);

    $deleted = User::whereIn('id', $userIds)
        ->where('status', 'inactive')
        ->delete();

    if ($deleted == 0) {
        return back()->with('error', 'No inactive users deleted.');
    }

    return back()->with('success', "$deleted user(s) deleted successfully.");
}
}

