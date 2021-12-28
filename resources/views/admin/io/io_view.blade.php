@extends('layouts.admin')
@section('body')

<div class="container">

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<br>

<div class="jumbotron jumbotron-fluid">
  <div class="container">
    <h1 class="display-4"> საინფორმაციო ობიექტი </h1>
    <p class="lead">This is a modified jumbotron that occupies the entire horizontal space of its parent.</p>
  </div>
</div>

<ul class="list-group">
  <li class="list-group-item"> <b> ტიპი: </b> <span>  {{$io->type->name}} </span> </li>
  <li class="list-group-item"> <b> რეფერენსი: </b> <span> {{$io->reference}} </span> </li>
  <li class="list-group-item"> <b> სუფიქსი: </b> <span> {{$io->suffix}} </span> </li>
  <li class="list-group-item"> <b> იდენტიფიკატორი: </b> <span> {{$io->identifier}} </span> </li>
  <li class="list-group-item"> <b> პრეფიქსი: </b> <span> {{$io->prefix}} </span> </li>
  <li class="list-group-item"> <b> მონაცემი: </b> <span> {{$data}} </span> </li>
</ul>




</div>

@endsection
