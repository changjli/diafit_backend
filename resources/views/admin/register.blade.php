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
    <div class="container h-100">
        <p class="text-center h1 fw-bold mb-5 mx-1 mx-md-4 mt-4">Login</p>

        <form class="mx-1 mx-md-4" action="/login" method="POST">
            @csrf
            <div class="d-flex flex-row align-items-center mb-4">
                <i class="fas fa-envelope fa-lg me-3 fa-fw"></i>
                <div class="form-outline flex-fill mb-0">
                    <input type="email" id="email" class="form-control" name="email">
                    <label class="form-label" for="email">email</label>
                    @error('email')
                        {{ $message }}
                    @enderror
                </div>
            </div>

            <div class="d-flex flex-row align-items-center mb-4">
                <i class="fas fa-lock fa-lg me-3 fa-fw"></i>
                <div class="form-outline flex-fill mb-0">
                    <input type="password" id="password" class="form-control" name="password">
                    <label class="form-label" for="password">password</label>
                    @error('password')
                        {{ $message }}
                    @enderror
                </div>
            </div>

            <div class="d-flex justify-content-center mx-4 mb-3 mb-lg-4">
                <button type="submit" class="btn btn-primary btn-lg">Login</button>
            </div>
        </form>

        <a href="/forgot-password" class="d-flex justify-content-center">Forgot password ?</a>
    </div>
@endsection
