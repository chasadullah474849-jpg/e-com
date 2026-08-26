<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

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

use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CollectionProController;


/*
|--------------------------------------------------------------------------
| Public Home Page & Storefront
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/product', [HomeController::class, 'products'])->name('products');
Route::get('/product/{uuid}', [HomeController::class, 'productDetails'])->name('product.details');

/* Public Collection Details Route */
Route::get('/collection/{uuid}', [HomeController::class, 'collectionDetails'])->name('collection.details');


/*
|--------------------------------------------------------------------------
| Authentication
|--------------------------------------------------------------------------
*/

Auth::routes();


/*
|--------------------------------------------------------------------------
| Custom Login Routes
|--------------------------------------------------------------------------
*/

Route::prefix('login')->group(function () {

    Route::get('/admin', [LoginController::class, 'showAdminLoginForm'])->name('login.admin.form');
    Route::get('/writer', [LoginController::class, 'showWriterLoginForm'])->name('login.writer.form');
    Route::get('/user', [UserController::class, 'login'])->name('login.user');

    Route::post('/admin', [LoginController::class, 'adminLogin'])->name('login.admin');
    Route::post('/writer', [LoginController::class, 'writerLogin'])->name('login.writer');

});


/*
|--------------------------------------------------------------------------
| Custom Register Routes
|--------------------------------------------------------------------------
*/

Route::prefix('register')->group(function () {

    Route::get('/admin', [RegisterController::class, 'showAdminRegisterForm'])->name('register.admin.form');
    Route::get('/writer', [RegisterController::class, 'showWriterRegisterForm'])->name('register.writer.form');

    Route::post('/admin', [RegisterController::class, 'createAdmin'])->name('register.admin');
    Route::post('/writer', [RegisterController::class, 'createWriter'])->name('register.writer');

});


/*
|--------------------------------------------------------------------------
| Dashboard Routes
|--------------------------------------------------------------------------
*/

Route::view('/admin', 'admin.index')->name('admin');
Route::view('/writer', 'writer');


/*
|--------------------------------------------------------------------------
| ADMIN ROUTES (Prefix: /admin, Name: admin.*)
|--------------------------------------------------------------------------
*/

