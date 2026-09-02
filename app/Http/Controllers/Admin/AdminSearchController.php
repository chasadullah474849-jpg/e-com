<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminSearchController extends Controller
{
    public function redirect(Request $request)
    {
        $query = strtolower(trim($request->input('query')));

        // Map search keywords to route names
        $routes = [
            // Users / Profile
            'user'             => 'admin.users.index',
            'users'            => 'admin.users.index',
            'profile'          => 'admin.profile.show',

            // Varieties
            'variety'          => 'admin.varieties.index',
            'varieties'        => 'admin.varieties.index',

            // Categories & Sub Categories
            'category'         => 'admin.categories.index',
            'categories'       => 'admin.categories.index',
            'sub category'     => 'admin.subcategories.index',
            'sub categories'   => 'admin.subcategories.index',
            'subcategory'      => 'admin.subcategories.index',

            // Products
            'product'          => 'admin.products.index',
            'products'         => 'admin.products.index',

            // Billboards
            'billboard'        => 'admin.billboards.index',
            'billboards'       => 'admin.billboards.index',

            // Collections
            'collection'       => 'admin.collections.index',
            'collections'      => 'admin.collections.index',
            'collection pro'   => 'admin.collections.index',

            // Features
            'feature'          => 'admin.features.index',
            'features'         => 'admin.features.index',

            // Testimonials
            'testimonial'      => 'admin.testimonials.index',
            'testimonials'     => 'admin.testimonials.index',

            // Blogs
            'blog'             => 'admin.blogs.index',
            'blogs'            => 'admin.blogs.index',

            // Orders
            'order'            => 'admin.orders.index',
            'orders'           => 'admin.orders.index',
        ];

        // Direct Redirect if exact or partial match exists
        foreach ($routes as $keyword => $routeName) {
            if (str_contains($query, $keyword)) {
                if (\Route::has($routeName)) {
                    return redirect()->route($routeName);
                }
            }
        }

        // Default fallback if no CRUD match is found
        return redirect()->back()->with('error', "No page found matching '{$query}'.");
    }
}
