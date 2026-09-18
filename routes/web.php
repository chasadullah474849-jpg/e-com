<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CollectionProController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\StripePaymentController;

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\BillboardController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CollectionController;
use App\Http\Controllers\Admin\FeatureController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductImageController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\SubCategoryController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\VarietyCrudController;
use App\Http\Controllers\Admin\AccountController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminProfileController;
use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\Admin\FinanceReportController;



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


/*
|--------------------------------------------------------------------------
| PUBLIC PRODUCT ROUTES
|--------------------------------------------------------------------------
*/

// Display all products
Route::get(
    '/productss',
    [HomeController::class, 'products']
)->name('productss');
// Shop product page using UUID
Route::get(
    '/product/{uuid}',
    [ShopController::class, 'product']
)
    ->whereUuid('uuid')
    ->name('shop.product');

// Product details page using UUID


Route::get(
    '/product-details/{identifier}',
    [HomeController::class, 'productDetails']
)
    ->where('identifier', '[A-Za-z0-9\-]+')
    ->name('product.details');
/*
|--------------------------------------------------------------------------
| SEARCH
|--------------------------------------------------------------------------
*/

Route::get('/search', [HomeController::class, 'search'])
    ->name('search');


/*
|--------------------------------------------------------------------------
| PUBLIC COLLECTION ROUTES
|--------------------------------------------------------------------------
*/

Route::get('/collections', [HomeController::class, 'collections'])
    ->name('collections');

Route::get(
    '/collection/{uuid}',
    [HomeController::class, 'collectionDetails']
)
    ->whereUuid('uuid')
    ->name('collection.details');

Route::get(
    '/collection-pro/{uuid}',
    [HomeController::class, 'collectionProDetails']
)
    ->whereUuid('uuid')
    ->name('collection-pro.details');


/*
|--------------------------------------------------------------------------
| PUBLIC CATEGORY ROUTES
|--------------------------------------------------------------------------
*/

Route::get(
    '/shop/category/{uuid}',
    [ShopController::class, 'category']
)
    ->whereUuid('uuid')
    ->name('shop.category');

Route::get('/subcategory/{slug}', function ($slug) {
    return view(
        'admin.shop.subcategory',
        compact('slug')
    );
})->name('subcategory.show');


/*
|--------------------------------------------------------------------------
| PUBLIC BLOG ROUTES
|--------------------------------------------------------------------------
*/

Route::get('/blogs', [HomeController::class, 'blogs'])
    ->name('home.blogs');

Route::get(
    '/blog/{identifier}',
    [HomeController::class, 'blogDetails']
)->name('home.blog.details');


/*
|--------------------------------------------------------------------------
| CONTACT ROUTES
|--------------------------------------------------------------------------
*/

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

    Route::post(
        '/add/{identifier}',
        [CartController::class, 'addToCart']
    )
        ->where('identifier', '[A-Za-z0-9\-]+')
        ->name('cart.add');

    Route::post(
        '/add',
        [CartController::class, 'add']
    )->name('cart.add.ajax');

    Route::post(
        '/update-quantity',
        [CartController::class, 'updateQuantity']
    )->name('cart.update-quantity');

    Route::delete(
        '/remove/{identifier}',
        [CartController::class, 'removeFromCart']
    )
        ->where('identifier', '[A-Za-z0-9\-]+')
        ->name('cart.remove');
});

/*
|--------------------------------------------------------------------------
| CHECKOUT AND ORDER ROUTES
|--------------------------------------------------------------------------
*/

Route::get(
    '/checkout',
    [CheckoutController::class, 'index']
)->name('checkout');

Route::post(
    '/checkout',
    [CheckoutController::class, 'process']
)->name('checkout.submit');

Route::post(
    '/checkout/process',
    [CheckoutController::class, 'process']
)->name('checkout.process');

Route::post(
    '/place-order',
    [CheckoutController::class, 'process']
)->name('checkout.place');

Route::get(
    '/order/success',
    [CheckoutController::class, 'orderSuccess']
)->name('order.success');

Route::get(
    '/checkout/login',
    [CheckoutController::class, 'login']
)->name('checkout.login');


/*
|--------------------------------------------------------------------------
| CART STRIPE PAYMENT ROUTES
|--------------------------------------------------------------------------
|
| These routes process all products currently stored in the cart.
| Unique route names prevent conflict with single-product Stripe routes.
|
*/

Route::post(
    '/checkout/stripe/create-payment-intent',
    [
        CheckoutController::class,
        'createPaymentIntent'
    ]
)->name('checkout.stripe.create-intent');

Route::get(
    '/checkout/stripe/payment-success',
    [
        CheckoutController::class,
        'stripeSuccess'
    ]
)->name('checkout.stripe.success');

