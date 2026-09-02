<div class="row g-3">
    <!-- Name Field -->
    <div class="col-md-6">
        <label for="name" class="form-label fw-semibold">Author / Subtitle Name</label>
        <input type="text"
               class="form-control @error('name') is-invalid @enderror"
               id="name"
               name="name"
               value="{{ old('name', $blog->name ?? '') }}"
               placeholder="Enter author or short name">
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <!-- Title Field -->
    <div class="col-md-6">
        <label for="title" class="form-label fw-semibold">Blog Title <span class="text-danger">*</span></label>
        <input type="text"
               class="form-control @error('title') is-invalid @enderror"
               id="title"
               name="title"
               value="{{ old('title', $blog->title ?? '') }}"
               required
               placeholder="Enter blog title">
        @error('title')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <!-- Status Field -->
    <div class="col-md-6">
        <label for="status" class="form-label fw-semibold">Status</label>
        <select name="status" id="status" class="form-select @error('status') is-invalid @enderror">
            <option value="1" {{ old('status', $blog->status ?? '1') == '1' || old('status', $blog->status ?? '') == 'published' ? 'selected' : '' }}>Active / Published</option>
            <option value="0" {{ old('status', $blog->status ?? '1') == '0' || old('status', $blog->status ?? '') == 'draft' ? 'selected' : '' }}>Inactive / Draft</option>
        </select>
        @error('status')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <!-- Image Upload -->
    <div class="col-md-6">
        <label for="image" class="form-label fw-semibold">Featured Image</label>
        <input type="file"
               class="form-control @error('image') is-invalid @enderror"
               id="image"
               name="image"
               accept="image/*">
        @error('image')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror

        @if(isset($blog) && $blog->image)
            <div class="mt-2 d-flex align-items-center gap-3">
                <img src="{{ asset('uploads/blogs/' . $blog->image) }}"
                     alt="{{ $blog->title }}"
                     style="width: 80px; height: 80px; object-fit: cover; border-radius: 8px;"
                     onerror="this.onerror=null;this.src='{{ asset('users/images/no-image.png') }}';">
                <small class="text-muted">Current Image</small>
            </div>
        @endif
    </div>

    <!-- Description Field -->
    <div class="col-12">
        <label for="description" class="form-label fw-semibold">Description / Content</label>
        <textarea class="form-control @error('description') is-invalid @enderror"
                  id="description"
                  name="description"
                  rows="6"
                  placeholder="Write blog content...">{{ old('description', $blog->description ?? '') }}</textarea>
        @error('description')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <!-- Action Buttons -->
    <div class="col-12 text-end mt-4">
        <a href="{{ route('admin.blogs.index') }}" class="btn btn-outline-secondary me-2">Cancel</a>
        <button type="submit" class="btn btn-primary px-4">
            <i class="bx bx-save me-1"></i> {{ $buttonText ?? 'Save Blog' }}
        </button>
    </div>
</div>
