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


                    {{-- SUCCESS MESSAGE --}}

                    @if(session('success'))

                        <div
                            class="alert alert-success alert-dismissible fade show mb-4"
                            role="alert"
                        >

                            <div class="d-flex align-items-center">

                                <i class="bx bx-check-circle me-2 fs-4"></i>

                                <span>
                                    {{ session('success') }}
                                </span>

                            </div>


                            <button
                                type="button"
                                class="btn-close"
                                data-bs-dismiss="alert"
                                aria-label="Close"
                            ></button>

                        </div>

                    @endif


                    {{-- ERROR MESSAGE --}}

                    @if(session('error'))

                        <div
                            class="alert alert-danger alert-dismissible fade show mb-4"
                            role="alert"
                        >

                            <div class="d-flex align-items-center">

                                <i class="bx bx-error-circle me-2 fs-4"></i>

                                <span>
                                    {{ session('error') }}
                                </span>

                            </div>


                            <button
                                type="button"
                                class="btn-close"
                                data-bs-dismiss="alert"
                                aria-label="Close"
                            ></button>

                        </div>

                    @endif


                    {{-- VALIDATION ERRORS --}}

                    @if($errors->any())

                        <div
                            class="alert alert-danger alert-dismissible fade show mb-4"
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


                    <!-- AJAX ALERT -->

                    <div id="ajax-alert-container"></div>


                    <!-- Page Header -->

                    <div
                        class="d-flex justify-content-between align-items-center mb-4"
                    >

                        <div>

                            <h4 class="fw-bold py-3 mb-0">

                                <span class="text-muted fw-light">
                                    Sub Categories /
                                </span>

                                List

                            </h4>

                        </div>


                        <div>

                            <a
                                href="{{ route('subcategories.create') }}"
                                class="btn btn-primary"
                            >

                                <i class="bx bx-plus me-1"></i>

                                Add Subcategory

                            </a>

                        </div>

                    </div>


                    <!-- SubCategory Card -->

                    <div class="card">

                        <div
                            class="card-header d-flex justify-content-between align-items-center"
                        >

                            <h5 class="mb-0">
                                Sub Categories
                            </h5>

                            <span class="text-muted">
                                Total:
                                {{ $subcategories->count() }}
                            </span>

                        </div>


                        <div class="table-responsive">

                            <table
                                class="table table-hover"
                                id="subcategories-table"
                            >

                                <thead>

                                    <tr>

                                        <th>
                                            #
                                        </th>

                                        <th>
                                            Name
                                        </th>

                                        <th>
                                            Description
                                        </th>

                                        <th>
                                            Category
                                        </th>

                                        <th>
                                            Status
                                        </th>

                                        <th width="180">
                                            Actions
                                        </th>

                                    </tr>

                                </thead>


                                <tbody>


                                    @forelse(
                                        $subcategories
                                        as $subcategory
                                    )


                                        <tr
                                            id="subcategory-{{ $subcategory->uuid }}"
                                        >


                                            <!-- ID -->

                                            <td>

                                                <span
                                                    class="text-muted"
                                                    title="{{ $subcategory->uuid }}"
                                                >

                                                    {{ $loop->iteration }}

                                                </span>

                                            </td>


                                            <!-- NAME -->

                                            <td>

                                                <strong>

                                                    {{ $subcategory->name }}

                                                </strong>

                                            </td>


                                            <!-- DESCRIPTION -->

                                            <td>

                                                @if($subcategory->description)

                                                    {{ Str::limit(
                                                        $subcategory->description,
                                                        60
                                                    ) }}

                                                @else

                                                    <span
                                                        class="text-muted"
                                                    >
                                                        No description
                                                    </span>

                                                @endif

                                            </td>


                                            <!-- CATEGORY -->

                                            <td>

                                                @if($subcategory->category)

                                                    <span
                                                        class="badge bg-label-primary"
                                                    >

                                                        {{ $subcategory->category->name }}

                                                    </span>

                                                @else

                                                    <span
                                                        class="badge bg-secondary"
                                                    >

                                                        No Category

                                                    </span>

                                                @endif

                                            </td>


                                            <!-- STATUS -->

                                            <td>

                                                @if(
                                                    $subcategory->status == 1
                                                    ||
                                                    $subcategory->status === '1'
                                                )

                                                    <span
                                                        class="badge bg-success"
                                                    >

                                                        Active

                                                    </span>

                                                @else

                                                    <span
                                                        class="badge bg-danger"
                                                    >

                                                        Inactive

                                                    </span>

                                                @endif

                                            </td>


                                            <!-- ACTIONS -->

                                            <td>


                                                <!-- EDIT -->

                                                <a
                                                    href="{{ route(
                                                        'subcategories.edit',
                                                        $subcategory->id
                                                    ) }}"
                                                    class="btn btn-sm btn-warning"
                                                >

                                                    <i class="bx bx-edit-alt"></i>

                                                    Edit

                                                </a>


                                                <!-- DELETE -->

                                                <form
                                                    action="{{ route(
                                                        'subcategories.destroy',
                                                        $subcategory->id
                                                    ) }}"
                                                    method="POST"
                                                    class="delete-subcategory d-inline"
                                                >

                                                    @csrf

                                                    @method('DELETE')


                                                    <button
                                                        type="submit"
                                                        class="btn btn-sm btn-danger"
                                                    >

                                                        <i class="bx bx-trash"></i>

                                                        Delete

                                                    </button>

                                                </form>


                                            </td>


                                        </tr>


                                    @empty


                                        <tr>

                                            <td
                                                colspan="6"
                                                class="text-center py-5"
                                            >

                                                <div>

                                                    <i
                                                        class="bx bx-category fs-1 text-muted"
                                                    ></i>

                                                    <h5 class="mt-2">
                                                        No Subcategories Found
                                                    </h5>

                                                    <p class="text-muted">
                                                        Create your first subcategory.
                                                    </p>


                                                    <a
                                                        href="{{ route(
                                                            'subcategories.create'
                                                        ) }}"
                                                        class="btn btn-primary"
                                                    >

                                                        <i class="bx bx-plus me-1"></i>

                                                        Add Subcategory

                                                    </a>

                                                </div>

                                            </td>

                                        </tr>


                                    @endforelse


                                </tbody>

                            </table>

                        </div>

                    </div>


                </div>

                <!-- / Content -->


            </div>

            <!-- / Layout page -->


        </div>

    </div>


    <!-- Delete AJAX -->

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>


    <script>

        $(document).on(
            'submit',
            '.delete-subcategory',
            function(e)
        {

            e.preventDefault();


            let form = $(this);

            let url = form.attr('action');


            if (
                !confirm(
                    'Are you sure you want to delete this subcategory?'
                )
            ) {

                return;

            }


            $.ajax({

                url: url,

                type: 'POST',

                data: form.serialize(),

                headers: {

                    'X-Requested-With': 'XMLHttpRequest'

                },


                success: function(response)
                {

                    form.closest('tr').fadeOut(
                        300,
                        function()
                        {

                            $(this).remove();


                            $('#ajax-alert-container').html(`
                                <div
                                    class="alert alert-success alert-dismissible fade show"
                                    role="alert"
                                >
                                    <i class="bx bx-check-circle me-1"></i>
                                    Subcategory deleted successfully.

                                    <button
                                        type="button"
                                        class="btn-close"
                                        data-bs-dismiss="alert"
                                    ></button>

                                </div>
                            `);

                        }
                    );

                },


                error: function(xhr)
                {

                    let message =
                        'Delete failed. Please try again.';


                    if (
                        xhr.responseJSON &&
                        xhr.responseJSON.message
                    ) {

                        message =
                            xhr.responseJSON.message;

                    }


                    $('#ajax-alert-container').html(`
                        <div
                            class="alert alert-danger alert-dismissible fade show"
                            role="alert"
                        >
                            <i class="bx bx-error-circle me-1"></i>
                            ${message}

                            <button
                                type="button"
                                class="btn-close"
                                data-bs-dismiss="alert"
                            ></button>

                        </div>
                    `);

                }

            });

        });

    </script>


    @include('admin.footer')

    @include('admin.js')


</body>

</html>
