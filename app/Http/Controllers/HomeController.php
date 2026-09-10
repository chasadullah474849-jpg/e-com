<?php

namespace App\Http\Controllers;

use App\Models\Billboard;
use App\Models\Blog;
use App\Models\Category;
use App\Models\Collection;
use App\Models\CollectionPro;
use App\Models\Feature;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Home Page
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $billboard = Billboard::where('status', 1)
            ->first();

        $features = Feature::where('status', 1)
            ->latest()
            ->take(4)
            ->get();

        $collections = Collection::query()
            ->whereRaw('LOWER(status) = ?', ['active'])
            ->latest()
            ->get();

        $collectionss = Collection::query()
            ->whereRaw('LOWER(status) = ?', ['active'])
            ->latest()
            ->first();

        $collectionPro = CollectionPro::where('status', 1)
            ->latest()
            ->first();

        $categories = Category::query()
            ->whereRaw('LOWER(status) = ?', ['active'])
            ->latest()
            ->get();

        $products = Product::with([
                'images',
                'category',
                'subcategory',
            ])
            ->latest()
            ->take(10)
            ->get();

        $blogs = Blog::query()
            ->where(function ($query) {
                $query->whereRaw('LOWER(status) = ?', ['active'])
                    ->orWhere('status', 1);
            })
            ->latest()
            ->take(3)
            ->get();

        return view('home.index', compact(
            'billboard',
            'features',
            'collections',
            'collectionss',
            'collectionPro',
            'categories',
            'products',
            'blogs'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | All Collections
    |--------------------------------------------------------------------------
    */

    public function collections(Request $request)
    {
        $query = Collection::query();

        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(
                'name',
                'like',
                '%' . $search . '%'
            );
        }

        $collections = $query
            ->latest()
            ->paginate(9)
            ->withQueryString();

        return view(
            'home.collections',
            compact('collections')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Collection Details
    |--------------------------------------------------------------------------
    */

   public function collectionDetails(string $uuid)
{
    $collection = Collection::with([
            'category.subcategories.products.images',
        ])
        ->where('uuid', $uuid)
        ->firstOrFail();

    $products = collect();

    if (
        $collection->category &&
        $collection->category->subcategories
    ) {
        $products = $collection->category
            ->subcategories
            ->flatMap(function ($subcategory) {
                return $subcategory->products;
            })
            ->unique('id')
            ->values();
    }

    if (
        $products->isEmpty() &&
        !empty($collection->category_id)
    ) {
        $products = Product::with([
                'images',
                'category',
                'subcategory',
            ])
            ->where(
                'category_id',
                $collection->category_id
            )
            ->latest()
            ->get();
    }

    return view('home.collection-details', compact(
        'collection',
        'products'
    ));
}

    /*
    |--------------------------------------------------------------------------
    | Collection Pro Details
    |--------------------------------------------------------------------------
    */

    public function collectionProDetails(string $uuid)
    {
        $collectionPro = CollectionPro::where('uuid', $uuid)
            ->firstOrFail();

        return view(
            'home.collection-pro-details',
            compact('collectionPro')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Products Page
    |--------------------------------------------------------------------------
    */

    public function products(Request $request)
    {
        $query = Product::with([
            'images',
            'category',
            'subcategory',
        ]);

        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($productQuery) use ($search) {
                $productQuery
                    ->where(
                        'name',
                        'like',
                        '%' . $search . '%'
                    )
                    ->orWhere(
                        'description',
                        'like',
                        '%' . $search . '%'
                    );
            });
        }

        if ($request->filled('category')) {
            $categoryName = trim($request->category);

            $query->whereHas(
                'category',
                function ($categoryQuery) use ($categoryName) {
                    $categoryQuery->where(
                        'name',
                        $categoryName
                    );
                }
            );
        }

        $products = $query
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view(
            'home.products',
            compact('products')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Product Search
    |--------------------------------------------------------------------------
    */

   /*
|--------------------------------------------------------------------------
| Global Search
|--------------------------------------------------------------------------
*/

public function search(Request $request)
{
    $request->validate([
        'search' => [
            'nullable',
            'string',
            'max:100',
        ],
    ]);

    $search = trim((string) $request->input('search'));

    $products = collect();
    $collections = collect();
    $categories = collect();
    $blogs = collect();

    if ($search !== '') {
        $products = Product::with([
                'images',
                'category',
                'subcategory',
            ])
            ->where(function ($query) use ($search) {
                $query->where(
                        'name',
                        'like',
                        '%' . $search . '%'
                    )
                    ->orWhere(
                        'description',
                        'like',
                        '%' . $search . '%'
                    );
            })
            ->latest()
            ->take(12)
            ->get();

        $collections = Collection::with('category')
            ->where(function ($query) use ($search) {
                $query->where(
                        'name',
                        'like',
                        '%' . $search . '%'
                    )
                    ->orWhere(
                        'description',
                        'like',
                        '%' . $search . '%'
                    );
            })
            ->latest()
            ->take(12)
            ->get();

        $categories = Category::query()
            ->where(function ($query) use ($search) {
                $query->where(
                        'name',
                        'like',
                        '%' . $search . '%'
                    )
                    ->orWhere(
                        'description',
                        'like',
                        '%' . $search . '%'
                    );
            })
            ->latest()
            ->take(12)
            ->get();

        $blogs = Blog::query()
            ->where(function ($query) use ($search) {
                $query->where(
                        'name',
                        'like',
                        '%' . $search . '%'
                    )
                    ->orWhere(
                        'title',
                        'like',
                        '%' . $search . '%'
                    )
                    ->orWhere(
                        'description',
                        'like',
                        '%' . $search . '%'
                    );
            })
            ->latest()
            ->take(12)
            ->get();
    }

    $totalResults =
        $products->count() +
        $collections->count() +
        $categories->count() +
        $blogs->count();

    return view('home.search-results', compact(
        'search',
        'products',
        'collections',
        'categories',
        'blogs',
        'totalResults'
    ));
}

    /*
    |--------------------------------------------------------------------------
    | Product Details
    |--------------------------------------------------------------------------
    */

   public function productDetails(string $identifier)
{
    /*
    |--------------------------------------------------------------------------
    | Find product using UUID or old numeric ID
    |--------------------------------------------------------------------------
    */

    $product = Product::query()
        ->with([
            'category',
            'subcategory',
            'images',
        ])
        ->where(function ($query) use ($identifier) {
            $query->where('uuid', $identifier);

            if (ctype_digit($identifier)) {
                $query->orWhere('id', (int) $identifier);
            }
        })
        ->firstOrFail();

    /*
    |--------------------------------------------------------------------------
    | Generate UUID if an old product does not have one
    |--------------------------------------------------------------------------
    */

    if (empty($product->uuid)) {
        $product->uuid = (string) \Illuminate\Support\Str::uuid();
        $product->save();
    }

    /*
    |--------------------------------------------------------------------------
    | Redirect numeric URL to correct UUID URL
    |--------------------------------------------------------------------------
    |
    | /product-details/20
    | becomes:
    | /product-details/product-uuid
    */

    if (ctype_digit($identifier)) {
        return redirect()->route(
            'product.details',
            ['identifier' => $product->uuid]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Related products
    |--------------------------------------------------------------------------
    */

    $relatedProducts = Product::query()
        ->with([
            'category',
            'images',
        ])
        ->where('id', '!=', $product->id)
        ->when(
            $product->category_id,
            function ($query) use ($product) {
                $query->where(
                    'category_id',
                    $product->category_id
                );
            }
        )
        ->whereNotNull('uuid')
        ->latest()
        ->take(4)
        ->get();

    return view('home.product_details', [
        'product' => $product,
        'relatedProducts' => $relatedProducts,
    ]);
}
    /*
    |--------------------------------------------------------------------------
    | All Blogs
    |--------------------------------------------------------------------------
    */

  public function blogs(Request $request)
{
    $query = Blog::query()
        ->where(function ($query) {
            $query->whereRaw('LOWER(status) = ?', ['active'])
                ->orWhere('status', 1);
        });

    if ($request->filled('search')) {
        $search = trim($request->search);

        $query->where(function ($query) use ($search) {
            $query->where('title', 'like', "%{$search}%")
                ->orWhere('name', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%");
        });
    }

    $blogs = $query
        ->latest()
        ->paginate(9)
        ->withQueryString();

    return view('home.blogs', compact('blogs'));
}
    /*
    |--------------------------------------------------------------------------
    | Blog Details — Supports UUID and Numeric ID
    |--------------------------------------------------------------------------
    */

    /*
|--------------------------------------------------------------------------
| Blog Details — Supports UUID and Numeric ID
|--------------------------------------------------------------------------
*/

public function blogDetails(string $identifier)
{
    $blog = Blog::query()
        ->where(function ($query) use ($identifier) {
            $query->where('uuid', $identifier);

            if (ctype_digit($identifier)) {
                $query->orWhere('id', (int) $identifier);
            }
        })
        ->firstOrFail();

    return view('home.blog_details', compact('blog'));
}
    /*
    |--------------------------------------------------------------------------
    | Contact Page
    |--------------------------------------------------------------------------
    */

    public function contact()
    {
        return view('home.contact');
    }

    /*
    |--------------------------------------------------------------------------
    | Contact Form
    |--------------------------------------------------------------------------
    */

    public function sendContactForm(Request $request)
    {
        $validatedData = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'email' => [
                'required',
                'email',
                'max:255',
            ],
            'phone' => [
                'nullable',
                'string',
                'max:20',
            ],
            'message' => [
                'required',
                'string',
            ],
        ]);

        return back()->with(
            'success',
            'Your message has been sent successfully!'
        );
    }
}
