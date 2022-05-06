@extends('layouts.admin')
@section('body')

    <div class="container">
        <x-error-alert></x-error-alert>
    </div>

    <h1 class="mt-4">IO NAME</h1>

    <form action="" method="post">
    @foreach($data["all"] as $permission)
        @if(in_array( $permission->name, $data['permited']))
        <label for="#{{$permission->name}}" checked>{{$permission->name}}</label>
        <input id="{{$permission->name}}" type="checkbox">
        @else
        <label for="#{{$permission->name}}">{{$permission->name}}</label>
        <input id="{{$permission->name}}" type="checkbox">
        @endif
            <br>
    @endforeach
    </form>

@endsection
