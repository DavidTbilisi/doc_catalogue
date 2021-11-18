@extends('layouts.admin')
@section('body')


    <table class="table table-striped">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Name</th>
        </tr>
        </thead>
        <tbody>
        @foreach($types as $type)
        <tr>
            <th scope="row">{{++$loop->index}}</th>
            <td><a href="{{route("types.show",['id'=>$type->table])}}">{{$type->name}}</a></td>
        </tr>
        @endforeach
        </tbody>
    </table>


    <div style="position:fixed; right: 100px; bottom: 100px; border-radius: 50%; background-color: #00fa9a; padding: 5px; border: 1px solid black; ">
        <a href="{{route("types.add")}}"> <span class="material-icons md-light">add</span> </a>
    </div>
@endsection