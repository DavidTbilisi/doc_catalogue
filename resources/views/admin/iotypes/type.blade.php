@extends('layouts.admin')
@section('body')




    <h1 class="mt-3"> {{$tablename->name}}</h1>

    @foreach($columns as $col)
        <div class="form-group mb-2">
            <div class="row">
                <label for="{{$col->Field}}">{{$col->Field}}</label>
                <input type="text" class="form-control" id="{{$col->Field}}" name="name" placeholder="{{$col->Field}}">
            </div>
        </div>
    @endforeach


    <div style="position:fixed; right: 100px; bottom: 100px; border-radius: 50%; background-color: #00fa9a; padding: 5px; border: 1px solid black; ">
        <a href="{{route("types.add")}}"> <span class="material-icons md-light">add</span> </a>
    </div>
@endsection