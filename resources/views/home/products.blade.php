<!DOCTYPE html>
<html lang="en">

<head>
    @include('home.header')

    <style>

        .page-title{
            font-size:55px;
            font-weight:600;
            margin-bottom:0;
        }

        .product-count{
            text-align:right;
            font-size:22px;
            margin-top:18px;
        }

        .product-card{
            margin-bottom:35px;
            transition:.3s;
        }

        .product-card:hover{
            transform:translateY(-8px);
        }

        .product-image{
            position:relative;
            overflow:hidden;
            border-radius:10px;
        }

        .product-image img{
            width:100%;
            height:380px;
            object-fit:cover;
            transition:.4s;
        }

        .product-card:hover img{
            transform:scale(1.08);
        }

        .wishlist{
            position:absolute;
            top:15px;
            right:15px;
            width:45px;
            height:45px;
            border-radius:50%;
            background:#fff;
            display:flex;
            justify-content:center;
            align-items:center;
            text-decoration:none;
            color:#000;
            box-shadow:0 10px 20px rgba(0,0,0,.15);
        }

        .product-info{
            text-align:center;
            padding-top:20px;
        }

        .product-name{
            font-size:20px;
            font-weight:600;
            margin-bottom:8px;
        }

        .price{
            font-size:22px;
            font-weight:bold;
            color:#000;
            margin-bottom:15px;
        }

        .details-btn{
            border-radius:50px;
            padding:10px 28px;
        }

        .pagination{
            justify-content:center;
        }

    </style>

</head>

<body class="homepage">

@include('home.nav')

<section class="padding-large">

<div class="container">

<div class="row align-items-center mb-5">

<div class="col-lg-6">

<h1 class="page-title">
All Products
</h1>

</div>

<div class="col-lg-6">

<div class="product-count">

Showing {{ $products->total() }} Products

</div>

</div>

</div>

<div class="row">

@foreach($products as $product)

<div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-12">

<div class="product-card">

<div class="product-image">

@if($product->images->count())

<img src="{{ asset('uploads/products/'.$product->images->first()->image) }}">

@else

<img src="{{ asset('images/no-image.png') }}">

@endif
<a href="#" class="wishlist">

    <i class="bi bi-heart"></i>

</a>

</div>

<div class="product-info">

<div class="product-name">

{{ $product->name }}

</div>

<div class="price">

Rs {{ number_format($product->price,2) }}

</div>

<a href="{{ route('product.details',$product->uuid) }}"
class="btn btn-dark details-btn">

View Details

</a>

</div>

</div>

</div>

@endforeach

</div>

<div class="mt-5">

{{ $products->links() }}

</div>

</div>

</section>

@include('home.footer')

@include('home.js')




</body>



</html>
