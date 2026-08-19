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


<div class="container mt-4">


<h3 class="mb-4">Edit Product</h3>


@if ($errors->any())

<div class="alert alert-danger">

<ul>

@foreach ($errors->all() as $error)

<li>{{ $error }}</li>

@endforeach

</ul>

</div>

@endif



<!-- PRODUCT UPDATE FORM -->

<form action="{{ route('admin.products.update',$product->id) }}"
      method="POST">

@csrf
@method('PUT')



<div class="mb-3">

<label class="form-label">
Name
</label>

<input
type="text"
name="name"
class="form-control"
value="{{ old('name',$product->name) }}">

</div>




<div class="mb-3">

<label class="form-label">
Description
</label>

<textarea
name="description"
class="form-control"
rows="4">{{ old('description',$product->description) }}</textarea>

</div>




<div class="row">


<div class="col-md-6 mb-3">

<label class="form-label">
Price
</label>

<input
type="number"
step="0.01"
name="price"
class="form-control"
value="{{ old('price',$product->price) }}">

</div>



<div class="col-md-6 mb-3">

<label class="form-label">
Stock
</label>

<input
type="number"
name="stock"
class="form-control"
value="{{ old('stock',$product->stock) }}">

</div>


</div>





<div class="row">


<div class="col-md-6 mb-3">

<label class="form-label">
Category
</label>

<input
type="number"
name="category_id"
class="form-control"
value="{{ old('category_id',$product->category_id) }}">

</div>



<div class="col-md-6 mb-3">

<label class="form-label">
Sub Category
</label>

<input
type="number"
name="subcategory_id"
class="form-control"
value="{{ old('subcategory_id',$product->subcategory_id) }}">

</div>


</div>





<div class="mb-3">

<label class="form-label">
Status
</label>


<select name="status" class="form-control">


<option value="1"
{{ $product->status == 1 ? 'selected':'' }}>
Active
</option>


<option value="0"
{{ $product->status == 0 ? 'selected':'' }}>
Inactive
</option>


</select>


</div>




<button class="btn btn-primary">

Update Product

</button>


<a href="{{route('admin.products.index')}}"
class="btn btn-secondary">

Back

</a>


</form>





<hr class="my-5">





<h4 class="mb-3">
Product Images
</h4>



<div class="row">


@foreach($product->images as $image)


<div class="col-md-4 mb-4">


<div class="card p-2 shadow-sm">


<img

src="{{asset('uploads/products/'.$image->image)}}"

class="img-fluid rounded"

style="
height:200px;
width:100%;
object-fit:cover;
">


<!-- REPLACE IMAGE -->


<form

action="{{route('admin.products.image.replace',$image->id)}}"

method="POST"

enctype="multipart/form-data"

class="mt-3">


@csrf


<input

type="file"

name="image"

class="form-control mb-2"

required>


<button

class="btn btn-primary btn-sm w-100">

Replace Image

</button>


</form>





<!-- DELETE IMAGE -->


<form

action="{{route('admin.products.image.delete',$image->id)}}"

method="POST"

class="mt-2">


@csrf

@method('DELETE')



<button

onclick="return confirm('Delete this image?')"

class="btn btn-danger btn-sm w-100">


Delete Image


</button>


</form>



</div>


</div>


@endforeach


</div>



</div>


</div>


</div>


</div>


@include('admin.footer')

@include('admin.js')


</body>

</html>
