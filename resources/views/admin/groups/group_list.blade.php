@extends('layouts.admin')


@section('body')
<div class="container pt-4">
    <x-error-alert></x-error-alert>


    @if (session()->has("message"))
        <div class="alert alert-info">
            {{session()->get("message")}}
        </div>
    @endif



    <p class="h1">Groups List</p>
    <table class="table table-striped">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Name</th>
            <th scope="col">Members</th>
            <th scope="col">Permissions</th>
        </tr>
        </thead>
        <tbody>
        @foreach($groups as $index => $group)
        <tr>
            <th scope="row"> {{$index + 1}} </th>
            <td>
                @isGroup("admin")
                <a href="{{route("groups.update", ['id'=>$group->id])}}">
                {{$group->alias}}
                </a>
                @notGroup()
                <p>{{$group->alias}}</p>
                @isGroupEnd
            </td>
            <td>{{$group->users->count()}}</td>
            <td>
                @foreach($group->permissions as $perm)
                    <span class="btn btn-info m-1">{{$perm->name}}</span>
                @endforeach
            </td>
        </tr>
        @endforeach
        </tbody>
    </table>

    @isGroup('admin')
    <x-add-button route="{{route('groups.add')}}"></x-add-button>
    @isGroupEnd()

</div>
@endsection
