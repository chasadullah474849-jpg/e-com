<!DOCTYPE html>
<html
  lang="en"
  class="light-style layout-menu-fixed"
  dir="ltr"
  data-theme="theme-default"
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

<div class="container mt-4">

    <div class="d-flex justify-content-between mb-3">
        <h3>Products</h3>

        <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
            Add Product
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <table class="table table-bordered table-striped">

        <thead>
            <tr>
                <th>UUID</th>
                <th>Name</th>
                <th>Image</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Status</th>
                <th width="180">Action</th>
            </tr>
        </thead>

        <tbody>

        @forelse($products as $product)

            <tr id="product-{{ $product->uuid }}">

                <td>{{ $product->uuid }}</td>

                <td>{{ $product->name }}</td>

                {{-- Product Image --}}
               <td>

@if($product->images->count())

<div class="d-flex flex-wrap gap-2">

@foreach($product->images as $image)

<img
src="{{ asset('uploads/products/'.$image->image) }}"
width="80"
height="80"
style="object-fit:cover;border-radius:5px;">

@endforeach

</div>

@else

<span class="text-danger">
No Image
</span>

@endif

</td>

                <td>${{ number_format($product->price,2) }}</td>

                <td>{{ $product->stock }}</td>

                <td>

                    @if($product->status)

                        <span class="badge bg-success">
                            Active
                        </span>

                    @else

                        <span class="badge bg-danger">
                            Inactive
                        </span>

                    @endif

                </td>

                <td>

                    <a href="{{ route('admin.products.edit',$product->id) }}"
                       class="btn btn-primary btn-sm">
                        Edit
                    </a>

                    <form action="{{ route('admin.products.destroy',$product->id) }}"
                          method="POST"
                          class="delete-product d-inline">

                        @csrf
                        @method('DELETE')

                        <button class="btn btn-danger btn-sm">
                            Delete
                        </button>

                    </form>

                </td>

            </tr>

        @empty

            <tr>

                <td colspan="7" class="text-center">
                    No Products Found
                </td>

            </tr>

        @endforelse

        </tbody>

    </table>

</div>

</div>
</div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script>
$(document).on('submit','.delete-product',function(e){

    e.preventDefault();

    let form=$(this);

    if(confirm('Delete product?')){

        $.ajax({

            url:form.attr('action'),

            type:'POST',

            data:form.serialize(),

            success:function(){

                form.closest('tr').fadeOut();

            },

            error:function(){

                alert('Delete Failed');

            }

        });

    }

});
</script>

@include('admin.footer')
@include('admin.js')

</body>
</html>