Route::post(
    '/checkout/stripe/webhook',
    [
        CheckoutController::class,
        'webhook'
    ]
)->name('checkout.stripe.webhook');


/*
|--------------------------------------------------------------------------
| SINGLE PRODUCT STRIPE PAYMENT ROUTES
|--------------------------------------------------------------------------
|
| These routes are only for purchasing one product directly.
|
*/

Route::get(
    '/payment/checkout/{product:uuid}',
    [
        StripePaymentController::class,
        'checkout'
    ]
)->name('stripe.checkout');

Route::post(
    '/payment/create-intent/{product:uuid}',
    [
        StripePaymentController::class,
        'createPaymentIntent'
    ]
)->name('stripe.create-intent');

Route::get(
    '/payment/success',
    [
        StripePaymentController::class,
        'success'
    ]
)->name('stripe.success');

Route::post(
    '/stripe/product-webhook',
    [
        StripePaymentController::class,
        'webhook'
    ]
)->name('stripe.webhook');

/*
|--------------------------------------------------------------------------
| AUTHENTICATION ROUTES
|--------------------------------------------------------------------------
*/

Auth::routes();


/*
|--------------------------------------------------------------------------
| CUSTOM LOGIN ROUTES
|--------------------------------------------------------------------------
*/

Route::prefix('login')->group(function () {

    Route::get(
        '/admin',
        [LoginController::class, 'showAdminLoginForm']
    )->name('login.admin.form');

    Route::post(
        '/admin',
        [LoginController::class, 'adminLogin']
    )->name('login.admin');

    Route::get(
        '/writer',
        [LoginController::class, 'showWriterLoginForm']
    )->name('login.writer.form');

    Route::post(
        '/writer',
        [LoginController::class, 'writerLogin']
    )->name('login.writer');

    Route::get(
        '/user',
        [UserController::class, 'login']
    )->name('login.user');
});


/*
|--------------------------------------------------------------------------
| CUSTOM REGISTRATION ROUTES
|--------------------------------------------------------------------------
*/

Route::prefix('register')->group(function () {

    Route::get(
        '/admin',
        [RegisterController::class, 'showAdminRegisterForm']
    )->name('register.admin.form');

    Route::post(
        '/admin',
        [RegisterController::class, 'createAdmin']
    )->name('register.admin');

    Route::get(
        '/writer',
        [RegisterController::class, 'showWriterRegisterForm']
    )->name('register.writer.form');

    Route::post(
        '/writer',
        [RegisterController::class, 'createWriter']
    )->name('register.writer');
});


/*
|--------------------------------------------------------------------------
| WRITER AND USER PROFILE
|--------------------------------------------------------------------------
*/

Route::view('/writer', 'writer')
    ->name('writer');

Route::middleware(['auth'])->group(function () {

    Route::get(
        '/profile',
        [ProfileController::class, 'show']
    )->name('admin.profile.show');

    Route::get(
        '/profile/edit',
        [ProfileController::class, 'edit']
    )->name('admin.profile.edit');
});


/*
|--------------------------------------------------------------------------
| SUBCATEGORY CRUD
|--------------------------------------------------------------------------
*/

Route::get(
    '/subcategories',
    [SubCategoryController::class, 'index']
)->name('subcategories.index');

Route::get(
    '/subcategories/create',
    [SubCategoryController::class, 'create']
)->name('subcategories.create');

Route::post(
    '/subcategories',
    [SubCategoryController::class, 'store']
)->name('subcategories.store');

Route::get(
    '/subcategories/{id}/edit',
    [SubCategoryController::class, 'edit']
)->name('subcategories.edit');

Route::put(
    '/subcategories/{id}',
    [SubCategoryController::class, 'update']
)->name('subcategories.update');

Route::delete(
    '/subcategories/{id}',
    [SubCategoryController::class, 'destroy']
)->name('subcategories.destroy');


/*
|--------------------------------------------------------------------------
| ADMIN PANEL
|--------------------------------------------------------------------------
*/

