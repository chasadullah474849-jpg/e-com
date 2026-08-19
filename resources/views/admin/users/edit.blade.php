@extends('admin.layout')

@section('content')

<form action="{{ route('users.update',$user->id) }}" method="POST">
    @csrf
    @method('PUT')

    <input type="text" name="name"
           value="{{ $user->name }}"
           class="form-control mb-2">

    <input type="email" name="email"
           value="{{ $user->email }}"
           class="form-control mb-2">

    <button class="btn btn-primary">
        Update User
    </button>

</form>

@endsection
