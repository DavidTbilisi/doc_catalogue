@extends('layouts.admin')


@section('body')
<div class="container pt-4">
    <table class="table table-striped">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Name</th>
            <th scope="col">Members</th>
        </tr>
        </thead>
        <tbody>
        @foreach($groups as $group)
        <tr>
            <th scope="row">1</th>
            <td>{{$group->alias}}</td>
            <td>{{$group->users->count()}}</td>
        </tr>
        @endforeach
        </tbody>
    </table>
</div>
@endsection