@extends('layouts.admin')
@section('body')

<div class="container">
    <x-error-alert></x-error-alert>
    <br>
    <div id="jstree_demo_div" class="mt-5">  </div>

    <div class="jumbotron jumbotron-fluid">
      <div class="container">
        <h1 class="display-4"> საინფორმაციო ობიექტი
            @hasPerm('editObject')
            <a href="{{route('io.edit', ['id'=>$io->id])}}" class="material-icons md-light">edit</a>
            @hasPermEnd
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
        @hasPerm('editObject')
        <a id="go-to-data" class="link-light" href="{{route("data.edit",["id"=>$io->data_id, "table"=>$table])}}" >
        @hasPermEnd
            <span class="material-icons md-light"> source </span>
            მონაცემების რედაქტირება
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
    @hasPerm('deleteDocument')
    @if(count($io->documents))
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
    @hasPermEnd

    @hasPerm('deleteObject')
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
    @hasPermEnd

    @hasPerm('viewDocument')
    <div class="documents mt-3">
        @foreach($io->documents as $doc)
            <a href="{{asset("/storage/".$doc->filepath)}}" target="_blank">
                @if(strpos($doc->mimetype, "image") == -1)
{{--                    <img class="w-100" src="{{asset("/storage/".$doc->filepath)}}" alt="{{$doc->filename}}">--}}
                    <a target="_blank" href="{{asset("/storage/".$doc->filepath)}}">{{$doc->mimetype}}</a>
                @endif
            </a>
        @endforeach
    </div>
    @hasPermEnd


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



@section("iobuttons")
        @hasPerm('viewDocument')
        @if(count($io->documents))
            <a href="{{route('viewer', ['io_id'=>$io->id])}}" target="_blank" class="btn btn-success w-100 m-2">
                <span class="material-icons md-light"> photo_library </span>
                სურათების ნახვა {{count($io->documents) >0? "(" .count($io->documents) . ")": ""}}
            </a>
        @else
            <form action="{{route("io.update",['id'=>$io->id])}}" method="post" id="io" class="form-inline" enctype="multipart/form-data">
                @csrf
                <div class="input-group">
                    <!-- Button trigger modal -->
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#pluploadermodal">
                        Launch demo modal
                    </button>

                    <!-- Modal -->
                    <div class="modal fade" id="pluploadermodal" tabindex="-1" aria-labelledby="pluploader" aria-hidden="true">
                        <div class="modal-dialog modal-fullscreen">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="pluploader">სურათების ატვირთვა</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">

                                    <script type="text/javascript" src="{{ asset('js/plupload.full.min.js') }}"></script>
                                    <script type="text/javascript" src="{{ asset('js/jquery.plupload.queue.js') }}"></script>
                                    <script type="text/javascript" src="{{ asset('js/i18n/ka.js') }}"></script>
                                    <link rel="stylesheet" href="{{asset('css/jquery.plupload.queue.css')}}">

                                    <div style="float: left; margin-right: 20px">
                                        <div id="uploader" style="width:98.5vw; min-height: 330px; height: 600px ">Your browser doesn't support native upload.</div>
                                    </div>

                                    <script>
                                    $("#uploader").pluploadQueue({
                                        // General settings
                                        runtimes : 'html5,flash,silverlight,html4',
                                        url : '{{route("io.update",['id'=>$io->id])}}',
                                        chunk_size : '1mb',
                                        unique_names : true,

                                        max_file_size : '10mb',
                                        filters : [
                                            {title : "Image files", extensions : "jpg,gif,png"},
                                            {title : "Zip files", extensions : "zip"}
                                        ],
                                            // Rename files by clicking on their titles
                                        rename: true,

                                        // Sort files
                                        sortable: true,

                                        // Enable ability to drag'n'drop files onto the widget (currently only HTML5 supports that)
                                        dragdrop: true,
                                        // Resize images on clientside if we can
                                        resize : {
                                            width : 200,
                                            height : 200,
                                            quality : 90,
                                            crop: true // crop to exact dimensions
                                        },
                                        // Views to activate
                                        views: {
                                            list: true,
                                            thumbs: true, // Show thumbs
                                            active: 'thumbs'
                                        },
                                        // Flash settings
                                        flash_swf_url : '{{ asset('js/Moxie.swf') }}',

                                        // Silverlight settings
                                        silverlight_xap_url : '{{ asset('js/Moxie.xap') }}',
                                        multipart_params:{
                                            "_token": "{{csrf_token()}}",
                                            "prefix":"{{$io->prefix}}",
                                            "identifier":"{{$io->identifier}}",
                                            "suffix":"{{$io->suffix}}",
                                            "io_type_id":"{{$io->io_type_id}}",
                                        }


                                    });

                                    </script>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <input type="file" class="form-control btn btn-success m-2" id="files" name="files[]" multiple>
                    <input type="hidden" name="prefix" value="{{$io->prefix}}">
                    <input type="hidden" name="identifier" value="{{$io->identifier}}">
                    <input type="hidden" name="suffix" value="{{$io->suffix}}">
                    <input type="hidden" name="io_type_id" value="{{$io->io_type_id}}">

                    <button type="submit" class="btn btn-success">
                        <span class="material-icons md-light"> upload_file </span>
                    </button>
                </div>
{{--                <div class="input-group mb-3">--}}
{{--                    <label class="input-group-text" for="files"><span class="material-icons md-light"> upload_file </span></label>--}}
{{--                    <input type="file" class="form-control" id="inputGroupFile01">--}}
{{--                    <input type="file" class="form-control" id="files" name="files[]" multiple>--}}
{{--                </div>--}}
            </form>
        @endif
        @hasPermEnd

        <a href="{{route("elfinder.ckeditor")."#elf_".$el_path}}" target="_blank" class="btn btn-success w-100 m-2">
            <span class="material-icons md-light"> description </span>
            ფაილების ნახვა ({{$pool_count}})
        </a>

@endsection
