@extends('admin.layout')

@section('content')

<form action="{{ route('users.store') }}" method="POST">
    @csrf

    <input type="text" name="name"
           class="form-control mb-2"
           placeholder="Name">

    <input type="email" name="email"
           class="form-control mb-2"
           placeholder="Email">

    <input type="password" name="password"
           class="form-control mb-2"
           placeholder="Password">

    <button class="btn btn-success">
        Save User
    </button>

</form>

@endsection
