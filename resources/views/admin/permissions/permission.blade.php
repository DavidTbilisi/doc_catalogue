@extends('layouts.admin')
@section('body')
<div class="container">
    <div class="mt-3">
        <p class="h1"> {{$permission->name}}</p>
        <small class="text-muted">{{$permission->description}}</small>

        <div class="mb-3">
            <label for="name" class="form-label">Permission Name</label>
            <input type="text" class="form-control" disabled id="name" value="{{$permission->name}}">
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Permission Description</label>
            <textarea class="form-control" id="description" rows="3">{{$permission->description}}</textarea>
        </div>
        <div class="mb-3">
            <button class="btn btn-success">Save</button>
            <a class="btn btn-primary" href="{{route('permissions.index')}}">Back</a>
        </div>
    </div>
</div>
@endsection
