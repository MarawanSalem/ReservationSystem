<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('profile.edit');
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user->id)],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => ['required', 'string', 'max:20', Rule::unique('users')->ignore($user->id)],
            'bio' => ['nullable', 'string', 'max:500'],
            'image' => ['nullable', 'image', 'max:2048'], // 2MB max
            'remove_image' => ['nullable', 'boolean'],
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($user->image && !str_contains($user->image, 'ui-avatars')) {
                Storage::delete($user->image);
            }

            // Store new image
            $path = $request->file('image')->store('profile-photos', 'public');
            $user->image = $path;
        }
        // Handle image removal
        elseif ($request->boolean('remove_image')) {
            if ($user->image && !str_contains($user->image, 'ui-avatars')) {
                Storage::delete($user->image);
            }
            $user->image = null;
        }

        // Update other fields
        $user->update([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'phone' => $request->phone,
            'bio' => $request->bio,
        ]);

        return back()->with('success', 'Profile updated successfully!');
    }
}
