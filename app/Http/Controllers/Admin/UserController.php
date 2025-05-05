<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
            return redirect()->route('home');
        }

        return view('admin.users.show', compact('user'));
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
}
