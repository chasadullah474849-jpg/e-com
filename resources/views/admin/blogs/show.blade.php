<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $blog->title }} | Kaira</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #ffffff;
            color: #111111;
        }
        .blog-header-img {
            width: 100%;
            max-height: 550px;
            object-fit: cover;
        }
        .blog-title {
            font-family: 'Times New Roman', Times, serif;
            font-size: 36px;
            letter-spacing: 1px;
            text-transform: uppercase;
        }
        .date-badge {
            font-size: 13px;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: #666666;
        }
    </style>
</head>
<body>

<div class="container my-5 py-3">
    <a href="{{ route('blogs.index') }}" class="btn btn-outline-dark mb-4">&larr; BACK TO ALL BLOGS</a>

    <div class="row">
        <div class="col-lg-10 mx-auto">
            <span class="date-badge d-block mb-2">
                {{ \Carbon\Carbon::parse($blog->created_at)->format('M d, Y') }}
            </span>
            <h1 class="blog-title mb-4">{{ $blog->title }}</h1>

            <div class="mb-4">
                <img src="{{ Str::startsWith($blog->image, 'http') ? $blog->image : asset(Storage::url($blog->image)) }}"
                     onerror="this.onerror=null;this.src='{{ asset('uploads/blogs/' . $blog->image) }}';"
                     class="blog-header-img"
                     alt="{{ $blog->title }}">
            </div>

            <div class="blog-content fs-5 leading-relaxed">
                {!! $blog->content ?? $blog->description ?? 'No content available for this post.' !!}
            </div>
        </div>
    </div>
</div>

</body>
</html>
