

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1">{{ $blog->title }}</h1>

        <span
            class="badge {{ $blog->status === 'published' ? 'bg-success' : 'bg-secondary' }}"
        >
            {{ ucfirst($blog->status) }}
        </span>
    </div>

    <div class="d-flex gap-2">
        <a
            href="{{ route('admin.blogs.edit', $blog) }}"
            class="btn btn-warning"
        >
            Edit
        </a>

        <a
            href="{{ route('admin.blogs.index') }}"
            class="btn btn-outline-secondary"
        >
            Back
        </a>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">

        @if($blog->image)
            <img
                src="{{ asset('storage/' . $blog->image) }}"
                alt="{{ $blog->title }}"
                class="img-fluid rounded mb-4"
                style="width: 100%; max-height: 500px; object-fit: cover;"
            >
        @endif

        <div class="mb-4">
            <strong>Slug:</strong>
            {{ $blog->slug }}
        </div>

        <div class="mb-4">
            <strong>Published:</strong>
            {{ $blog->published_at?->format('d M Y, h:i A') ?? 'Not published' }}
        </div>

        @if($blog->excerpt)
            <div class="alert alert-light border">
                {{ $blog->excerpt }}
            </div>
        @endif

        <div class="blog-content">
            {!! nl2br(e($blog->content)) !!}
        </div>

    </div>
</div>

