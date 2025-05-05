<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Redirect admin users to admin dashboard
        if (Auth::user()->roles()->where('name', 'admin')->exists()) {
            return redirect()->route('admin.dashboard');
        }

        $query = Service::query();

        // Search functionality
        if ($search = request('search')) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Category filter
        if ($category = request('category')) {
            if ($category !== 'all') {
                $query->where('category', $category);
            }
        }

        // Sorting
        $sort = request('sort', 'rating');
        $direction = request('direction', 'desc');
        $query->orderBy($sort, $direction);

        $services = $query->paginate(6);
        $categories = Service::distinct()->pluck('category');

        return view('home', compact('services', 'categories'));
    }
}
