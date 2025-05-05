<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ServiceController extends Controller
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

        $query = Service::with('addedBy');

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('service_provider', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%");
            });
        }

        $services = $query->latest()->paginate(10);
        return view('admin.services.index', compact('services'));
    }

    public function create()
    {
        if (!Auth::user()->hasRole('admin')) {
            return redirect()->route('home');
        }

        return view('admin.services.create');
    }

    public function store(Request $request)
    {
        if (!Auth::user()->hasRole('admin')) {
            return redirect()->route('home');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:services',
            'description' => 'required|string',
            'service_provider' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'duration' => 'required|integer|min:1',
            'category' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('services', 'public');
            $validated['image'] = $path;
        }

        $validated['added_by'] = Auth::id();
        $validated['rating'] = 0;

        Service::create($validated);

        return redirect()->route('admin.services.index')
            ->with('success', 'Service created successfully.');
    }

    public function show(Service $service)
    {
        if (!Auth::user()->hasRole('admin')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return response()->json([
            'id' => $service->id,
            'name' => $service->name,
            'description' => $service->description,
            'service_provider' => $service->service_provider,
            'location' => $service->location,
            'price' => $service->price,
            'duration' => $service->duration,
            'category' => $service->category,
            'rating' => $service->rating,
            'image' => $service->image ? Storage::url($service->image) : null,
            'added_by' => $service->addedBy->name,
            'created_at' => $service->created_at->format('Y-m-d H:i:s'),
            'reservations_count' => $service->reservations()->count()
        ]);
    }

    public function edit(Service $service)
    {
        if (!Auth::user()->hasRole('admin')) {
            return redirect()->route('home');
        }

        return view('admin.services.edit', compact('service'));
    }

    public function update(Request $request, Service $service)
    {
        if (!Auth::user()->hasRole('admin')) {
            return redirect()->route('home');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:services,name,' . $service->id,
            'description' => 'required|string',
            'service_provider' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'duration' => 'required|integer|min:1',
            'category' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($service->image) {
                Storage::disk('public')->delete($service->image);
            }
            $path = $request->file('image')->store('services', 'public');
            $validated['image'] = $path;
        }

        $service->update($validated);

        return redirect()->route('admin.services.index')
            ->with('success', 'Service updated successfully.');
    }

    public function destroy(Service $service)
    {
        if (!Auth::user()->hasRole('admin')) {
            return redirect()->route('home');
        }

        // Delete image if exists
        if ($service->image) {
            Storage::disk('public')->delete($service->image);
        }

        $service->delete();

        return back()->with('success', 'Service deleted successfully.');
    }
}
