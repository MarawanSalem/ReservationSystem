<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Notifications\AdminNotification;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $isAdmin = DB::table('model_has_roles')
            ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->where('model_has_roles.model_id', $user->id)
            ->where('roles.name', 'admin')
            ->exists();

        if (!$isAdmin) {
            return redirect()->route('home');
        }

        $query = User::with('roles');

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $users = $query->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function show(User $user)
    {
        $authUser = Auth::user();
        $isAdmin = DB::table('model_has_roles')
            ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->where('model_has_roles.model_id', $authUser->id)
            ->where('roles.name', 'admin')
            ->exists();

        if (!$isAdmin) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'username' => $user->username,
            'phone' => $user->phone,
            'image' => $user->image,
            'roles' => $user->roles,
            'created_at' => $user->created_at,
        ]);
    }

    public function destroy(User $user)
    {
        $authUser = Auth::user();
        $isAdmin = DB::table('model_has_roles')
            ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->where('model_has_roles.model_id', $authUser->id)
            ->where('roles.name', 'admin')
            ->exists();

        if (!$isAdmin) {
            return redirect()->route('home');
        }

        // Prevent deleting admin users
        if ($user->hasRole('admin')) {
            return back()->with('error', 'Cannot delete admin users.');
        }

        $user->delete();
        return back()->with('success', 'User deleted successfully.');
    }

    public function sendNotification(Request $request)
    {
        try {
            $authUser = Auth::user();
            $isAdmin = DB::table('model_has_roles')
                ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
                ->where('model_has_roles.model_id', $authUser->id)
                ->where('roles.name', 'admin')
                ->exists();

            if (!$isAdmin) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access'
                ], 403);
            }

            $request->validate([
                'user_ids' => 'required|string',
                'title' => 'required|string|max:255',
                'body' => 'required|string',
            ]);

            $userIds = explode(',', $request->user_ids);
            $users = User::whereIn('id', $userIds)->get();

            if ($users->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No valid users found'
                ], 404);
            }

            foreach ($users as $user) {
                $user->notify(new AdminNotification(
                    $request->title,
                    $request->body,
                    $authUser
                ));
            }

            return response()->json([
                'success' => true,
                'message' => 'Notifications sent successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Notification Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while sending notifications',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
