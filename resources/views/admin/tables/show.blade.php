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
            <label for="{{$value['field']}}" class="p-2">{{$value['translation']}}</label>
            <input type="{{$value['type']}}" name="{{$value['field']}}" class="form-control" id="{{$value['field']}}" value="{{$value['data']}}" placeholder="{{$value['translation']}}">
        </div>
        @endforeach
        <button type="submit" class="btn btn-success">
            <span class="material-icons md-light">save</span>
            შენახვა
        </button>
        <a type="submit" href="javascript:void(0)" onclick="history.back()" class="btn btn-danger">
            <span class="material-icons md-light">arrow_back</span>
            დაბრუნება
        </a>
    </form>

@endsection
