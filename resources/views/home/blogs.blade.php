<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Blog Posts</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <style>
        body {
            background-color: #f8f9fa;
        }

        .blog-card {
            background: #ffffff;
            border: 0;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
        }

        .blog-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12);
        }

        .blog-image {
            width: 100%;
            height: 250px;
            object-fit: cover;
        }

        .blog-title {
            font-size: 21px;
            font-weight: 700;
            line-height: 1.4;
        }

        .blog-title a {
            color: #212529;
            text-decoration: none;
        }

        .blog-title a:hover {
            color: #0d6efd;
        }

        .blog-description {
            color: #6c757d;
            line-height: 1.7;
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">

            <a class="navbar-brand" href="{{ route('home') }}">
                KAIRA
            </a>

            <button
                class="navbar-toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#mainNavbar"
            >
                <span class="navbar-toggler-icon"></span>
            </button>

            <div
                class="collapse navbar-collapse"
                id="mainNavbar"
            >
                <ul class="navbar-nav ms-auto">

                    <li class="nav-item">
                        <a
                            href="{{ route('home') }}"
                            class="nav-link"
                        >
                            Home
                        </a>
                    </li>

                    <li class="nav-item">
                        <a
                            href="{{ route('productss') }}"
                            class="nav-link"
                        >
                            Products
                        </a>
                    </li>

                    <li class="nav-item">
                        <a
                            href="{{ route('collections') }}"
                            class="nav-link"
                        >
                            Collections
                        </a>
                    </li>

                    <li class="nav-item">
                        <a
                            href="{{ route('home.blogs') }}"
                            class="nav-link active"
                        >
                            Blogs
                        </a>
                    </li>

                    <li class="nav-item">
                        <a
                            href="{{ route('home.contact') }}"
                            class="nav-link"
                        >
                            Contact
                        </a>
                    </li>

                </ul>
            </div>
        </div>
    </nav>

    <main class="py-5">
        <div class="container">

            <div
                class="d-flex flex-wrap justify-content-between align-items-center mb-4"
            >
                <div>
                    <h1 class="fw-bold mb-1">
                        Latest Blog Posts
                    </h1>

                    <p class="text-muted mb-0">
                        Read our latest articles and updates.
                    </p>
                </div>

                <form
                    action="{{ route('home.blogs') }}"
                    method="GET"
                    class="d-flex mt-3 mt-md-0"
                >
                    <input
                        type="search"
                        name="search"
                        value="{{ request('search') }}"
                        class="form-control me-2"
                        placeholder="Search blogs..."
                    >

                    <button
                        type="submit"
                        class="btn btn-dark"
                    >
                        Search
                    </button>
                </form>
            </div>

            <div class="row">

                @forelse($blogs as $blog)

                    @php
                        $blogImage = $blog->image ?? null;

                        if (!empty($blogImage)) {
                            if (
                                \Illuminate\Support\Str::startsWith(
                                    $blogImage,
                                    ['http://', 'https://']
                                )
                            ) {
                                $blogImageUrl = $blogImage;
                            } elseif (
                                \Illuminate\Support\Str::startsWith(
                                    $blogImage,
                                    ['uploads/', 'storage/']
                                )
                            ) {
                                $blogImageUrl = asset($blogImage);
                            } else {
                                $blogImageUrl = asset(
                                    'uploads/blogs/' . $blogImage
                                );
                            }
                        } else {
                            $blogImageUrl = asset(
                                'users/images/no-image.png'
                            );
                        }

                        $blogIdentifier =
                            !empty($blog->uuid)
                                ? $blog->uuid
                                : $blog->id;
                    @endphp

                    <div class="col-lg-4 col-md-6 mb-4">
                        <article class="blog-card h-100">

                            <a
                                href="{{ route(
                                    'home.blog.details',
                                    ['identifier' => $blogIdentifier]
                                ) }}"
                            >
                                <img
                                    src="{{ $blogImageUrl }}"
                                    alt="{{ $blog->title ?? $blog->name ?? 'Blog image' }}"
                                    class="blog-image"
                                    onerror="this.onerror=null; this.src='{{ asset('users/images/no-image.png') }}';"
                                >
                            </a>

                            <div class="card-body p-4">

                                <p class="text-muted small text-uppercase mb-2">
                                    Blog /

                                    {{ optional($blog->created_at)->format('M d, Y') }}
                                </p>

                                <h2 class="blog-title mb-3">
                                    <a
                                        href="{{ route(
                                            'home.blog.details',
                                            ['identifier' => $blogIdentifier]
                                        ) }}"
                                    >
                                        {{ $blog->title ?? $blog->name }}
                                    </a>
                                </h2>

                                <p class="blog-description">
                                    {{ \Illuminate\Support\Str::limit(
                                        strip_tags($blog->description ?? ''),
                                        130
                                    ) }}
                                </p>

                                <a
                                    href="{{ route(
                                        'home.blog.details',
                                        ['identifier' => $blogIdentifier]
                                    ) }}"
                                    class="btn btn-outline-dark mt-2"
                                >
                                    Read More
                                </a>

                            </div>
                        </article>
                    </div>

                @empty

                    <div class="col-12">
                        <div
                            class="alert alert-light text-center border py-5"
                        >
                            <h4>No blog posts found</h4>

                            <p class="text-muted mb-0">
                                There are currently no active blog posts.
                            </p>
                        </div>
                    </div>

                @endforelse

            </div>

            @if($blogs->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $blogs->links() }}
                </div>
            @endif

        </div>
    </main>

    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    ></script>

</body>
</html>
