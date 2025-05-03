<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function showProfile()
    {
        return view('profile.show', ['user' => auth()->user()]);
    }

    public function editProfile()
    {
        return view('profile.edit', ['user' => auth()->user()]);
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => ['sometimes', 'string', 'max:255'],
            'username' => ['sometimes', 'string', 'max:255', 'unique:users,username,' . $user->id],
            'email' => ['sometimes', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone' => ['sometimes', 'string', 'max:20', 'unique:users,phone,' . $user->id],
            'bio' => ['nullable', 'string'],
            'image' => ['nullable', 'string']
        ]);

        $this->userRepository->update($user->id, $validated);

        return redirect()->route('profile.show')->with('success', 'Profile updated successfully');
    }

    public function showPasswordForm()
    {
        return view('profile.password');
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = $request->user();

        if (!Hash::check($validated['current_password'], $user->password)) {
            return back()->withErrors([
                'current_password' => 'The current password is incorrect.',
            ]);
        }

        $this->userRepository->update($user->id, [
            'password' => Hash::make($validated['password'])
        ]);

        return redirect()->route('profile.show')->with('success', 'Password updated successfully');
    }
}
