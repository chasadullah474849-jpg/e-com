<!DOCTYPE html>
<html
    lang="en"
    class="light-style layout-menu-fixed"
    dir="ltr"
    data-theme="theme-default"
    data-assets-path="../assets/"
    data-template="vertical-menu-template-free"
>

<head>

    @include('admin.header')

</head>

<body>

    @include('admin.sidebar')


    <div class="layout-wrapper layout-content-navbar">

        <div class="layout-container">

            <div class="layout-page">

                @include('admin.nav')


                <div class="container-fluid py-4">


                    {{-- ================================================== --}}
                    {{-- Success Message --}}
                    {{-- ================================================== --}}

                    @if(session('success'))

                        <div
                            class="alert alert-success alert-dismissible fade show"
                            role="alert"
                        >

                            <strong>Success!</strong>
                            {{ session('success') }}

                            <button
                                type="button"
                                class="btn-close"
                                data-bs-dismiss="alert"
                                aria-label="Close"
                            ></button>

                        </div>

                    @endif



                    {{-- ================================================== --}}
                    {{-- Error Messages --}}
                    {{-- ================================================== --}}

                    @if($errors->any())

                        <div
                            class="alert alert-danger alert-dismissible fade show"
                            role="alert"
                        >

                            <strong>
                                Please fix the following errors:
                            </strong>

                            <ul class="mb-0 mt-2">

                                @foreach($errors->all() as $error)

                                    <li>
                                        {{ $error }}
                                    </li>

                                @endforeach

                            </ul>

                            <button
                                type="button"
                                class="btn-close"
                                data-bs-dismiss="alert"
                                aria-label="Close"
                            ></button>

                        </div>

                    @endif



                    {{-- ================================================== --}}
                    {{-- Edit Blog Card --}}
                    {{-- ================================================== --}}

                    <div class="card">


                        {{-- Card Header --}}

                        <div class="card-header">

                            <h4 class="mb-0">
                                Edit Blog
                            </h4>

                        </div>



                        {{-- Card Body --}}

                        <div class="card-body">


                            {{-- ================================================== --}}
                            {{-- UPDATE FORM --}}
                            {{-- ================================================== --}}

                            <form
                                action="{{ route('admin.blogs.update', ['id' => $blog->id]) }}"
                                method="POST"
                                enctype="multipart/form-data"
                            >

                                @csrf

                                @method('PUT')



                                {{-- ================================================== --}}
                                {{-- Category --}}
                                {{-- ================================================== --}}

                                <div class="mb-3">

                                    <label
                                        for="category_id"
                                        class="form-label"
                                    >
                                        Category
                                    </label>


                                    <select
                                        name="category_id"
                                        id="category_id"
                                        class="form-select @error('category_id') is-invalid @enderror"
                                        required
                                    >

                                        <option value="">
                                            Select Category
                                        </option>


                                        @foreach($categories as $category)

                                            <option
                                                value="{{ $category->id }}"
                                                {{ old('category_id', $blog->category_id) == $category->id ? 'selected' : '' }}
                                            >

                                                {{ $category->name }}

                                            </option>

                                        @endforeach

                                    </select>


                                    @error('category_id')

                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>

                                    @enderror

                                </div>



                                {{-- ================================================== --}}
                                {{-- Name --}}
                                {{-- ================================================== --}}

                                <div class="mb-3">

                                    <label
                                        for="name"
                                        class="form-label"
                                    >
                                        Name
                                    </label>


                                    <input
                                        type="text"
                                        name="name"
                                        id="name"
                                        class="form-control @error('name') is-invalid @enderror"
                                        value="{{ old('name', $blog->name) }}"
                                        required
                                    >


                                    @error('name')

                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>

                                    @enderror

                                </div>



                                {{-- ================================================== --}}
                                {{-- Title --}}
                                {{-- ================================================== --}}

                                <div class="mb-3">

                                    <label
                                        for="title"
                                        class="form-label"
                                    >
                                        Title
                                    </label>


                                    <input
                                        type="text"
                                        name="title"
                                        id="title"
                                        class="form-control @error('title') is-invalid @enderror"
                                        value="{{ old('title', $blog->title) }}"
                                        required
                                    >


                                    @error('title')

                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>

                                    @enderror

                                </div>



                                {{-- ================================================== --}}
                                {{-- Description --}}
                                {{-- ================================================== --}}

                                <div class="mb-3">

                                    <label
                                        for="description"
                                        class="form-label"
                                    >
                                        Description
                                    </label>


                                    <textarea
                                        name="description"
                                        id="description"
                                        rows="6"
                                        class="form-control @error('description') is-invalid @enderror"
                                        required
                                    >{{ old('description', $blog->description) }}</textarea>


                                    @error('description')

                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>

                                    @enderror

                                </div>



                                {{-- ================================================== --}}
                                {{-- Details --}}
                                {{-- ================================================== --}}

                                <div class="mb-3">

                                    <label
                                        for="details"
                                        class="form-label"
                                    >
                                        Details
                                    </label>


                                    <textarea
                                        name="details"
                                        id="details"
                                        rows="6"
                                        class="form-control @error('details') is-invalid @enderror"
                                        required
                                    >{{ old('details', $blog->details) }}</textarea>


                                    @error('details')

                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>

                                    @enderror

                                </div>



                                {{-- ================================================== --}}
                                {{-- Current Image --}}
                                {{-- ================================================== --}}

                                <div class="mb-4">

                                    <label class="form-label">
                                        Current Image
                                    </label>


                                    @if($blog->image)

                                        <div class="mt-2">

                                            <img
                                                src="{{ asset('uploads/blogs/' . $blog->image) }}"
                                                alt="{{ $blog->title }}"
                                                width="200"
                                                height="150"
                                                class="img-thumbnail"
                                                style="object-fit: cover;"
                                            >

                                        </div>

                                    @else

                                        <div class="alert alert-secondary">

                                            No image uploaded.

                                        </div>

                                    @endif

                                </div>



                                {{-- ================================================== --}}
                                {{-- Change Image --}}
                                {{-- ================================================== --}}

                                <div class="mb-4">

                                    <label
                                        for="image"
                                        class="form-label"
                                    >
                                        Change Image
                                    </label>


                                    <input
                                        type="file"
                                        name="image"
                                        id="image"
                                        class="form-control @error('image') is-invalid @enderror"
                                        accept="image/jpeg,image/png,image/jpg,image/webp"
                                    >


                                    <small class="text-muted">

                                        Leave empty if you do not want
                                        to change the current image.

                                    </small>


                                    @error('image')

                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>

                                    @enderror

                                </div>



                                {{-- ================================================== --}}
                                {{-- Status --}}
                                {{-- ================================================== --}}

                                <div class="mb-4">

                                    <label class="form-label">
                                        Status
                                    </label>


                                    <div>


                                        {{-- Active --}}

                                        <div class="form-check form-check-inline">

                                            <input
                                                type="radio"
                                                name="status"
                                                id="status_active"
                                                value="1"
                                                class="form-check-input"
                                                {{ old('status', $blog->status) == 1 ? 'checked' : '' }}
                                            >


                                            <label
                                                for="status_active"
                                                class="form-check-label"
                                            >
                                                Active
                                            </label>

                                        </div>



                                        {{-- Inactive --}}

                                        <div class="form-check form-check-inline">

                                            <input
                                                type="radio"
                                                name="status"
                                                id="status_inactive"
                                                value="0"
                                                class="form-check-input"
                                                {{ old('status', $blog->status) == 0 ? 'checked' : '' }}
                                            >


                                            <label
                                                for="status_inactive"
                                                class="form-check-label"
                                            >
                                                Inactive
                                            </label>

                                        </div>

                                    </div>


                                    @error('status')

                                        <div class="text-danger mt-1">
                                            {{ $message }}
                                        </div>

                                    @enderror

                                </div>



                                {{-- ================================================== --}}
                                {{-- Buttons --}}
                                {{-- ================================================== --}}

                                <div class="mt-4">


                                    <button
                                        type="submit"
                                        class="btn btn-primary"
                                    >

                                        <i class="bx bx-save me-1"></i>

                                        Update Blog

                                    </button>


                                    <a
                                        href="{{ route('admin.blogs.index') }}"
                                        class="btn btn-secondary"
                                    >

                                        Cancel

                                    </a>

                                </div>


                            </form>

                        </div>

                    </div>

                </div>


                @include('admin.footer')

            </div>

        </div>

    </div>


    @include('admin.js')

</body>

</html>
