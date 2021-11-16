@extends('layouts.admin')
@section('body')

<table class="table">
    <thead>
    <tr>
        <th scope="col">#</th>
        <th scope="col">იდენტიფიკატორი</th>
        <th scope="col">რეფენსი</th>
        <th scope="col">ტიპი</th>
        <th scope="col">მოქმედება</th>
    </tr>
    </thead>
    <tbody>
@foreach($iolist as $io)
        <tr>
            <th scope="row">{{++$loop->index}}</th>
            <td class="identifier">
                {{$io->prefix . $io->identifier . $io->suffix . $io->type->id}}
            </td>
            <td class="reference">{{$io->reference}}</td>
            <td class="type">{{$io->type->name}}</td>
            <td>
                <a href="{{route("io.show", ["id"=>$io->id])}}" class="btn btn-success">View</a>
                <a href="{{route("io.edit", ["id"=>$io->id])}}" class="btn btn-info">Edit</a>
                <button onclick="return confirm('Are you sure you want to delete?')"class="btn btn-danger">Delete</button>
            </td>
        </tr>
@endforeach
    </tbody>
</table>



<div style="position:fixed; right: 100px; bottom: 100px; border-radius: 50%; background-color: #00fa9a; padding: 5px; border: 1px solid black; ">
    <a href="{{route("io.add")}}"> <span class="material-icons md-light">add</span> </a>
</div>
@endsection
