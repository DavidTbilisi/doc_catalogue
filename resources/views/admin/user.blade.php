@extends('layouts.admin')
@section('body')
    <div class="container">
        <div class="mt-3">
            <p class="h1"> {{$user->name}}</p>
            <small class="text-muted">{{$user->description}}</small>

            <div class="mb-3">
                <label for="name" class="form-label">User Name</label>
                <input type="text" class="form-control" id="name" placeholder="{{$user->name}}">
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">User Description</label>
                <textarea class="form-control" id="description" rows="3"></textarea>
            </div>
            <div class="mb-3">
                <button class="btn btn-success">Save</button>
                <a class="btn btn-danger">Back</a>
            </div>
        </div>
    </div>
@endsection