<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CollectionProController;
use App\Http\Controllers\ContactController;

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\VarietyCrudController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\SubCategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\BillboardController;
use App\Http\Controllers\Admin\FeatureController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\CollectionController;
use App\Http\Controllers\Admin\ProductImageController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\OrderController;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;


/*
|--------------------------------------------------------------------------
| PUBLIC WEBSITE ROUTES
|--------------------------------------------------------------------------
*/

// Home
Route::get('/', [HomeController::class, 'index'])
    ->name('home');


// Products
Route::get('/productss', [HomeController::class, 'products'])
    ->name('productss');

Route::get('/product/{id}', [ShopController::class, 'product'])
    ->name('shop.product');

/*
|--------------------------------------------------------------------------
| PRODUCT DETAILS
|--------------------------------------------------------------------------
| IMPORTANT:
| Product URL contains UUID, so use {uuid}
*/

Route::get('/product-details/{uuid}', [HomeController::class, 'productDetails'])
    ->name('product.details');


// Search
Route::get('/search', [HomeController::class, 'search'])
    ->name('search');


// Collections
Route::get('/collections', [HomeController::class, 'collections'])
    ->name('collections');

Route::get('/collection/{uuid}', [HomeController::class, 'collectionDetails'])
    ->name('collection.details');

Route::get('/collection-pro/{uuid}', [HomeController::class, 'collectionProDetails'])
    ->name('collection-pro.details');


// Categories
Route::get('/shop/category/{uuid}', [ShopController::class, 'category'])
    ->name('shop.category');


// Subcategory public page
Route::get('/subcategory/{slug}', function ($slug) {
    return view('admin.shop.subcategory', compact('slug'));
})->name('subcategory.show');


// Blogs
/*
|--------------------------------------------------------------------------
| PUBLIC BLOG ROUTES
|--------------------------------------------------------------------------
*/

// All blog posts
Route::get('/blogs', [HomeController::class, 'blogs'])
    ->name('home.blogs');

Route::get(
    '/blog/{identifier}',
    [HomeController::class, 'blogDetails']
)->name('home.blog.details');


// Contact
Route::get('/contact', [HomeController::class, 'contact'])
    ->name('home.contact');

Route::post('/contact', [ContactController::class, 'send'])
    ->name('home.contact.send');


/*
|--------------------------------------------------------------------------
| CART ROUTES
|--------------------------------------------------------------------------
*/

Route::prefix('cart')->group(function () {

    Route::get('/', [CartController::class, 'cart'])
        ->name('cart');

    Route::post('/add/{uuid}', [CartController::class, 'addToCart'])
        ->name('cart.add');

    Route::post('/update-quantity', [CartController::class, 'updateQuantity'])
        ->name('cart.update-quantity');

    Route::delete('/remove/{uuid}', [CartController::class, 'remove'])
        ->name('cart.remove');

    Route::delete('/clear', [CartController::class, 'clear'])
        ->name('cart.clear');
});


/*
|--------------------------------------------------------------------------
| CHECKOUT / ORDER ROUTES
|--------------------------------------------------------------------------
*/

Route::get('/checkout', [CheckoutController::class, 'index'])
    ->name('checkout');

Route::post('/checkout', [CheckoutController::class, 'process'])
    ->name('checkout.submit');

Route::post('/place-order', [CheckoutController::class, 'process'])
    ->name('checkout.place');

Route::get('/order-success', [CheckoutController::class, 'orderSuccess'])
    ->name('order.success');

Route::get('/checkout/login', [CheckoutController::class, 'login'])
    ->name('checkout.login');


/*
|--------------------------------------------------------------------------
| AUTHENTICATION
|--------------------------------------------------------------------------
*/

Auth::routes();


// Custom Login
Route::prefix('login')->group(function () {

    Route::get('/admin', [LoginController::class, 'showAdminLoginForm'])
        ->name('login.admin.form');

    Route::get('/writer', [LoginController::class, 'showWriterLoginForm'])
        ->name('login.writer.form');

    Route::get('/user', [UserController::class, 'login'])
        ->name('login.user');


    Route::post('/admin', [LoginController::class, 'adminLogin'])
        ->name('login.admin');

    Route::post('/writer', [LoginController::class, 'writerLogin'])
        ->name('login.writer');
});


// Custom Register
Route::prefix('register')->group(function () {

    Route::get('/admin', [RegisterController::class, 'showAdminRegisterForm'])
        ->name('register.admin.form');

    Route::get('/writer', [RegisterController::class, 'showWriterRegisterForm'])
        ->name('register.writer.form');


    Route::post('/admin', [RegisterController::class, 'createAdmin'])
        ->name('register.admin');

    Route::post('/writer', [RegisterController::class, 'createWriter'])
        ->name('register.writer');
});


/*
|--------------------------------------------------------------------------
| WRITER & USER PROFILE
|--------------------------------------------------------------------------
*/

