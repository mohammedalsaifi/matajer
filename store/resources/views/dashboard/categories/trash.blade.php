@extends('layouts.dashboard')

@section('title', 'trash categories')

@section('breadcrumb')
@parent
<li class="breadcrumb-item">Categories</li>
<li class="breadcrumb-item active">Trash</li>
@endsection

@section('content')
<div class="mb-5">
    <a href="{{ route('dashboard.categories.index') }}" class="btn btn-sm btn-outline-primary mr-2">Back</a>
</div>

<x-alert type="success" />
<x-alert type="info" />

<table class="table">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Image</th>
            <th scope="col">Name</th>
            <th scope="col">Status</th>
            <th scope="col">Deleted_At</th>
            <th scope="col" colspan="2">Handle</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($categories as $category)
        <tr>
            <th scope="row">{{ $category->id }}</th>
            <td><img src="{{ asset('storage/' . $category->image) }}" alt="YET!" height="70px" width="70px"></td>
            <td>{{ $category->name }}</td>
            <td>{{ $category->status }}</td>
            <td>{{ $category->deleted_at }}</td>
            <td>
                <form action="{{ route('dashboard.categories.restore', $category->id) }}" method="post">
                    @csrf
                    <!--
                    from method spoofing
                    <input type="hidden" name="_method" value="delete">
                    -->
                    @method('put')
                    <button class="btn btn-sm btn-outline-success">Restore</button>
                </form>
            </td>
            <td>
                <form action="{{ route('dashboard.categories.force-delete', $category->id) }}" method="post">
                    @csrf
                    <!--
                    from method spoofing
                    <input type="hidden" name="_method" value="delete">
                    -->
                    @method('delete')
                    <button class="btn btn-sm btn-outline-danger">Delete</button>
                </form>
            </td>
        </tr>
        @empty
        <td colspan="7">Empty!</td>
        @endforelse
    </tbody>
</table>

{{ $categories->withQueryString()->links() }}
@endsection
