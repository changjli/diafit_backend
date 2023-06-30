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
                <th scope="col">transaction_id</th>
                <th scope="col">food_name</th>
                <th scope="col">food_quantity</th>
            </tr>
        </thead>
        <tbody>
            @php
                $counter = 1;
            @endphp
            @foreach ($transactionDetails as $transactionDetail)
                <tr>
                    <th scope="row">{{ $counter }}</th>
                    <td>{{ $transactionDetail->transaction_id }}</td>
                    <td>{{ $transactionDetail->Food->name }}</td>
                    <td>{{ $transactionDetail->food_quantity }}</td>
                </tr>
                @php
                    $counter++;
                @endphp
            @endforeach
        </tbody>
    </table>
@endsection
