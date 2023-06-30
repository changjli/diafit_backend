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

    <p class="text-center h1 fw-bold mb-5 mx-1 mx-md-4 mt-4">Add Food</p>

    <form class="mx-1 mx-md-4" action="/food" method="POST">
        @csrf
        <div class="form-group">
            <label for="name">name</label>
            <input class="form-control" id="name" aria-describedby="emailHelp" placeholder="Enter name" name="name">
            @error('name')
                {{ $message }}
            @enderror
        </div>
        <div class="form-group">
            <label for="serving_size">serving_size</label>
            <input type="number" class="form-control" id="serving_size" aria-describedby="emailHelp"
                placeholder="Enter serving_size" name="serving_size">
            @error('serving_size')
                {{ $message }}
            @enderror
        </div>
        <div class="form-group">
            <label for="price">price</label>
            <input class="form-control" id="price" aria-describedby="emailHelp" placeholder="Enter price"
                name="price">
            @error('price')
                {{ $message }}
            @enderror
        </div>
        <div class="form-group">
            <label for="stock">stock</label>
            <input class="form-control" id="stock" aria-describedby="emailHelp" placeholder="Enter stock"
                name="stock">
            @error('stock')
                {{ $message }}
            @enderror
        </div>
        <div class="form-group">
            <label for="date">date</label>
            <input type="date"class="form-control" id="date" aria-describedby="emailHelp" placeholder="Enter date"
                name="date">
            @error('date')
                {{ $message }}
            @enderror
        </div>
        <div class="form-group">
            <label for="image">image</label>
            <input class="form-control" id="image" aria-describedby="emailHelp" placeholder="Enter image"
                name="image">
            @error('image')
                {{ $message }}
            @enderror
        </div>

        <div class="d-flex justify-content-center mx-4 mb-3 mb-lg-4">
            <button type="submit" class="btn btn-primary btn-lg">Add</button>
        </div>
    </form>
@endsection
