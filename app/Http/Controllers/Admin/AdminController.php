<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('admin.index');
    }

    public function profile()
    {
        $user = auth()->user();

        return view('admin.account.profile', compact('user'));
    }

    public function settings()
    {
        $user = auth()->user();

        return view('admin.account.settings', compact('user'));
    }

    public function billing()
    {
        $user = auth()->user();

        return view('admin.account.billing', compact('user'));
    }
}
