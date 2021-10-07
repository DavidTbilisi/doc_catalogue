@extends('layouts.admin')


@section('body')
<div class="container pt-4">
    <table class="table table-striped">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Name</th>
            <th scope="col">Email</th>
            <th scope="col">Group</th>
        </tr>
        </thead>
        <tbody>
        @foreach($users as $user)
        <tr>
            <th scope="row">1</th>
            <td>{{$user->name}}</td>
            <td>{{$user->email}}</td>
            <td>{{$user->group->alias}}</td>
        </tr>
        @endforeach
        </tbody>
    </table>
</div>
@endsection