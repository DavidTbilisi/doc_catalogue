@extends('layouts.admin')
@section('body')


    <x-error-alert></x-error-alert>

    <x-alert></x-alert>

    <div class="jumbotron jumbotron-fluid">
        <div class="container">
            <h1 class="display-4"> {{$type->name}} </h1>
        </div>
    </div>


    <form action="{{route("data.update",['id'=>$id])}}" method="POST">
        @csrf
        <input type="hidden" name="table" value="{{$type->table}}">
        @foreach($data as $index => $value)
        <div class="form-group mb-3">
            <label for="{{$index}}" class="p-2">{{$translation[$index]}}</label>
            <input type="text" name="{{$index}}" class="form-control" id="{{$index}}" value="{{$value}}" placeholder="{{$translation[$index]}}">
        </div>
        @endforeach
        <button type="submit" class="btn btn-primary">Save</button>
    </form>

@endsection
