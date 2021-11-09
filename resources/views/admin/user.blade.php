@extends('layouts.admin')
@section('body')
    <div class="container">
        <div class="mt-3">
            <p class="h1"> {{$user->name}}</p>
            <small class="text-muted">{{$user->description}}</small>


            <form action="{{route("users.update", ['id'=>$user->id])}}" method="post">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">სახელი</label>
                <input type="text" class="form-control" id="name" name="name" value="{{$user->name}}">
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">ელ.ფოსტა</label>
                <input type="email" class="form-control" id="email" name="email" value="{{$user->email}}">
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">როლი</label>
                <select name="group_id" id="group_id" class="form-select">
                    @foreach($groups as $group)
                        @if($group->id == $user->group_id)
                            <option value="{{$group->id}}" selected>{{$group->name}}</option>
                        @else
                            <option value="{{$group->id}}">{{$group->name}}</option>
                        @endif
                    @endforeach
                </select>
            </div>

            @foreach($permissions as $p)
                @if($up->has($p->name))
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
                <a class="btn btn-primary" href="{{route('users.index')}}">Back</a>

            </div>

            </form>

            <form action="{{route('users.delete', ['id'=>$user->id])}}" method="POST">
                @csrf
                <button onclick="return confirm('Are you sure you want to delete?')"
                        class="btn btn-danger">
                    Delete
                </button>
            </form>
        </div>
    </div>
@endsection