Route::prefix('admin')
    ->name('admin.')
    ->group(function () {

        /* Users */
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::get('/users/{user}/fetch', [UserController::class, 'fetchUser'])->name('users.fetch');
        Route::post('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

        /* Varieties */
        Route::get('/varieties', [VarietyCrudController::class, 'index'])->name('varieties.index');
        Route::get('/varieties/create', [VarietyCrudController::class, 'create'])->name('varieties.create');
        Route::post('/varieties/store', [VarietyCrudController::class, 'store'])->name('varieties.store');
        Route::get('/varieties/{id}/edit', [VarietyCrudController::class, 'edit'])->name('varieties.edit');
        Route::put('/varieties/{id}', [VarietyCrudController::class, 'update'])->name('varieties.update');
        Route::delete('/varieties/{id}', [VarietyCrudController::class, 'destroy'])->name('varieties.destroy');

        Route::get('/get-subcategories/{categoryId}', [VarietyCrudController::class, 'getSubcategories'])->name('varieties.get-subcategories');
        Route::get('/get-products/{subcategoryId}', [VarietyCrudController::class, 'getProducts'])->name('varieties.get-products');

        /* Categories */
        Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
        Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
        Route::post('/categories/store', [CategoryController::class, 'store'])->name('categories.store');
        Route::get('/categories/{uuid}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
        Route::put('/categories/update/{uuid}', [CategoryController::class, 'update'])->name('categories.update');
        Route::delete('/categories/destroy/{uuid}', [CategoryController::class, 'destroy'])->name('categories.destroy');

        /* Products */
        Route::get('/products', [ProductController::class, 'index'])->name('products.index');
        Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
        Route::post('/products/store', [ProductController::class, 'store'])->name('products.store');
        Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
        Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
        Route::delete('/products/{id}', [ProductController::class, 'destroy'])->name('products.destroy');

        /* Product Images */
        Route::post('/products/image/{id}/replace', [ProductImageController::class, 'replace'])->name('products.image.replace');
        Route::delete('/products/image/{id}/delete', [ProductImageController::class, 'delete'])->name('products.image.delete');

        /* Product Subcategory AJAX */
        Route::get('/get-subcategory/{category_id}', [ProductController::class, 'getSubcategory'])->name('get.subcategory');

        /* Billboards */
        Route::resource('billboards', BillboardController::class);

        /* Features */
        Route::get('/features', [FeatureController::class, 'index'])->name('features.index');
        Route::get('/features/create', [FeatureController::class, 'create'])->name('features.create');
        Route::post('/features/store', [FeatureController::class, 'store'])->name('features.store');
        Route::get('/features/edit/{uuid}', [FeatureController::class, 'edit'])->name('features.edit');
        Route::put('/features/update/{uuid}', [FeatureController::class, 'update'])->name('features.update');
        Route::delete('/features/delete/{uuid}', [FeatureController::class, 'destroy'])->name('features.delete');
        Route::get('/features/status/{uuid}', [FeatureController::class, 'status'])->name('features.status');
        Route::delete('/features/{uuid}', [FeatureController::class, 'destroy'])->name('features.destroy');

        /* Testimonials */
        Route::get('/testimonials', [TestimonialController::class, 'index'])->name('testimonials.index');
        Route::get('/testimonials/create', [TestimonialController::class, 'create'])->name('testimonials.create');
        Route::post('/testimonials', [TestimonialController::class, 'store'])->name('testimonials.store');
        Route::get('/testimonials/{uuid}/edit', [TestimonialController::class, 'edit'])->name('testimonials.edit');
        Route::put('/testimonials/{uuid}', [TestimonialController::class, 'update'])->name('testimonials.update');
        Route::delete('/testimonials/{uuid}', [TestimonialController::class, 'destroy'])->name('testimonials.destroy');

        /* Blogs */
       /*
|--------------------------------------------------------------------------
| BLOGS - ADMIN CRUD
|--------------------------------------------------------------------------
*/



/*
|--------------------------------------------------------------------------
| Admin Blog Routes
|--------------------------------------------------------------------------
*/
        /* Collections (Admin Panel Routes) */
        Route::get('/collections', [CollectionController::class, 'index'])->name('collections.index');
        Route::get('/collections/create', [CollectionController::class, 'create'])->name('collections.create');
        Route::post('/collections', [CollectionController::class, 'store'])->name('collections.store');
        Route::get('/collections/{collection}/edit', [CollectionController::class, 'edit'])->name('collections.edit');
        Route::put('/collections/{collection}', [CollectionController::class, 'update'])->name('collections.update');
        Route::delete('/collections/{collection}', [CollectionController::class, 'destroy'])->name('collections.destroy');
        Route::get('/collections/{id}', [CollectionController::class, 'show'])->name('collections.show');

    });


/*
|--------------------------------------------------------------------------
| SUBCATEGORY ROUTES
|--------------------------------------------------------------------------
*/

Route::get('/subcategories', [SubCategoryController::class, 'index'])->name('subcategories.index');
Route::get('/subcategories/create', [SubCategoryController::class, 'create'])->name('subcategories.create');
Route::post('/subcategories', [SubCategoryController::class, 'store'])->name('subcategories.store');
Route::get('/subcategories/{id}/edit', [SubCategoryController::class, 'edit'])->name('subcategories.edit');
Route::put('/subcategories/{id}', [SubCategoryController::class, 'update'])->name('subcategories.update');
Route::delete('/subcategories/{id}', [SubCategoryController::class, 'destroy'])->name('subcategories.destroy');


/*
|--------------------------------------------------------------------------
| SHOPPING CART
|--------------------------------------------------------------------------
*/
Route::prefix('cart')->group(function () {

    // Add product
    Route::post('/add/{uuid}', [CartController::class, 'addToCart'])
        ->name('cart.add');

    // Full cart page
    Route::get('/', [CartController::class, 'cart'])
        ->name('cart');

    // Update cart quantity
    Route::post('/update-quantity', [CartController::class, 'updateQuantity'])
        ->name('cart.update-quantity');

    // Remove product
    Route::delete('/remove/{uuid}', [CartController::class, 'remove'])
        ->name('cart.remove');

    // Clear cart
    Route::delete('/clear', [CartController::class, 'clear'])
        ->name('cart.clear');
});

/*
|--------------------------------------------------------------------------
| CHECKOUT
|--------------------------------------------------------------------------
*/


/*
|--------------------------------------------------------------------------
| CHECKOUT ROUTES
|--------------------------------------------------------------------------
*/



/*
|--------------------------------------------------------------------------
| Show checkout
|--------------------------------------------------------------------------
*/

Route::get(
    '/checkout',
    [CheckoutController::class, 'index']
)->name('checkout');


/*
|--------------------------------------------------------------------------
| Submit checkout / Place Order
|--------------------------------------------------------------------------
*/

Route::post(
    '/checkout',
    [CheckoutController::class, 'process']
)->name('checkout.submit');


/*
|--------------------------------------------------------------------------
| Optional old URL support
|--------------------------------------------------------------------------
| If an old form is still using /place-order,
| it will use the same correct process() method.
|--------------------------------------------------------------------------
*/

Route::post(
    '/place-order',
    [CheckoutController::class, 'process']
)->name('checkout.place');


/*
|--------------------------------------------------------------------------
| Order success
|--------------------------------------------------------------------------
*/

Route::get(
    '/order-success',
    [CheckoutController::class, 'orderSuccess']
)->name('order.success');


/*
|--------------------------------------------------------------------------
| Checkout Login
|--------------------------------------------------------------------------
*/

Route::get(
    '/checkout/login',
    [CheckoutController::class, 'login']
)->name('checkout.login');


/*
|--------------------------------------------------------------------------
| Cart quantity update
|--------------------------------------------------------------------------
*/

Route::post(
    '/cart/update-quantity',
    [CheckoutController::class, 'updateQuantity']
)->name('cart.update-quantity');


Route::prefix('admin')->group(function () {
    Route::resource('collection-pro', CollectionProController::class);
});

// Front-end detail route using UUID
Route::get('/collection-pro/{uuid}', [HomeController::class, 'collectionProDetails'])->name('collection-pro.details');




Route::get('/blogs', [HomeController::class, 'blogs'])
    ->name('blogs');

Route::get('/blog/{id}', [HomeController::class, 'blogDetails'])
    ->name('blog.details');



Route::get('/blogs', [BlogController::class, 'index'])->name('blogs.index');
Route::get('/blogs/create', [BlogController::class, 'create'])->name('admin.blogs.create');
Route::post('/blogs', [BlogController::class, 'store'])->name('admin.blogs.store');
Route::get('/blogs/{id}/edit', [BlogController::class, 'edit'])->name('admin.blogs.edit');
Route::put('/blogs/{id}', [BlogController::class, 'update'])->name('admin.blogs.update');
Route::delete('/blogs/{id}', [BlogController::class, 'destroy'])->name('admin.blogs.destroy');
Route::get('/blog/{id}', [BlogController::class, 'show'])->name('home.blog.details');
