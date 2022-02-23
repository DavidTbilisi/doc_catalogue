@extends('layouts.guestoverride')
@section('body')

    <x-error-alert></x-error-alert>


<div class="d-flex justify-content-center align-items-center min-vh-100">
    <div class="card w-25">
        <h5 class="card-header">Log In</h5>
        <div class="card-body">
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="mb-3">
                    <label for="email" class="form-label">Email address</label>
                    <input type="email" class="form-control" name="email" id="email" placeholder="name@example.com">
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" name="password" id="password">
                </div>
                <button class="btn btn-primary w-100">Log In</button>
            </form>
        </div>
    </div>
</div>

@endsection
