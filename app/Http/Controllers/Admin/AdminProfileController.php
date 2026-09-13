<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class AdminProfileController extends Controller
{
    public function show(Request $request)
    {
        return view('admin.account.profile', [
            'user' => $request->user(),
        ]);
    }

    public function update(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')
                    ->ignore($user->id),
            ],

            'phone' => [
                'nullable',
                'string',
                'max:30',
            ],

            'address' => [
                'nullable',
                'string',
                'max:500',
            ],

            'city' => [
                'nullable',
                'string',
                'max:100',
            ],

            'country' => [
                'nullable',
                'string',
                'max:100',
            ],

            'bio' => [
                'nullable',
                'string',
                'max:1000',
            ],

            'avatar' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
        ]);

        if ($request->hasFile('avatar')) {
            if (
                $user->avatar &&
                Storage::disk('public')->exists($user->avatar)
            ) {
                Storage::disk('public')->delete($user->avatar);
            }

            $validated['avatar'] = $request
                ->file('avatar')
                ->store('admin/avatars', 'public');
        }

        $user->update($validated);

        return redirect()
            ->route('admin.profile')
            ->with('success', 'Profile updated successfully.');
    }

    public function settings(Request $request)
    {
        return view('admin.account.settings', [
            'user' => $request->user(),
        ]);
    }

    public function updateSettings(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')
                    ->ignore($user->id),
            ],

            'phone' => [
                'nullable',
                'string',
                'max:30',
            ],
        ]);

        $user->update($validated);

        return redirect()
            ->route('admin.settings')
            ->with('success', 'Account settings saved successfully.');
    }

    public function updatePassword(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'current_password' => [
                'required',
                'string',
            ],

            'password' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->letters()
                    ->numbers(),
            ],
        ]);

        if (
            !Hash::check(
                $validated['current_password'],
                $user->password
            )
        ) {
            return back()->withErrors([
                'current_password' =>
                    'Your current password is incorrect.',
            ]);
        }

        $user->forceFill([
            'password' => Hash::make(
                $validated['password']
            ),
        ])->save();

        return redirect()
            ->route('admin.settings')
            ->with('success', 'Password changed successfully.');
    }

    public function billing(Request $request)
    {
        $user = $request->user();

        $emails = array_values(
            array_unique(
                array_filter([
                    $user->email,
                    $user->billing_email,
                ])
            )
        );

        $orders = Order::query()->with('items');

        if (
            Schema::hasColumn('orders', 'customer_email') ||
            Schema::hasColumn('orders', 'email')
        ) {
            $orders->where(
                function ($query) use ($emails) {
                    if (
                        Schema::hasColumn(
                            'orders',
                            'customer_email'
                        )
                    ) {
                        $query->whereIn(
                            'customer_email',
                            $emails
                        );
                    }

                    if (Schema::hasColumn('orders', 'email')) {
                        $method = Schema::hasColumn(
                            'orders',
                            'customer_email'
                        )
                            ? 'orWhereIn'
                            : 'whereIn';

                        $query->{$method}('email', $emails);
                    }
                }
            );
        } else {
            $orders->whereRaw('1 = 0');
        }

        $orders = $orders
            ->latest('id')
            ->paginate(10);

        return view(
            'admin.account.billing',
            compact('user', 'orders')
        );
    }

    public function updateBilling(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'company_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'billing_email' => [
                'required',
                'email',
                'max:255',
            ],

            'address' => [
                'nullable',
                'string',
                'max:500',
            ],

            'city' => [
                'nullable',
                'string',
                'max:100',
            ],

            'country' => [
                'nullable',
                'string',
                'max:100',
            ],

            'vat_number' => [
                'nullable',
                'string',
                'max:100',
            ],
        ]);

        $user->update($validated);

        return redirect()
            ->route('admin.billing')
            ->with(
                'success',
                'Billing information saved successfully.'
            );
    }
}
