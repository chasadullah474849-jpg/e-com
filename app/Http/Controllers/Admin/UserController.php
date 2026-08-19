<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Apply the admin guard middleware so only logged-in admins can access this CRUD
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    // 1. READ (List all users)
    public function index()
    {
        $users = User::latest()->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    // 2. CREATE (Show form)
    public function create()
    {
        return view('admin.users.create');
    }

    // 3. CREATE (Save data)
    public function store(Request $request)
    {
        // 1. Run Validation
        // If this fails, Laravel automatically catches it and sends a 422 JSON error back to jQuery
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // 2. Create the User Record in Database
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // 3. Return JSON Response back to your Modal Script
        return response()->json([
            'success' => true,
            'message' => 'User account created successfully!',
            'data'    => $user
        ], 200);
    }

    // 4. UPDATE (Show form)
   public function fetchUser(User $user)
    {
        return response()->json([
            'success' => true,
            'user'    => $user
        ], 200);
    }

    // 2. Process changes sent from the edit modal via AJAX
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed', // password is optional on edit
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        // Only update password if something was entered
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'User credentials updated successfully!'
        ], 200);
    }

    // 6. DELETE
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }
}
