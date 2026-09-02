<?php


namespace App\Http\Controllers\Admin;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
  public function show()
    {
        $user = Auth::user() ?? (object)[
            'first_name' => 'Muhammad',
            'last_name' => 'Asadullah',
            'email' => 'chasadullah474849@gmail.com',
            'phone' => '',
            'is_verified' => false,
            'profile_photo_path' => null,
        ];

        return view('admin.profile.index', compact('user'));
    }

    public function edit()
    {
        $user = Auth::user();
        return view('admin.profile.edit', compact('user'));
    }

    /**
     * Update the authenticated user's profile.
     */
  public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => 'required|email|max:255',
            'phone'      => 'nullable|string|max:20',
            'avatar'     => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($user) {
            if ($request->hasFile('avatar')) {
                if ($user->profile_photo_path && Storage::disk('public')->exists($user->profile_photo_path)) {
                    Storage::disk('public')->delete($user->profile_photo_path);
                }
                $user->profile_photo_path = $request->file('avatar')->store('profile-photos', 'public');
            }

            $user->name = $request->first_name . ' ' . $request->last_name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->save();
        }

        return redirect()->back()->with('success', 'Profile updated successfully!');
    }
public function billing()
    {
        return view('admin.profile.billing');
    }
}
