@extends('layouts.admin')
@section('body')



<table class="table mt-4">
    <thead>
    <tr>
        <th scope="col">#</th>
        <th scope="col">იდენტიფიკატორი</th>
        <th scope="col">რეფერენსი</th>
        <th scope="col">ტიპი</th>
        <th scope="col">მოქმედება</th>
    </tr>
    </thead>
    <tbody>
@foreach($iolist as $io)
        <tr>
            <th scope="row">{{++$loop->index}}</th>
            <td class="identifier">
                {{$io->prefix}}-{{$io->identifier}}-{{$io->suffix }}
            </td>
            <td class="reference">{{$io->reference}}</td>
            <td class="type">{{$io->type->name}}</td>
            <td>
            <form action="{{route("io.delete", ["id"=>$io->id])}}" method="POST">
                @csrf
                <a href="{{route("io.show", ["id"=>$io->id])}}" class="btn btn-success">View</a>
                <a href="{{route("io.edit", ["id"=>$io->id])}}" class="btn btn-info">Edit</a>
                <button onclick="return confirm('Are you sure you want to delete?')"class="btn btn-danger">Delete</button>
            </form>
            </td>
        </tr>
@endforeach
    </tbody>
</table>

<x-add-button route="{{route('io.add')}}"></x-add-button>

<script>

    $(function () {
        $('#jstree_demo_div').jstree();
    });

</script>
@endsection
