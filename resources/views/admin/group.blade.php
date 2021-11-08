@extends('layouts.admin')
@section('body')
    <div class="container">
        <div class="mt-3">

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <p class="h1"> {{$group->alias}}</p>
            <small class="text-muted">{{$group->description}}</small>
            <form action="{{route('updategroup', ['id'=>$group->id])}}" method="post">
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
                <a class="btn btn-danger" href="{{url()->previous()}}">Back</a>
            </div>
            </form>
        </div>
    </div>
@endsection