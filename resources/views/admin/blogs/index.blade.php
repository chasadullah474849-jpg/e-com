<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Blogs | Kaira</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #ffffff;
            color: #111111;
        }
        .blog-card {
            border: none;
            background: transparent;
        }
        .image-wrapper {
            position: relative;
            width: 100%;
            height: 480px;
            overflow: hidden;
            background-color: #f8f9fa;
        }
        .image-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .date-badge {
            position: absolute;
            bottom: 20px;
            left: 20px;
            background: #ffffff;
            padding: 8px 18px;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: #000000;
            z-index: 2;
        }
        .blog-title {
            font-family: 'Times New Roman', Times, serif;
            font-size: 26px;
            line-height: 1.2;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: #111111;
            margin-top: 22px;
            margin-bottom: 12px;
            font-weight: 400;
        }
        .read-article {
            color: #111111;
            text-decoration: none;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            border-bottom: 1px solid #111111;
            padding-bottom: 2px;
        }
        .read-article:hover {
            color: #666666;
            border-color: #666666;
        }
    </style>
</head>
<body>

<div class="container my-5 py-3">
    <div class="row g-4">
        @foreach($blogs as $blog)
            <div class="col-md-4">
                <div class="blog-card">
                    <div class="image-wrapper">
                        {{-- Handles storage path, public uploads, or asset fallback --}}
                       <img src="{{ asset('uploads/blogs/' . $blog->image) }}"
     onerror="this.onerror=null;this.src='{{ asset('images/' . $blog->image) }}';"
     alt="{{ $blog->title }}">

                        <div class="date-badge">
                            {{ \Carbon\Carbon::parse($blog->created_at)->format('M d, Y') }}
                        </div>
                    </div>

                    <h2 class="blog-title">{{ $blog->title }}</h2>

                    <a href="{{ url('/blog/' . ($blog->slug ?? $blog->id)) }}" class="read-article">
                        READ ARTICLE &rarr;
                    </a>
                </div>
            </div>
        @endforeach
    </div>
</div>

</body>
</html>
