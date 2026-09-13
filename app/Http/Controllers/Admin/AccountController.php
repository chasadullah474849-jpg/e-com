<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class AccountController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Profile
    |--------------------------------------------------------------------------
    */

    public function profile()
    {
        $user = auth()->user();

        return view('admin.account.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:30',
            'bio' => 'nullable|string|max:500',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('avatar')) {

            if (
                !empty($user->avatar) &&
                Storage::disk('public')->exists($user->avatar)
            ) {
                Storage::disk('public')->delete($user->avatar);
            }

            $data['avatar'] = $request
                ->file('avatar')
                ->store('avatars', 'public');
        }

        $user->update($data);

        return redirect('/admin')
            ->with('success', 'Profile updated successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | Settings
    |--------------------------------------------------------------------------
    */

    public function settings()
    {
        $user = auth()->user();

        return view('admin.account.settings', compact('user'));
    }

    public function updatePassword(Request $request)
    {
        $data = $request->validate([
            'current_password' => [
                'required',
                'current_password',
            ],

            'password' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->mixedCase()
                    ->numbers(),
            ],
        ]);

        $request->user()->update([
            'password' => Hash::make($data['password']),
        ]);

        return redirect('/admin')
            ->with('success', 'Password changed successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | Billing
    |--------------------------------------------------------------------------
    */

    public function billing()
    {
        $user = auth()->user();

        $orders = collect();

        if (
            class_exists(Order::class) &&
            Schema::hasTable('orders')
        ) {
            $query = Order::query()->latest();

            if (Schema::hasColumn('orders', 'user_id')) {

                $query->where('user_id', $user->id);

            } elseif (Schema::hasColumn('orders', 'email')) {

                $query->where('email', $user->email);

            } else {

                $query->whereRaw('1 = 0');
            }

            $orders = $query->get();
        }

        $billing = null;

        if (method_exists($user, 'billingProfile')) {
            $billing = $user->billingProfile;
        } elseif (method_exists($user, 'billingDetail')) {
            $billing = $user->billingDetail;
        }

        return view(
            'admin.account.billing',
            compact('user', 'billing', 'orders')
        );
    }

    public function updateBilling(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'company_name' => 'nullable|string|max:255',
            'billing_email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:30',
            'tax_number' => 'nullable|string|max:80',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'state' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:30',
            'country' => 'required|string|max:100',
        ]);

        if (method_exists($user, 'billingProfile')) {

            $user->billingProfile()->updateOrCreate(
                [
                    'user_id' => $user->id,
                ],
                $data
            );

        } elseif (method_exists($user, 'billingDetail')) {

            $user->billingDetail()->updateOrCreate(
                [
                    'user_id' => $user->id,
                ],
                $data
            );
        }

        return redirect('/admin')
            ->with('success', 'Billing details updated successfully.');
    }
}
