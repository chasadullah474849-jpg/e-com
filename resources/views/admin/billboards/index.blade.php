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
                            <div class="alert alert-danger">

                                <strong>Please fix the following errors:</strong>

                                <ul class="mb-0 mt-2">

                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach

                                </ul>

                            </div>
                        @endif


                        {{-- Billboard Card --}}
                        <div class="card">

                            <div class="card-header">

                                <h4 class="mb-0">
                                    Billboard
                                </h4>

                            </div>


                            <div class="card-body">

                                {{-- Billboard Form --}}

                                <form
                                    action="{{ $billboard
                                        ? route('admin.billboards.update', $billboard->id)
                                        : route('admin.billboards.store') }}"
                                    method="POST"
                                >

                                    @csrf


                                    {{-- PUT method when updating --}}
                                    @if($billboard)

                                        @method('PUT')

                                    @endif


                                    {{-- Title --}}
                                    <div class="mb-3">

                                        <label
                                            for="title"
                                            class="form-label"
                                        >
                                            Title
                                        </label>

                                        <input
                                            type="text"
                                            id="title"
                                            name="title"
                                            class="form-control"
                                            placeholder="Enter billboard title"
                                            value="{{ old('title', $billboard->title ?? '') }}"
                                            required
                                        >

                                    </div>


                                    {{-- Description --}}
                                    <div class="mb-3">

                                        <label
                                            for="description"
                                            class="form-label"
                                        >
                                            Description
                                        </label>

                                        <textarea
                                            id="description"
                                            name="description"
                                            class="form-control"
                                            rows="5"
                                            placeholder="Enter billboard description"
                                            required
                                        >{{ old('description', $billboard->description ?? '') }}</textarea>

                                    </div>


                                    {{-- Status --}}
                                    <div class="mb-3">

                                        <label
                                            for="status"
                                            class="form-label"
                                        >
                                            Status
                                        </label>

                                        <select
                                            name="status"
                                            id="status"
                                            class="form-select"
                                            required
                                        >

                                            <option
                                                value="1"
                                                {{ old('status', $billboard->status ?? 1) == 1 ? 'selected' : '' }}
                                            >
                                                Active
                                            </option>

                                            <option
                                                value="0"
                                                {{ old('status', $billboard->status ?? 1) == 0 ? 'selected' : '' }}
                                            >
                                                Inactive
                                            </option>

                                        </select>

                                    </div>


                                    {{-- Submit Button --}}
                                    <button
                                        type="submit"
                                        class="btn btn-primary"
                                    >

                                        {{ $billboard ? 'Update Billboard' : 'Save Billboard' }}

                                    </button>


                                    {{-- Cancel --}}
                                    @if($billboard)

                                        <a
                                            href="{{ route('admin.billboards.index') }}"
                                            class="btn btn-secondary"
                                        >
                                            Cancel
                                        </a>

                                    @endif

                                </form>

                            </div>

                        </div>

                    </div>


                    {{-- Footer --}}
                    <footer class="content-footer footer bg-footer-theme">

                        <div class="container-xxl d-flex flex-wrap justify-content-between py-2">

                            <div class="mb-2 mb-md-0">
                                © {{ date('Y') }} E-Commerce Website
                            </div>

                        </div>

                    </footer>


                    <div class="content-backdrop fade"></div>

                </div>

            </div>

        </div>

    </div>


    {{-- JavaScript --}}
    @include('admin.js')

</body>

</html> 
