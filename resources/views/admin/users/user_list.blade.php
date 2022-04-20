@extends('layouts.admin')
@section('body')
<div class="container pt-4">

    @if (session()->has("message"))
        <div class="alert alert-info">
            {{session()->get("message")}}
        </div>
    @endif

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
                @isGroup("admin")
               <a href="{{route("users.edit", ['id'=>$user->id])}}">
                    {{$user->name}}
                </a>
                @notGroup()
                <p>{{$user->name}}</p>
                @isGroupEnd()

            </td>
            <td>{{$user->email}}</td>
            <td>{{$user->group->alias}}</td>
            <td>
                @foreach($user->permissions as $perm)
                    <span class="btn btn-info m-1">{{$perm->name}}</span>
                @endforeach
            </td>
        </tr>
        @endforeach
        </tbody>
    </table>


    @isGroup('admin')
    <x-add-button route="{{route('users.add')}}"></x-add-button>
    @isGroupEnd()

</div>
@endsection
