<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $blog->title }} | Kaira Admin</title>

    <!-- Bootstrap 5 CSS & Google Fonts -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Plus+Jakarta+Sans:wght@400;500;600&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8f9fa;
            color: #2b2f32;
        }
        .blog-card {
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            border: 1px solid #e9ecef;
            overflow: hidden;
        }
        .blog-header-img {
            width: 100%;
            max-height: 480px;
            object-fit: cover;
            border-radius: 8px;
        }
        .blog-title {
            font-family: 'Playfair Display', serif;
            font-size: 2.25rem;
            font-weight: 700;
            color: #1a1a1a;
            line-height: 1.3;
        }
        .date-badge {
            font-size: 0.825rem;
            font-weight: 600;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: #6c757d;
        }
        .blog-content {
            font-size: 1.1rem;
            line-height: 1.8;
            color: #374151;
        }
    </style>
</head>
<body>

<div class="container my-5 py-2">
    <!-- Back Button pointing to admin route -->
    <a href="{{ route('admin.blogs.index') }}" class="btn btn-outline-dark rounded-pill px-4 mb-4 fw-semibold">
        &larr; Back to All Blogs
    </a>

    <div class="row">
        <div class="col-lg-9 mx-auto">
            <div class="blog-card p-4 p-md-5">
                <span class="date-badge d-block mb-2">
                    {{ \Carbon\Carbon::parse($blog->created_at)->format('M d, Y') }}
                </span>

                <h1 class="blog-title mb-4">{{ $blog->title }}</h1>

                <div class="mb-4">
                    <img src="{{ Str::startsWith($blog->image, 'http') ? $blog->image : asset(Storage::url($blog->image)) }}"
                         onerror="this.onerror=null;this.src='{{ asset('uploads/blogs/' . $blog->image) }}';"
                         class="blog-header-img img-fluid"
                         alt="{{ $blog->title }}">
                </div>

                <div class="blog-content">
                    {!! $blog->content ?? $blog->description ?? 'No content available for this post.' !!}
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
