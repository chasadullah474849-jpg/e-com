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

    <div class="layout-wrapper layout-content-navbar">

        <div class="layout-container">

            {{-- Sidebar --}}
            @include('admin.sidebar')


            {{-- Layout Page --}}
            <div class="layout-page">

                {{-- Navbar --}}
                @include('admin.nav')


                {{-- Content --}}
                <div class="content-wrapper">

                    <div class="container-xxl flex-grow-1 container-p-y">

                        {{-- Success Message --}}
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">

                                {{ session('success') }}

                                <button
                                    type="button"
                                    class="btn-close"
                                    data-bs-dismiss="alert"
                                    aria-label="Close">
                                </button>

                            </div>
                        @endif


                        {{-- Error Messages --}}
                        @if($errors->any())

                            <div class="alert alert-danger alert-dismissible fade show" role="alert">

                                <strong>Please fix the following errors:</strong>

                                <ul class="mb-0 mt-2">

                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach

                                </ul>

                                <button
                                    type="button"
                                    class="btn-close"
                                    data-bs-dismiss="alert"
                                    aria-label="Close">
                                </button>

                            </div>

                        @endif


                        {{-- Page Header --}}
                        <div class="d-flex justify-content-between align-items-center mb-4">

                            <h4 class="fw-bold py-3 mb-0">
                                Collections
                            </h4>


                            {{-- Create Collection --}}
                            <a
                                href="{{ route('admin.collections.create') }}"
                                class="btn btn-primary"
                            >

                                <span class="tf-icons bx bx-plus me-1"></span>

                                Create New Collection

                            </a>

                        </div>


                        {{-- Collections Table Card --}}
                        <div class="card">

                            <div class="card-header">

                                <h5 class="mb-0">
                                    All Collections
                                </h5>

                            </div>


                            <div class="table-responsive text-nowrap">

                                <table class="table table-hover">

                                    <thead>

                                        <tr>

                                            <th>Name</th>

                                            <th>Description</th>

                                            <th>Image</th>

                                            <th>Category</th>

                                            <th>Status</th>

                                            <th>Actions</th>

                                        </tr>

                                    </thead>


                                    <tbody class="table-border-bottom-0">

                                        @forelse($collections as $collection)

                                            <tr>

                                                {{-- Name --}}
                                                <td>

                                                    <strong>
                                                        {{ $collection->name }}
                                                    </strong>

                                                </td>


                                                {{-- Description --}}
                                                <td>

                                                    {{ Str::limit(
                                                        $collection->description ?? '',
                                                        50,
                                                        '...'
                                                    ) }}

                                                </td>


                                                {{-- Image --}}
                                                <td>

                                                    @if($collection->image)

                                                        <img
                                                            src="{{ asset('storage/' . $collection->image) }}"
                                                            alt="{{ $collection->name }}"
                                                            class="rounded"
                                                            style="
                                                                width: 50px;
                                                                height: 50px;
                                                                object-fit: cover;
                                                            "
                                                        >

                                                    @else

                                                        <div
                                                            class="d-flex align-items-center justify-content-center bg-light text-muted rounded"
                                                            style="
                                                                width: 50px;
                                                                height: 50px;
                                                                font-size: 11px;
                                                            "
                                                        >
                                                            No Img
                                                        </div>

                                                    @endif

                                                </td>


                                                {{-- Category --}}
                                                <td>

                                                    @if($collection->category)

                                                        <span class="badge bg-label-info">

                                                            {{ $collection->category->name }}

                                                        </span>

                                                    @else

                                                        <span class="badge bg-label-secondary">

                                                            No Category

                                                        </span>

                                                    @endif

                                                </td>


                                                {{-- Status --}}
                                                <td>

                                                    @if(strtolower((string) $collection->status) === 'active')

                                                        <span class="badge bg-label-success me-1">
                                                            Active
                                                        </span>

                                                    @elseif((string) $collection->status === '1')

                                                        <span class="badge bg-label-success me-1">
                                                            Active
                                                        </span>

                                                    @else

                                                        <span class="badge bg-label-secondary me-1">
                                                            Inactive
                                                        </span>

                                                    @endif

                                                </td>


                                                {{-- Actions --}}
                                                <td>

                                                    <div class="dropdown">

                                                        <button
                                                            type="button"
                                                            class="btn p-0 dropdown-toggle hide-arrow"
                                                            data-bs-toggle="dropdown"
                                                            aria-expanded="false"
                                                        >

                                                            <i class="bx bx-dots-vertical-rounded"></i>

                                                        </button>


                                                        <div class="dropdown-menu">


                                                            {{-- Edit --}}
                                                            <a
                                                                class="dropdown-item d-flex align-items-center"
                                                                href="{{ route('admin.collections.edit', $collection->id) }}"
                                                            >

                                                                <i class="bx bx-edit-alt me-2"></i>

                                                                Edit

                                                            </a>


                                                            {{-- Delete --}}
                                                            <form
                                                                action="{{ route('admin.collections.destroy', $collection->id) }}"
                                                                method="POST"
                                                                onsubmit="return confirm('Are you sure you want to delete this collection?');"
                                                            >

                                                                @csrf

                                                                @method('DELETE')


                                                                <button
                                                                    type="submit"
                                                                    class="dropdown-item d-flex align-items-center text-danger border-0 bg-transparent w-100 text-start"
                                                                >

                                                                    <i class="bx bx-trash me-2"></i>

                                                                    Delete

                                                                </button>

                                                            </form>


                                                        </div>

                                                    </div>

                                                </td>

                                            </tr>

                                        @empty

                                            <tr>

                                                <td
                                                    colspan="6"
                                                    class="text-center py-5 text-muted"
                                                >

                                                    <i class="bx bx-folder-open fs-1 d-block mb-2"></i>

                                                    No collections found.

                                                </td>

                                            </tr>

                                        @endforelse

                                    </tbody>

                                </table>

                            </div>

                        </div>

                    </div>


                    {{-- Footer --}}
                    @include('admin.footer')

                </div>

            </div>

        </div>

    </div>


    {{-- JavaScript --}}
    @include('admin.js')

</body>

</html>
