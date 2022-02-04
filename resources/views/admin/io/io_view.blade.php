@extends('layouts.admin')
@section('body')

<div class="container">

{{--@dump($io->children)--}}
    <x-error-alert></x-error-alert>
<br>
{{--TODO: get all children from parent--}}
<div id="jstree_demo_div" class="mt-5">
    <ul>
        <li> ფონდები
            <ul>
                @foreach($io->children as $i)
                    <li>
                        <a href="{{route('io.show',["id"=>$i->id])}}">{{$i->reference}}</a>
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
    <li class="list-group-item active"><a id="go-to-data" href="{{route("data.edit",["id"=>$io->data_id, "table"=>$table])}}" class="link-light">მონაცემი</a> </li>

  @if($data)
    @foreach((array)$data as $key => $value)
      @if ( !preg_match("/_at|_id|^id$/i", $key) )
        <li class="list-group-item"> <b> {{$translation[$key]}}: </b> <span> {{$value}} </span> </li>
      @endif
    @endforeach
  @endif

  </ul>

    <form action="{{route('io.delete', ["id"=>$io->id])}}" method="POST">
        @csrf
    <button class="btn btn-danger" onclick="return confirm('Are you sure you want to delete?')">წაშლა</button>
    </form>
  <x-add-button route="{{route('io.add',['io_parent_id'=> $io->id])}}"></x-add-button>

<script>

    $(function () {
        $('#jstree_demo_div').jstree();
    });

</script>
@endsection
