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
    <li class="list-group-item active">
        <a id="go-to-data" href="{{route("data.edit",["id"=>$io->data_id, "table"=>$table])}}" class="link-light">
            <span class="material-icons md-light"> source </span>
            მონაცემი
        </a>
    </li>

  @if($data)
    @foreach((array)$data as $key => $value)
      @if ( !preg_match("/_at|_id|^id$/i", $key) )
        <li class="list-group-item"> <b> {{$translation[$key]??$key}}: </b> <span> {{$value}} </span> </li>
      @endif
    @endforeach
  @endif

  </ul>

    <div class="row">
    @if(count($io->documents) )
    <div class="col">
        <form action="{{route('io.cleardocs', ["id"=>$io->id])}}" method="POST">
            @csrf
            <button class="btn btn-danger w-100"
                    onclick="return confirm('დარწმუნებული ხარ რომ გინდა დოკუმენტების წაშლა?')">
                    <span class="material-icons md-light"> description </span>
                    დოკუმენტების გასუფთავება
            </button>
        </form>
    </div>
    @endif
    <div class="col">
        <form action="{{route('io.delete', ["id"=>$io->id])}}" method="POST">
            @csrf
            <button class="btn btn-danger w-100"
                    onclick="return confirm('საინფორმაციო ობიექტის წაშლით იშლება ყველა მასზე მიბმული ობიექტი. დარწმუნებული ხარ?')">
                    <span class="material-icons md-light"> account_tree </span>
                    ობიექტის წაშლა
            </button>
        </form>
    </div>
    </div>
    <div class="documents mt-3">
        @foreach($io->documents as $doc)
            <a href="{{asset("/storage/".$doc->filepath)}}" target="_blank">
            <img class="w-100" src="{{asset("/storage/".$doc->filepath)}}" alt="{{$doc->filename}}">
            </a>
        @endforeach
    </div>
    <br>


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
