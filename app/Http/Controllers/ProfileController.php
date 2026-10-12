<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('profile.edit', ['user' => Auth::user()]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'profile_picture' => ['nullable', 'image', 'max:2048'],
        ]);

        $user->name = $data['name'];
        $user->phone = $data['phone'] ?? null;

        if ($request->hasFile('profile_picture')) {
            // Remove the previous picture if one exists.
            if ($user->profile_picture) {
                Storage::disk('public')->delete($user->profile_picture);
            }
            $user->profile_picture = $request->file('profile_picture')->store('profiles', 'public');
        }

        $user->save();

        return back()->with('success', 'Profile updated successfully.');
    }
}
