@extends('layout.main')

@section('main')
    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <p>{{ session('error') }}</p>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <p>{{ session('success') }}</p>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    <p class="text-center h1 fw-bold mb-5 mx-1 mx-md-4 mt-4">Login</p>

    <form class="mx-1 mx-md-4" action="/login" method="POST">
        @csrf
        <div class="form-group">
            <label for="email">Email address</label>
            <input type="email" class="form-control" id="email" aria-describedby="emailHelp" placeholder="Enter email"
                name="email">
            @error('email')
                {{ $message }}
            @enderror
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" class="form-control" id="password" aria-describedby="emailHelp" placeholder="Password"
                name="password">
            @error('password')
                {{ $message }}
            @enderror
        </div>

        <div class="d-flex justify-content-center mx-4 mb-3 mb-lg-4">
            <button type="submit" class="btn btn-primary btn-lg">Login</button>
        </div>
    </form>

    <a href="/forgot-password" class="d-flex justify-content-center">Forgot password ?</a>
@endsection
