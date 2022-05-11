@extends('layouts.admin')
@section('body')

<div class="container">
    <x-error-alert></x-error-alert>
</div>

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
                {{$identifiers[$loop->index-1]}}
            </td>
            <td class="reference">{{$io->reference}}</td>
            <td class="type">{{$io->type->name}}</td>
            <td>

            @hasPerm('viewObject')
                @csrf


                <a href="{{route("io.show", ["id"=>$io->id])}}" class="btn btn-success">
                    <span class="material-icons md-light">visibility</span>
                </a>
                @hasPerm('editObject')
                <a href="{{route("io.edit", ["id"=>$io->id])}}" class="btn btn-info">
                    <span class="material-icons md-light">edit</span>
                </a>
                @hasPermEnd

                @isGroup("admin")
                <a href="{{route("io_perms.show", ["id"=>$io->id])}}" class="btn btn-primary">
                    <span class="material-icons md-light">rule</span>
                </a>
                @isGroupEnd()


                @hasPerm('deleteObject')
                <form action="{{route("io.delete", ["id"=>$io->id])}}" method="POST">
                    @csrf()
                    <button type="submit" onclick="return confirm('Are you sure you want to delete?')" class="btn btn-danger">
                        <span class="material-icons md-light">delete</span>
                    </button>
                </form>

                @hasPermEnd
            @hasPermEnd
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
