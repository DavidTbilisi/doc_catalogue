@extends('layouts.admin')
@section('body')

<div class="container">
    <x-error-alert></x-error-alert>
    <br>
    <div id="jstree_demo_div" class="mt-5">  </div>

    <div class="jumbotron jumbotron-fluid">
      <div class="container">
        <h1 class="display-4"> საინფორმაციო ობიექტი
            <a href="{{route('io.edit', ['id'=>$io->id])}}" class="material-icons md-light">edit</a>
        </h1>
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
        $('#jstree_demo_div').jstree({
            'core' : {
                'data' :
                    @php
                        $json = json_encode($children,  JSON_PRETTY_PRINT );
                        echo $json;
                    @endphp

            }
        });
        $("#jstree_demo_div").bind("select_node.jstree", function(e, data) {
            console.log(data.node.original)
            window.location.href = data.node.original.a_attr;
        });
    });

</script>
@endsection
