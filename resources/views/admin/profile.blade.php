@extends('layouts.admin')
@section('body')


    <form action="" class="mt-5">

        <div class="mb-3">
            <label for="name" class="form-label">სახელი</label>
            <input type="text" class="form-control" id="name" value="{{$user->name}}">
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">ელ.ფოსტა</label>
            <input type="email" class="form-control" id="email" placeholder="name@example.com" value="{{$user->email}}">
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">პაროლი</label>
            <input type="password" class="form-control" id="password" placeholder="name@example.com">
        </div>

        <div class="mb-3">
            <button class="btn btn-success">შენახვა</button>
        </div>

    </form>

@endsection
