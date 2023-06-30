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

    <table class="table">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">id</th>
                <th scope="col">name</th>
                <th scope="col">serving_size</th>
                <th scope="col">calories</th>
                <th scope="col">proteins</th>
                <th scope="col">fats</th>
                <th scope="col">carbs</th>
                <th scope="col">price</th>
                <th scope="col">stock</th>
                <th scope="col">date</th>
            </tr>
        </thead>
        <tbody>
            @php
                $counter = 1;
            @endphp
            @foreach ($foods as $food)
                <tr>
                    <th scope="row">{{ $counter }}</th>
                    <td>{{ $food->id }}</td>
                    <td>{{ $food->name }}</td>
                    <td>{{ $food->serving_size }}</td>
                    <td>{{ $food->calories }}</td>
                    <td>{{ $food->proteins }}</td>
                    <td>{{ $food->fats }}</td>
                    <td>{{ $food->carbs }}</td>
                    <td>{{ $food->price }}</td>
                    <td>{{ $food->stock }}</td>
                    <td>{{ $food->date }}</td>
                    <td>
                        <form action="/food/{{ $food->id }}">
                            <button type="submit" class="btn btn-outline-info">Update</button>
                        </form>
                    </td>
                    <td>
                        <form action="/food/{{ $food->id }}" method="POST">
                            @csrf
                            @method('delete')
                            <button type="submit" class="btn btn-outline-danger">Delete</button>
                        </form>
                    </td>
                </tr>
                @php
                    $counter++;
                @endphp
            @endforeach
        </tbody>
    </table>

    <form class="mx-1 mx-md-4" action="/food/create" method="GET">
        @csrf
        <div class="d-flex justify-content-start mx-4 mb-3 mb-lg-4">
            <button type="submit" class="btn btn-outline-primary btn-lg">Add</button>
        </div>
    </form>
@endsection