Route::prefix('admin')
    ->name('admin.')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Dashboard
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/',
            [DashboardController::class, 'index']
        )->name('dashboard');

        Route::get('/admin', [AdminDashboardController::class, 'index'])
    ->name('admin.dashboard');


        /*
        |--------------------------------------------------------------------------
        | Users
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/users',
            [UserController::class, 'index']
        )->name('users.index');

        Route::get(
            '/users/create',
            [UserController::class, 'create']
        )->name('users.create');

        Route::post(
            '/users',
            [UserController::class, 'store']
        )->name('users.store');

        Route::get(
            '/users/{user}/edit',
            [UserController::class, 'edit']
        )->name('users.edit');

        Route::get(
            '/users/{user}/fetch',
            [UserController::class, 'fetchUser']
        )->name('users.fetch');

        Route::post(
            '/users/{user}',
            [UserController::class, 'update']
        )->name('users.update');

        Route::delete(
            '/users/{user}',
            [UserController::class, 'destroy']
        )->name('users.destroy');


        /*
        |--------------------------------------------------------------------------
        | Varieties
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/varieties',
            [VarietyCrudController::class, 'index']
        )->name('varieties.index');

        Route::get(
            '/varieties/create',
            [VarietyCrudController::class, 'create']
        )->name('varieties.create');

        Route::post(
            '/varieties/store',
            [VarietyCrudController::class, 'store']
        )->name('varieties.store');

        Route::get(
            '/varieties/{id}/edit',
            [VarietyCrudController::class, 'edit']
        )->name('varieties.edit');

        Route::put(
            '/varieties/{id}',
            [VarietyCrudController::class, 'update']
        )->name('varieties.update');

        Route::delete(
            '/varieties/{id}',
            [VarietyCrudController::class, 'destroy']
        )->name('varieties.destroy');

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

        Route::get(
            '/categories',
            [CategoryController::class, 'index']
        )->name('categories.index');

        Route::get(
            '/categories/create',
            [CategoryController::class, 'create']
        )->name('categories.create');

        Route::post(
            '/categories/store',
            [CategoryController::class, 'store']
        )->name('categories.store');

        Route::get(
            '/categories/{uuid}/edit',
            [CategoryController::class, 'edit']
        )
            ->whereUuid('uuid')
            ->name('categories.edit');

        Route::put(
            '/categories/update/{uuid}',
            [CategoryController::class, 'update']
        )
            ->whereUuid('uuid')
            ->name('categories.update');

        Route::delete(
            '/categories/destroy/{uuid}',
            [CategoryController::class, 'destroy']
        )
            ->whereUuid('uuid')
            ->name('categories.destroy');


        /*
        |--------------------------------------------------------------------------
        | Products
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/products',
            [ProductController::class, 'index']
        )->name('products.index');

        Route::get(
            '/products/create',
            [ProductController::class, 'create']
        )->name('products.create');

        Route::post(
            '/products/store',
            [ProductController::class, 'store']
        )->name('products.store');

        Route::get(
            '/products/{uuid}/edit',
            [ProductController::class, 'edit']
        )
            ->whereUuid('uuid')
            ->name('products.edit');

        Route::put(
            '/products/{uuid}',
            [ProductController::class, 'update']
        )
            ->whereUuid('uuid')
            ->name('products.update');

        Route::delete(
            '/products/{uuid}',
            [ProductController::class, 'destroy']
        )
            ->whereUuid('uuid')
            ->name('products.destroy');

        Route::get(
            '/varieties/{id}',
            [ProductController::class, 'showVariety']
        )->name('varieties.show');


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
        | AJAX Subcategory
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

        Route::resource(
            'billboards',
            BillboardController::class
        );


        /*
        |--------------------------------------------------------------------------
        | Features
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/features',
            [FeatureController::class, 'index']
        )->name('features.index');

        Route::get(
            '/features/create',
            [FeatureController::class, 'create']
        )->name('features.create');

        Route::post(
            '/features/store',
            [FeatureController::class, 'store']
        )->name('features.store');

        Route::get(
            '/features/edit/{uuid}',
            [FeatureController::class, 'edit']
        )
            ->whereUuid('uuid')
            ->name('features.edit');

        Route::put(
            '/features/update/{uuid}',
            [FeatureController::class, 'update']
        )
            ->whereUuid('uuid')
            ->name('features.update');

        Route::get(
            '/features/status/{uuid}',
            [FeatureController::class, 'status']
        )
            ->whereUuid('uuid')
            ->name('features.status');

        Route::delete(
            '/features/{uuid}',
            [FeatureController::class, 'destroy']
        )
            ->whereUuid('uuid')
            ->name('features.destroy');


        /*
        |--------------------------------------------------------------------------
        | Testimonials
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/testimonials',
            [TestimonialController::class, 'index']
        )->name('testimonials.index');

        Route::get(
            '/testimonials/create',
            [TestimonialController::class, 'create']
        )->name('testimonials.create');

        Route::post(
            '/testimonials',
            [TestimonialController::class, 'store']
        )->name('testimonials.store');

        Route::get(
            '/testimonials/{uuid}/edit',
            [TestimonialController::class, 'edit']
        )
            ->whereUuid('uuid')
            ->name('testimonials.edit');

        Route::put(
            '/testimonials/{uuid}',
            [TestimonialController::class, 'update']
        )
            ->whereUuid('uuid')
            ->name('testimonials.update');

        Route::delete(
            '/testimonials/{uuid}',
            [TestimonialController::class, 'destroy']
        )
            ->whereUuid('uuid')
            ->name('testimonials.destroy');


        /*
        |--------------------------------------------------------------------------
        | Blogs
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/blogs',
            [BlogController::class, 'index']
        )->name('blogs.index');

        Route::get(
            '/blogs/create',
            [BlogController::class, 'create']
        )->name('blogs.create');

        Route::post(
            '/blogs',
            [BlogController::class, 'store']
        )->name('blogs.store');

        Route::get(
            '/blogs/{uuid}/edit',
            [BlogController::class, 'edit']
        )
            ->whereUuid('uuid')
            ->name('blogs.edit');

        Route::put(
            '/blogs/{uuid}',
            [BlogController::class, 'update']
        )
            ->whereUuid('uuid')
            ->name('blogs.update');

        Route::delete(
            '/blogs/{uuid}',
            [BlogController::class, 'destroy']
        )
            ->whereUuid('uuid')
            ->name('blogs.destroy');


        /*
        |--------------------------------------------------------------------------
        | Collections
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/collections',
            [CollectionController::class, 'index']
        )->name('collections.index');

        Route::get(
            '/collections/create',
            [CollectionController::class, 'create']
        )->name('collections.create');

        Route::post(
            '/collections',
            [CollectionController::class, 'store']
        )->name('collections.store');

        Route::get(
            '/collections/{uuid}/edit',
            [CollectionController::class, 'edit']
        )
            ->whereUuid('uuid')
            ->name('collections.edit');

        Route::put(
            '/collections/{uuid}',
            [CollectionController::class, 'update']
        )
            ->whereUuid('uuid')
            ->name('collections.update');

        Route::delete(
            '/collections/{uuid}',
            [CollectionController::class, 'destroy']
        )
            ->whereUuid('uuid')
            ->name('collections.destroy');

        Route::get(
            '/collections/{uuid}',
            [CollectionController::class, 'show']
        )
            ->whereUuid('uuid')
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

        Route::resource(
            'orders',
            OrderController::class
        );
   }
);


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
                $message
                    ->to('chasadullah474849@gmail.com')
                    ->subject('Test Mail - Setup Verification');
            }
        );

        return 'Email sent successfully! Check your inbox/spam folder.';
    } catch (\Exception $exception) {
        return 'Email failed: ' . $exception->getMessage();
    }
})->name('test.email');






/*
|--------------------------------------------------------------------------
| Admin authentication
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::get(
        '/login/admin',
        [AdminAuthController::class, 'showLoginForm']
    )->name('admin.login');

    Route::post(
        '/login/admin',
        [AdminAuthController::class, 'login']
    )->name('admin.login.submit');
});

/*
|--------------------------------------------------------------------------
| Authenticated admin pages
|--------------------------------------------------------------------------
*/

