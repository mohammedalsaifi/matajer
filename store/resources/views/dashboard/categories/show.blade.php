@extends('layouts.dashboard')

@section('title', $category->name)

@section('breadcrumb')
@parent
<li class="breadcrumb-item">Categories</li>
<li class="breadcrumb-item active">{{ $category->name }}</li>
@endsection

@section('content')

<table class="table">
    <thead>
        <tr>
            <th scope="col">Image</th>
            <th scope="col">Name</th>
            <th scope="col">Store</th>
            <th scope="col">Status</th>
            <th scope="col">Creation</th>

        </tr>
    </thead>
    <tbody>
        @php
        $products = $category->products()->with('store')->simplePaginate(5);
        @endphp
        @forelse ($products as $product)
        <tr>
            <td><img src="{{ asset('storage/' . $product->image) }}" alt="YET!" height="70px" width="70px"></td>
            <td>{{ $product->name }}</td>
            <td>{{ $product->store->name }}</td>
            <td>{{ $product->status }}</td>
            <td>{{ $product->created_at }}</td>
        </tr>
        @empty
        <td colspan="9">Empty!</td>
        @endforelse
    </tbody>
    {{ $products->links() }}
</table>

@endsection