Route::view('/writer', 'writer');


Route::middleware(['auth'])->group(function () {

    Route::get('/profile', [ProfileController::class, 'show'])
        ->name('admin.profile.show');

    Route::get('/profile/edit', [ProfileController::class, 'edit'])
        ->name('admin.profile.edit');
});


/*
|--------------------------------------------------------------------------
| SUBCATEGORY CRUD
|--------------------------------------------------------------------------
*/

Route::get('/subcategories', [SubCategoryController::class, 'index'])
    ->name('subcategories.index');

Route::get('/subcategories/create', [SubCategoryController::class, 'create'])
    ->name('subcategories.create');

Route::post('/subcategories', [SubCategoryController::class, 'store'])
    ->name('subcategories.store');

Route::get('/subcategories/{id}/edit', [SubCategoryController::class, 'edit'])
    ->name('subcategories.edit');

Route::put('/subcategories/{id}', [SubCategoryController::class, 'update'])
    ->name('subcategories.update');

Route::delete('/subcategories/{id}', [SubCategoryController::class, 'destroy'])
    ->name('subcategories.destroy');


/*
|--------------------------------------------------------------------------
| ADMIN PANEL
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->group(function () {


    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */

    Route::get('/', function () {
        return view('admin.index');
    })->name('dashboard');


    /*
    |--------------------------------------------------------------------------
    | Users
    |--------------------------------------------------------------------------
    */

    Route::get('/users', [UserController::class, 'index'])
        ->name('users.index');

    Route::get('/users/create', [UserController::class, 'create'])
        ->name('users.create');

    Route::post('/users', [UserController::class, 'store'])
        ->name('users.store');

    Route::get('/users/{user}/edit', [UserController::class, 'edit'])
        ->name('users.edit');

    Route::get('/users/{user}/fetch', [UserController::class, 'fetchUser'])
        ->name('users.fetch');

    Route::post('/users/{user}', [UserController::class, 'update'])
        ->name('users.update');

    Route::delete('/users/{user}', [UserController::class, 'destroy'])
        ->name('users.destroy');


    /*
    |--------------------------------------------------------------------------
    | Varieties
    |--------------------------------------------------------------------------
    */

    Route::get('/varieties', [VarietyCrudController::class, 'index'])
        ->name('varieties.index');

    Route::get('/varieties/create', [VarietyCrudController::class, 'create'])
        ->name('varieties.create');

    Route::post('/varieties/store', [VarietyCrudController::class, 'store'])
        ->name('varieties.store');

    Route::get('/varieties/{id}/edit', [VarietyCrudController::class, 'edit'])
        ->name('varieties.edit');

    Route::put('/varieties/{id}', [VarietyCrudController::class, 'update'])
        ->name('varieties.update');

    Route::delete('/varieties/{id}', [VarietyCrudController::class, 'destroy'])
        ->name('varieties.destroy');

    Route::get(
        '/get-subcategories/{categoryId}',
        [VarietyCrudController::class, 'getSubcategories']
    )->name('varieties.get-subcategories');

    Route::get(
        '/get-products/{subcategoryId}',
        [VarietyCrudController::class, 'getProducts']
    )->name('varieties.get-products');


    /*
    |--------------------------------------------------------------------------
    | Categories
    |--------------------------------------------------------------------------
    */

    Route::get('/categories', [CategoryController::class, 'index'])
        ->name('categories.index');

    Route::get('/categories/create', [CategoryController::class, 'create'])
        ->name('categories.create');

    Route::post('/categories/store', [CategoryController::class, 'store'])
        ->name('categories.store');

    Route::get('/categories/{uuid}/edit', [CategoryController::class, 'edit'])
        ->name('categories.edit');

    Route::put('/categories/update/{uuid}', [CategoryController::class, 'update'])
        ->name('categories.update');

    Route::delete('/categories/destroy/{uuid}', [CategoryController::class, 'destroy'])
        ->name('categories.destroy');


    /*
    |--------------------------------------------------------------------------
    | Products - Admin
    |--------------------------------------------------------------------------
    */

    Route::get('/products', [ProductController::class, 'index'])
        ->name('products.index');

    Route::get('/products/create', [ProductController::class, 'create'])
        ->name('products.create');

    Route::post('/products/store', [ProductController::class, 'store'])
        ->name('products.store');

    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])
        ->name('products.edit');

    Route::put('/products/{product}', [ProductController::class, 'update'])
        ->name('products.update');

    Route::delete('/products/{id}', [ProductController::class, 'destroy'])
        ->name('products.destroy');

    Route::get('/varieties/{id}', [ProductController::class, 'showVariety'])
        ->name('varieties.show');


    /*
    |--------------------------------------------------------------------------
    | Product Images
    |--------------------------------------------------------------------------
    */

    Route::post(
        '/products/image/{id}/replace',
        [ProductImageController::class, 'replace']
    )->name('products.image.replace');

    Route::delete(
        '/products/image/{id}/delete',
        [ProductImageController::class, 'delete']
    )->name('products.image.delete');


    /*
    |--------------------------------------------------------------------------
    | Ajax Subcategory
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/get-subcategory/{category_id}',
        [ProductController::class, 'getSubcategory']
    )->name('get.subcategory');


    /*
    |--------------------------------------------------------------------------
    | Billboards
    |--------------------------------------------------------------------------
    */

    Route::resource('billboards', BillboardController::class);


    /*
    |--------------------------------------------------------------------------
    | Features
    |--------------------------------------------------------------------------
    */

    Route::get('/features', [FeatureController::class, 'index'])
        ->name('features.index');

    Route::get('/features/create', [FeatureController::class, 'create'])
        ->name('features.create');

    Route::post('/features/store', [FeatureController::class, 'store'])
        ->name('features.store');

    Route::get('/features/edit/{uuid}', [FeatureController::class, 'edit'])
        ->name('features.edit');

    Route::put('/features/update/{uuid}', [FeatureController::class, 'update'])
        ->name('features.update');

    Route::delete('/features/delete/{uuid}', [FeatureController::class, 'destroy'])
        ->name('features.delete');

    Route::get('/features/status/{uuid}', [FeatureController::class, 'status'])
        ->name('features.status');

    Route::delete('/features/{uuid}', [FeatureController::class, 'destroy'])
        ->name('features.destroy');


    /*
    |--------------------------------------------------------------------------
    | Testimonials
    |--------------------------------------------------------------------------
    */

    Route::get('/testimonials', [TestimonialController::class, 'index'])
        ->name('testimonials.index');

    Route::get('/testimonials/create', [TestimonialController::class, 'create'])
        ->name('testimonials.create');

    Route::post('/testimonials', [TestimonialController::class, 'store'])
        ->name('testimonials.store');

    Route::get('/testimonials/{uuid}/edit', [TestimonialController::class, 'edit'])
        ->name('testimonials.edit');

    Route::put('/testimonials/{uuid}', [TestimonialController::class, 'update'])
        ->name('testimonials.update');

    Route::delete('/testimonials/{uuid}', [TestimonialController::class, 'destroy'])
        ->name('testimonials.destroy');


    /*
    |--------------------------------------------------------------------------
    | Blogs - Admin
    |--------------------------------------------------------------------------
    */

    Route::get('/blogs', [BlogController::class, 'index'])
        ->name('blogs.index');

    Route::get('/blogs/create', [BlogController::class, 'create'])
        ->name('blogs.create');

    Route::post('/blogs', [BlogController::class, 'store'])
        ->name('blogs.store');

    Route::get('/blogs/{id}/edit', [BlogController::class, 'edit'])
        ->name('blogs.edit');

    Route::put('/blogs/{id}', [BlogController::class, 'update'])
        ->name('blogs.update');

    Route::delete('/blogs/{id}', [BlogController::class, 'destroy'])
        ->name('blogs.destroy');


    /*
    |--------------------------------------------------------------------------
    | Collections - Admin
    |--------------------------------------------------------------------------
    */

    Route::get('/collections', [CollectionController::class, 'index'])
        ->name('collections.index');

    Route::get('/collections/create', [CollectionController::class, 'create'])
        ->name('collections.create');

    Route::post('/collections', [CollectionController::class, 'store'])
        ->name('collections.store');

    Route::get('/collections/{collection}/edit', [CollectionController::class, 'edit'])
        ->name('collections.edit');

    Route::put('/collections/{collection}', [CollectionController::class, 'update'])
        ->name('collections.update');

    Route::delete('/collections/{collection}', [CollectionController::class, 'destroy'])
        ->name('collections.destroy');

    Route::get('/collections/{id}', [CollectionController::class, 'show'])
        ->name('collections.show');


    /*
    |--------------------------------------------------------------------------
    | Collection Pro
    |--------------------------------------------------------------------------
    */

    Route::resource(
        'collection-pro',
        CollectionProController::class
    );


    /*
    |--------------------------------------------------------------------------
    | Orders
    |--------------------------------------------------------------------------
    */

    Route::resource('orders', OrderController::class);

});


/*
|--------------------------------------------------------------------------
| TEST EMAIL
|--------------------------------------------------------------------------
*/

Route::get('/test-email', function () {

    try {

        Mail::raw(
            'SMTP test email from Laravel localhost!',
            function ($message) {

                $message->to('chasadullah474849@gmail.com')
                    ->subject('Test Mail - Setup Verification');

            }
        );

        return 'Email sent successfully! Check your inbox/spam folder.';

    } catch (\Exception $e) {

        return 'Email failed with error: ' . $e->getMessage();

    }

});
