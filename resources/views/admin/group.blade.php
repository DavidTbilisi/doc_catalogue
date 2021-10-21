@extends('layouts.admin')
@section('body')
    <div class="container">
        <div class="mt-3">
            <p class="h1"> {{$group->alias}}</p>
            <small class="text-muted">{{$group->description}}</small>

            <div class="mb-3">
                <label for="alias" class="form-label">Group Name</label>
                <input type="text" class="form-control" id="alias" placeholder="{{@$group->alias}}">
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Group Description</label>
                <textarea class="form-control" id="description" rows="3"></textarea>
            </div>
            <div class="mb-3">
                <button class="btn btn-success">Save</button>
                <a class="btn btn-danger" href="{{url()->previous()}}">Back</a>
            </div>
        </div>
    </div>
@endsection