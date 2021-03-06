@extends('layouts.admin')
@section('body')
    <div class="container">
        <div class="mt-3">

            <x-error-alert></x-error-alert>


            <p class="h1"> {{$group->alias}}</p>
            <small class="text-muted">{{$group->description}}</small>
            <form action="{{route('groups.update', ['id'=>$group->id])}}" method="post">
            @csrf
            <div class="mb-3">
                <label for="alias" class="form-label">ჯგუფის სახელი</label>
                <input type="text" class="form-control" name="alias" id="alias" value="{{$group->alias}}" >
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">ჯგუფის აღწერა</label>
                <textarea class="form-control" name="description" id="description" rows="3">{{$group->description}}</textarea>
            </div>


            @foreach($permissions as $p)
                @if($gp->has($p->name))
            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" name="permissions[]" value="{{$p->id}}" id="{{$p->name}}" checked>
                <label class="form-check-label" for="{{$p->name}}">
                    {{$p->name}}
                </label>
            </div>
                @else
            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" name="permissions[]" value="{{$p->id}}" id="{{$p->name}}">
                <label class="form-check-label" for="{{$p->name}}">
                    {{$p->name}}
                </label>
            </div>
                @endif
            @endforeach

            <div class="mb-3">
                <button class="btn btn-success">Save</button>
                <a class="btn btn-primary" href="{{route("groups.index")}}">Back</a>
            </div>
            </form>

            <form action="{{route('groups.delete', ['id'=>$group->id])}}" method="POST">
                @csrf
                <button onclick="return confirm('Are you sure you want to delete?')"
                        class="btn btn-danger">
                    Delete
                </button>
            </form>


        </div>
    </div>
@endsection
