@extends('layouts.dashboard')

@section('title', 'Products')

@section('breadcrumb')
@parent
<li class="breadcrumb-item">products</li>
<li class="breadcrumb-item active">Trash</li>
@endsection

@section('content')
<div class="mb-5">
    <a href="{{ route('dashboard.products.create') }}" class="btn btn-sm btn-outline-primary mr-2">Create</a>
</div>

<x-alert type="success" />
<x-alert type="info" />

<form action="{{ URL::current() }}" method="get" class="d-flex justify-content-between mb-4">
    <x-form.input name="name" placeholder="Name" class="mx-2" :value="request('name')" />
    <select name="status" class="form-control mx-2">
        <option value="">All</option>
        <option value="active" @selected(request('status')=='active' )>Active</option>
        <option value="inactive" @selected(request('status')=='inacitve' )>Inactive</option>
    </select>
    <button class="btn btn-sm btn-secondary mx-2" type="submit">Filter</button>
</form>

<table class="table">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Image</th>
            <th scope="col">Name</th>
            <th scope="col">Category</th>
            <th scope="col">Store</th>
            <th scope="col">Status</th>
            <th scope="col">Creation</th>
            <th scope="col" colspan="2">Handle</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($products as $product)
        <tr>
            <th scope="row">{{ $product->id }}</th>
            <td><img src="{{ asset('storage/' . $product->image) }}" alt="YET!" height="70px" width="70px"></td>
            <td>{{ $product->name }}</td>
            <td>{{ $product->category ? $product->category->name : 'Parent' }}</td>
            <td>{{ $product->store->name }}</td>
            <td>{{ $product->status }}</td>
            <td>{{ $product->created_at }}</td>
            <td>
                <a href="{{ route('dashboard.products.edit', $product->id) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
            </td>
            <td>
                <form action="{{ route('dashboard.products.destroy', $product->id) }}" method="post">
                    @csrf
                    <!-- from method spoofing -->
                    <input type="hidden" name="_method" value="delete">
                    @method('delete')
                    <button class="btn btn-sm btn-outline-danger">Delete</button>
                </form>
            </td>
        </tr>
        @empty
        <td colspan="9">Empty!</td>
        @endforelse
    </tbody>
</table>

{{ $products->withQueryString()->links() }}
@endsection
