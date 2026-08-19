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


            <!-- Layout container -->

            <div class="layout-page">

                @include('admin.nav')


                <!-- Content -->

                <div class="container-xxl flex-grow-1 container-p-y">


                    <!-- Page Header -->

                    <div
                        class="d-flex justify-content-between align-items-center mb-4"
                    >

                        <div>

                            <h4 class="fw-bold py-3 mb-0">

                                <span class="text-muted fw-light">
                                    Sub Categories /
                                </span>

                                Add New

                            </h4>

                        </div>


                        <div>

                            <a
                                href="{{ route('subcategories.index') }}"
                                class="btn btn-secondary"
                            >

                                <i class="bx bx-arrow-back me-1"></i>

                                Back

                            </a>

                        </div>

                    </div>


                    <!-- Validation Errors -->

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
                            ></button>

                        </div>

                    @endif


                    <!-- Create Form -->

                    <div class="card">

                        <div class="card-header">

                            <h5 class="mb-0">
                                Add New Subcategory
                            </h5>

                        </div>


                        <div class="card-body">


                            <form
                                action="{{ route('subcategories.store') }}"
                                method="POST"
                            >

                                @csrf


                                <!-- Sub Category Name -->

                                <div class="mb-3">

                                    <label
                                        class="form-label"
                                        for="name"
                                    >

                                        Sub Category Name

                                    </label>


                                    <input
                                        type="text"
                                        id="name"
                                        name="name"
                                        class="form-control"
                                        value="{{ old('name') }}"
                                        placeholder="Enter subcategory name"
                                        required
                                    >

                                </div>


                                <!-- Description -->

                                <div class="mb-3">

                                    <label
                                        class="form-label"
                                        for="description"
                                    >

                                        Description

                                    </label>


                                    <textarea
                                        id="description"
                                        name="description"
                                        rows="4"
                                        class="form-control"
                                        placeholder="Enter subcategory description"
                                    >{{ old('description') }}</textarea>

                                </div>


                                <!-- Parent Category -->

                                <div class="mb-3">

                                    <label
                                        class="form-label"
                                        for="category_id"
                                    >

                                        Parent Category

                                    </label>


                                    <select
                                        id="category_id"
                                        name="category_id"
                                        class="form-select"
                                        required
                                    >

                                        <option value="">
                                            Select Category
                                        </option>


                                        @foreach(
                                            $categories
                                            as $category
                                        )

                                            <option
                                                value="{{ $category->id }}"
                                                {{ old('category_id') == $category->id
                                                    ? 'selected'
                                                    : ''
                                                }}
                                            >

                                                {{ $category->name }}

                                            </option>

                                        @endforeach


                                    </select>

                                </div>


                                <!-- Status -->

                                <div class="mb-4">

                                    <label
                                        class="form-label"
                                        for="status"
                                    >

                                        Status

                                    </label>


                                    <select
                                        id="status"
                                        name="status"
                                        class="form-select"
                                        required
                                    >

                                        <option
                                            value="1"
                                            {{ old(
                                                'status',
                                                '1'
                                            ) == '1'
                                                ? 'selected'
                                                : ''
                                            }}
                                        >

                                            Active

                                        </option>


                                        <option
                                            value="0"
                                            {{ old(
                                                'status'
                                            ) == '0'
                                                ? 'selected'
                                                : ''
                                            }}
                                        >

                                            Inactive

                                        </option>


                                    </select>

                                </div>


                                <!-- Buttons -->

                                <div class="d-flex gap-2">


                                    <button
                                        type="submit"
                                        class="btn btn-primary"
                                    >

                                        <i class="bx bx-save me-1"></i>

                                        Save Subcategory

                                    </button>


                                    <a
                                        href="{{ route(
                                            'subcategories.index'
                                        ) }}"
                                        class="btn btn-secondary"
                                    >

                                        Cancel

                                    </a>


                                </div>


                            </form>


                        </div>

                    </div>


                </div>

                <!-- / Content -->


            </div>

            <!-- / Layout page -->


        </div>

    </div>


    @include('admin.footer')

    @include('admin.js')


</body>

</html>
