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


                <div class="container-xxl flex-grow-1 container-p-y">


                    <!-- Header -->

                    <div
                        class="d-flex justify-content-between align-items-center mb-4"
                    >

                        <h4 class="fw-bold py-3 mb-0">

                            <span class="text-muted fw-light">
                                Sub Categories /
                            </span>

                            Edit

                        </h4>


                        <a
                            href="{{ route('subcategories.index') }}"
                            class="btn btn-secondary"
                        >

                            <i class="bx bx-arrow-back me-1"></i>

                            Back

                        </a>

                    </div>


                    <!-- Errors -->

                    @if($errors->any())

                        <div class="alert alert-danger">

                            <ul class="mb-0">

                                @foreach($errors->all() as $error)

                                    <li>
                                        {{ $error }}
                                    </li>

                                @endforeach

                            </ul>

                        </div>

                    @endif


                    <!-- Edit Card -->

                    <div class="card">

                        <div class="card-header">

                            <h5 class="mb-0">
                                Edit Subcategory
                            </h5>

                        </div>


                        <div class="card-body">


                            <form
                                action="{{ route(
                                    'subcategories.update',
                                    $subcategory->id
                                ) }}"
                                method="POST"
                            >

                                @csrf

                                @method('PUT')


                                <!-- Name -->

                                <div class="mb-3">

                                    <label class="form-label">

                                        Sub Category Name

                                    </label>


                                    <input
                                        type="text"
                                        name="name"
                                        class="form-control"
                                        value="{{ old(
                                            'name',
                                            $subcategory->name
                                        ) }}"
                                        required
                                    >

                                </div>


                                <!-- Description -->

                                <div class="mb-3">

                                    <label class="form-label">

                                        Description

                                    </label>


                                    <textarea
                                        name="description"
                                        rows="4"
                                        class="form-control"
                                    >{{ old(
                                        'description',
                                        $subcategory->description
                                    ) }}</textarea>

                                </div>


                                <!-- Category -->

                                <div class="mb-3">

                                    <label class="form-label">

                                        Parent Category

                                    </label>


                                    <select
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
                                                {{ old(
                                                    'category_id',
                                                    $subcategory->category_id
                                                ) == $category->id
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

                                <div class="mb-3">
    <label for="status" class="form-label">
        STATUS
    </label>

    <select
        name="status"
        id="status"
        class="form-select"
        required
    >
        <option value="1"
            {{ old('status', $subcategory->status) == 1 ? 'selected' : '' }}>
            Active
        </option>

        <option value="0"
            {{ old('status', $subcategory->status) == 0 ? 'selected' : '' }}>
            Inactive
        </option>
    </select>
</div>


                                <!-- Buttons -->

                                <button
                                    type="submit"
                                    class="btn btn-primary"
                                >

                                    <i class="bx bx-save me-1"></i>

                                    Update Subcategory

                                </button>


                                <a
                                    href="{{ route(
                                        'subcategories.index'
                                    ) }}"
                                    class="btn btn-secondary"
                                >

                                    Cancel

                                </a>


                            </form>


                        </div>

                    </div>


                </div>

            </div>

        </div>

    </div>


    @include('admin.footer')

    @include('admin.js')


</body>

</html>
