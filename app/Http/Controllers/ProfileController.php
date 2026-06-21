<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('profile', compact('user'));
    }

    // app/Http/Controllers/ProfileController.php

    public function update(Request $request)
    {
        $user = User::find(Auth::id());

        $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|unique:users,email,' . $user->id,
            'contact' => 'nullable|string|max:20',       // ✅ contact
            'image'   => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // ✅ image
        ]);

        $user->name    = $request->name;
        $user->email   = $request->email;
        $user->contact = $request->contact; // ✅

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($user->image && file_exists(public_path('uploads/avatars/' . $user->image))) {
                unlink(public_path('uploads/avatars/' . $user->image));
            }
            $file      = $request->file('image');
            $imageName = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/avatars'), $imageName);
            $user->image = $imageName; // ✅
        }

        $user->save();

        return redirect()->route('profile.index')->with('success', 'Profile updated successfully.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate(
            [
                'current_password'      => 'required',
                'password'              => 'required|min:8|confirmed',
                'password_confirmation' => 'required',
            ],
            [
                'current_password.required'      => 'Current password is required.',
                'password.required'              => 'Password is required.',
                'password.min'                   => 'Password must be at least 8 characters.',
                'password.confirmed'             => 'Password confirmation does not match.',
            ]
        );

        $user = User::find(Auth::id()); // ✅ fix here

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.'])->withInput();
        }

        $user->password = Hash::make($request->password);
        $user->save(); // ✅ no warning now

        return redirect()->route('profile.index')->with('success', 'Password changed successfully.');
    }
}
