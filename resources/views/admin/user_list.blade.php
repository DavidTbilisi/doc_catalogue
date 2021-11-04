@extends('layouts.admin')


@section('body')
<div class="container pt-4">
    <p class="h1">Users List</p>
    <table class="table table-striped">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Name</th>
            <th scope="col">Email</th>
            <th scope="col">Group</th>
            <th scope="col">Permissions</th>
        </tr>
        </thead>
        <tbody>
        @foreach($users as $index => $user)
        <tr>
            <th scope="row"> {{$index + 1}} </th>
            <td>
               <a href="{{route("users", ['id'=>$user->id])}}">
                    {{$user->name}}
                </a>
            </td>
            <td>{{$user->email}}</td>
            <td>{{$user->group->alias}}</td>
            <td>
                @foreach($user->permissions as $perm)
                    <span>{{$perm->name}}</span>
                @endforeach
            </td>
        </tr>
        @endforeach
        </tbody>
    </table>
</div>
@endsection