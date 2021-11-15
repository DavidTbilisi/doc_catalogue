@extends('layouts.admin')
@section('body')

<table class="table">
    <thead>
    <tr>
        <th scope="col">#</th>
        <th scope="col">პრეფიქსი</th>
        <th scope="col">იდენტიფიკატორი</th>
        <th scope="col">სუფიქსი</th>
        <th scope="col">დონე</th>
        <th scope="col">რეფენსი</th>
        <th scope="col">ტიპი</th>
        <th scope="col">მოქმედება</th>
    </tr>
    </thead>
    <tbody>
@foreach($iolist as $io)
        <tr>
            <th scope="row">{{++$loop->index}}</th>
            <td>{{$io->prefix}}</td>
            <td>{{$io->identifier}}</td>
            <td>{{$io->suffix}}</td>
            <td>{{$io->level}}</td>
            <td>{{$io->reference}}</td>
            <td>{{$io->type->name}}</td>
            <td>edit | delete </td>
        </tr>
@endforeach
    </tbody>
</table>

@endsection
