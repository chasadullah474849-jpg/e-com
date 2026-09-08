<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        {{ $blog->title ?? $blog->name ?? 'Blog Details' }}
    </title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <style>
        body {
            background-color: #f8f9fa;
            color: #212529;
        }

        .blog-wrapper {
            max-width: 950px;
            margin: auto;
        }

        .blog-card {
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.08);
        }

        .blog-image {
            width: 100%;
            height: 480px;
            object-fit: cover;
        }

        .blog-content {
            padding: 35px;
        }

        .blog-title {
            font-size: 36px;
            font-weight: 700;
            line-height: 1.3;
        }

        .blog-date {
            color: #6c757d;
            font-size: 15px;
        }

        .blog-description {
            font-size: 17px;
            line-height: 1.9;
        }

        @media (max-width: 767px) {
            .blog-image {
                height: 280px;
            }

            .blog-content {
                padding: 22px;
            }

            .blog-title {
                font-size: 28px;
            }
        }
    </style>
</head>

<body>

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
    @endphp

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
                            class="nav-link"
                            href="{{ route('home') }}"
                        >
                            Home
                        </a>
                    </li>

                    <li class="nav-item">
                        <a
                            class="nav-link"
                            href="{{ route('productss') }}"
                        >
                            Products
                        </a>
                    </li>

                    <li class="nav-item">
                        <a
                            class="nav-link"
                            href="{{ route('collections') }}"
                        >
                            Collections
                        </a>
                    </li>

                    <li class="nav-item">
                        <a
                            class="nav-link active"
                            href="{{ route('home.blogs') }}"
                        >
                            Blogs
                        </a>
                    </li>

                    <li class="nav-item">
                        <a
                            class="nav-link"
                            href="{{ route('home.contact') }}"
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

            <div class="blog-wrapper">

                <div class="mb-4">
                    <a
                        href="{{ route('home.blogs') }}"
                        class="btn btn-outline-dark"
                    >
                        ← Back to Blogs
                    </a>
                </div>

                <article class="blog-card">

                    <img
                        src="{{ $blogImageUrl }}"
                        alt="{{ $blog->title ?? $blog->name ?? 'Blog image' }}"
                        class="blog-image"
                        onerror="this.onerror=null; this.src='{{ asset('users/images/no-image.png') }}';"
                    >

                    <div class="blog-content">

                        <p class="blog-date text-uppercase mb-2">
                            Blog /

                            {{ optional($blog->created_at)->format('F d, Y') }}
                        </p>

                        <h1 class="blog-title mb-4">
                            {{ $blog->title ?? $blog->name }}
                        </h1>

                        @if(!empty($blog->name) && $blog->name !== $blog->title)
                            <h5 class="text-muted mb-4">
                                {{ $blog->name }}
                            </h5>
                        @endif

                        <div class="blog-description">
                            {!! $blog->description !!}
                        </div>

                    </div>
                </article>

            </div>
        </div>
    </main>

    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    ></script>

</body>
</html>
