<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Service;
use App\Models\Reservation;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
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

        $totalUsers = User::count();
        $totalServices = Service::count();
        $totalReservations = Reservation::count();
        $todayReservations = Reservation::whereDate('created_at', today())->count();
        $recentActivities = Activity::with('user')
            ->latest()
            ->take(10)
            ->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalServices',
            'totalReservations',
            'todayReservations',
            'recentActivities'
        ));
    }
}
