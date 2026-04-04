<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        // Update all users' status based on last login
        $this->updateAllUserStatuses();
        
        // Get users with updated statuses
        $users = User::where('role', '!=', 'admin')
                     ->latest()
                     ->paginate(10);
        
        return view('admin.users.index', compact('users'));
    }
    
    private function updateAllUserStatuses()
    {
        $users = User::where('role', '!=', 'admin')->get();
        
        foreach ($users as $user) {
            $user->updateStatusByLastLogin();
        }
    }
    
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        
        if ($user->status === 'inactive') {
            $user->delete();
            return redirect()->route('admin.users.index')
                           ->with('success', 'User deleted successfully.');
        }
        
        return redirect()->route('admin.users.index')
                       ->with('error', 'Cannot delete active user.');
    }
    
    public function destroySelected(Request $request)
    {
        // Get user_ids from request
        $userIdsString = $request->input('user_ids');
        
        // If it's a string (comma-separated), convert to array
        if (is_string($userIdsString)) {
            $userIds = explode(',', $userIdsString);
        } 
        // If it's already an array
        elseif (is_array($userIdsString)) {
            $userIds = $userIdsString;
        }
        // If it's JSON, decode it
        else {
            $userIds = json_decode($userIdsString, true);
        }
        
        // Remove any empty values and convert to integers
        $userIds = array_filter($userIds);
        $userIds = array_map('intval', $userIds);
        
        if (empty($userIds)) {
            return redirect()->route('admin.users.index')
                           ->with('error', 'No users selected.');
        }
        
        // Log the user IDs for debugging
        \Log::info('Deleting users: ', $userIds);
        
        // Only delete users with status 'inactive'
        $deletedCount = User::whereIn('id', $userIds)
                            ->where('status', 'inactive')
                            ->delete();
        
        if ($deletedCount > 0) {
            return redirect()->route('admin.users.index')
                           ->with('success', "$deletedCount user(s) deleted successfully.");
        }
        
        return redirect()->route('admin.users.index')
                       ->with('error', 'Only inactive users can be deleted.');
    }
}