Route::prefix('admin')
    ->name('admin.')
    ->middleware('auth')
    ->group(function () {
        Route::get(
            '/',
            [AdminController::class, 'dashboard']
        )->name('dashboard');

        Route::get(
            '/profile',
            [AdminProfileController::class, 'show']
        )->name('profile');

        Route::put(
            '/profile',
            [AdminProfileController::class, 'update']
        )->name('profile.update');

        Route::get(
            '/settings',
            [AdminProfileController::class, 'settings']
        )->name('settings');

        Route::put(
            '/settings',
            [AdminProfileController::class, 'updateSettings']
        )->name('settings.update');

        Route::put(
            '/settings/password',
            [AdminProfileController::class, 'updatePassword']
        )->name('settings.password');

        Route::get(
            '/billing',
            [AdminProfileController::class, 'billing']
        )->name('billing');

        Route::put(
            '/billing',
            [AdminProfileController::class, 'updateBilling']
        )->name('billing.update');

        Route::post(
            '/logout',
            [AdminAuthController::class, 'logout']
        )->name('logout');
    });



Route::get(
    '/admin/analytics',
    [AnalyticsController::class, 'index']
)->name('admin.analytics.index');







Route::prefix('admin')
    ->name('admin.')
    ->middleware('auth')
    ->group(function () {
        Route::get(
            '/finance/income',
            [FinanceReportController::class, 'income']
        )->name('finance.income');

        Route::get(
            '/finance/expenses',
            [FinanceReportController::class, 'expenses']
        )->name('finance.expenses');

        Route::get(
            '/finance/profit',
            [FinanceReportController::class, 'profit']
        )->name('finance.profit');

        Route::post(
            '/finance/expenses',
            [FinanceReportController::class, 'storeExpense']
        )->name('finance.expenses.store');

        Route::delete(
            '/finance/expenses/{expense}',
            [FinanceReportController::class, 'destroyExpense']
        )->name('finance.expenses.destroy');
    });
