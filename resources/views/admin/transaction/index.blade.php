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
                <th scope="col">user_id</th>
                <th scope="col">user_email</th>
                <th scope="col">total_price</th>
                <th scope="col">voucher_code</th>
                <th scope="col">payment</th>
                <th scope="col">status</th>
                <th scope="col">location</th>
                <th scope="col">delivery</th>
                <th scope="col">detail</th>
            </tr>
        </thead>
        <tbody>
            @php
                $counter = 1;
            @endphp
            @foreach ($transactions as $transaction)
                <tr>
                    <th scope="row">{{ $counter }}</th>
                    <td>{{ $transaction->id }}</td>
                    <td>{{ $transaction->user_id }}</td>
                    <td>{{ $transaction->User->email }}</td>
                    <td>{{ $transaction->total_price }}</td>
                    <td>{{ $transaction->voucher_code }}</td>
                    <td>{{ $transaction->payment }}</td>
                    <td>{{ $transaction->status }}</td>
                    <td>{{ $transaction->location }}</td>
                    <td>{{ $transaction->delivery }}</td>
                    <td>
                        <form action="/transaction/detail/{{ $transaction->id }}" method="GET">
                            @csrf
                            <input type="hidden" name="status" value="ready">
                            <button type="submit" class="btn btn-outline-primary">Detail</button>
                        </form>
                    </td>
                </tr>
                @php
                    $counter++;
                @endphp
            @endforeach
        </tbody>
    </table>
@endsection
