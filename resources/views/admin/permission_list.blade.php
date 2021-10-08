@extends('layouts.admin')


@section('body')
<div class="container pt-4">
    <p class="h1">Permissions List</p>
    <table class="table table-striped">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Name</th>
            <th scope="col">Description</th>
        </tr>
        </thead>
        <tbody>
        @foreach($permissions as $index => $permission)
        <tr>
            <th scope="row"> {{$index + 1}} </th>
            <td>
                <a href="{{ route("permissions", ['id'=>$permission->id]) }}">
                    {{$permission->name}}
                </a>
            </td>
            <td>{{$permission->description}}</td>
        </tr>
        @endforeach
        </tbody>
    </table>
</div>
@endsection