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
{{--TODO: get all children from parent--}}
<div id="jstree_demo_div" class="mt-5">
    <ul>
        <li> ფონდები
            <ul>
                @foreach([1,2,3,4] as $i)
                    <li>
                        {{$i}} {{$i}}
                    </li>
                @endforeach
            </ul>
        </li>
    </ul>
</div>



<div class="jumbotron jumbotron-fluid">
  <div class="container">
    <h1 class="display-4"> საინფორმაციო ობიექტი </h1>
  </div>
</div>

  <ul class="list-group">
    <li class="list-group-item"> <b> ტიპი: </b> <span>  {{$io->type->name}} </span> </li>
    <li class="list-group-item"> <b> რეფერენსი: </b> <span> {{$io->reference}} </span> </li>
    <li class="list-group-item"> <b> სუფიქსი: </b> <span> {{$io->suffix}} </span> </li>
    <li class="list-group-item"> <b> იდენტიფიკატორი: </b> <span> {{$io->identifier}} </span> </li>
    <li class="list-group-item"> <b> პრეფიქსი: </b> <span> {{$io->prefix}} </span> </li>
  </ul>


  <ul class="list-group mt-5 mb-5">
      {{$data}}
    <li class="list-group-item active"><a href="{{1}}" class="link-light">მონაცემი</a> </li>
    @foreach((array)$data[0] as $key => $value)
      @if ( !preg_match("/_at|_id|^id$/i", $key) )
        <li class="list-group-item"> <b> {{$translation[$key]}}: </b> <span> {{$value}} </span> </li>
      @endif
    @endforeach
  </ul>

  <x-add-button route="{{route('io.add',['io_parent_id'=> $io->id])}}"></x-add-button>

<script>

    $(function () {
        $('#jstree_demo_div').jstree();
    });

</script>
@endsection
