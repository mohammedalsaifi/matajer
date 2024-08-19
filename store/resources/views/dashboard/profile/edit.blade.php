@extends('layouts.dashboard')

@section('title', 'Edit Profile')

@section('breadcrumb')
@parent
<li class="breadcrumb-item active">Edit Profile</li>
@endsection

@section('content')

<x-alert type="success" />

<form action="{{ route('dashboard.profile.update') }}" method="post" enctype="multipart/form-data">
    @csrf
    @method('patch')

    <div class="form-row">
        <div class="col-md-6">
            <x-form.input name="first_name" label="First Name" :value="$user->profile->first_name" placeholder="First Name" />
        </div>
        <div class="col-md-6">
            <x-form.input name="last_name" label="Last Name" :value="$user->profile->last_name" placeholder="Last Name" />
        </div>
    </div>
    <br>
    <div class="form-row">
        <div class="col-md-6">
            <x-form.input name="birthday" type="date" label="Birthday" :value="$user->profile->birthday" placeholder="Birthday" />
        </div>
        <div class="col-md-6">
            <x-form.radio name="gender" label="Gender" placeholder="Gender" :options="['male'=>'Male', 'female'=>'Female']" :checked="$user->profile->gender" />
        </div>
    </div>
    <br>
    <div class="form-row">
        <div class="col-md-4">
            <x-form.input name="street_address" label="Street Address" placeholder="Street Address" :value="$user->profile->street_address" />
        </div>
        <div class="col-md-4">
            <x-form.input name="city" label="City" placeholder="City" :value="$user->profile->city" />
        </div>
        <div class="col-md-4">
            <x-form.input name="state" label="State" placeholder="State" :value="$user->profile->state" />
        </div>
    </div>
    <br>
    <div class="form-row">
        <div class="col-md-4">
            <x-form.input name="postal_code" label="Postal Code" placeholder="Postal Code" :value="$user->profile->postal_code" />
        </div>
        <div class="col-md-4">
            <x-form.select name="country" :options="$countries" label="Country" placeholder="Country" :selected="$user->profile->country" />
        </div>
        <div class="col-md-4">
            <x-form.select name="locale" :options="$locales" label="Locale" placeholder="Locale" :selected="$user->profile->locale" />
        </div>
    </div>
    <br>
    <button type="submit" class="btn btn-outline-success form-control">Save</button>
</form>

@endsection
