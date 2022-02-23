@extends('layouts.guestoverride')
@section('body')

    <x-error-alert></x-error-alert>

    <div class="d-flex justify-content-center align-items-center min-vh-100">
        <div class="card w-25">
            <h5 class="card-header">Register</h5>
            <div class="card-body">
                <form action="{{url('register')}}" method="post">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" name="name" id="name">
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email address</label>
                        <input type="email" class="form-control" name="email" id="email" placeholder="name@example.com">
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" name="password" id="password">
                    </div>


                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Password confirmation</label>
                        <input type="password" class="form-control"name="password_confirmation" id="password">
                    </div>


                    <div class="mt-3">
                        <button href="#" class="btn btn-success w-100">Register</button>
                    </div>
                </form>

        </div>
        </div>
    </div>

@endsection
