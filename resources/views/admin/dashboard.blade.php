@extends('layouts.admin')


@section('body')
    <div class="container mx-auto pt-4">
        <div>Name: {{$user->name}}</div>
        <div>Email: {{$user->email}}</div>
        <div>Group: {{@$user->group->name}}</div>
    </div>
@endsection
