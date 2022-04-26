@extends('layouts.admin')
@section('body')

@if($errors->any())
<div class="container mt-3">
<div class="alert alert-danger alert-dismissible fade show">
{{$errors->first()}}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
</div>
@endif


    <table class="table table-striped">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Name</th>
            <th scope="col">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($types as $type)
        <tr>
            <th scope="row">{{++$loop->index}}</th>
            <td><a href="{{route("types.show",['id'=>$type->table])}}">{{$type->name}}</a></td>
            @isGroup("admin")
            <form action="{{route("types.delete",['id'=>$type->id])}}" method="post">
                @csrf
                <input type="hidden" name="table" value="{{$type->table}}">
                <td>
                    <button class="btn btn-danger" onclick="return confirm('Do you really want to delete?')">
                        <span class="material-icons md-light"> delete_outline </span>
                    </button>
                </td>
            </form>
            @notGroup()
            <td>
                <button class="btn btn-danger" disabled>
                    <span class="material-icons md-light"> delete_outline </span>
                </button>
            </td>
            @isGroupEnd
        </tr>
        @endforeach
        </tbody>
    </table>


    <x-add-button route="{{route('types.add')}}"></x-add-button>

@endsection
