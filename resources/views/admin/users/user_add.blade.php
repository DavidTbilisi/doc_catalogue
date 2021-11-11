@extends('layouts.admin')
@section('body')
    <div class="container">

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif



        <div class="mt-3">
            <p class="h1"> მომხმარებლის დამატება </p>

            <form action="{{route("users.store")}}" method="post">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">მომხმარებლის სახელი</label>
                <input type="text" class="form-control" name="name" id="name" placeholder="სახელი">
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">ელ.ფოსტა</label>
                <input type="email" class="form-control" name="email" id="email" placeholder="ელ.ფოსტა">
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">პაროლი</label>
                <input type="password" class="form-control" name="password" id="password" >
            </div>



            <div class="mb-3">
                <button class="btn btn-success">დამატება</button>
                <a class="btn btn-danger" href="{{route('users.index')}}">უკან</a>
            </div>

            </form>
        </div>
    </div>
@endsection