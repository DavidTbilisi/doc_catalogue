<div class="input-group">
    <!-- Button trigger modal -->
    <button type="button" class="btn btn-primary w-100 m-2" data-bs-toggle="modal" data-bs-target="#pluploadermodal">
        <span class="material-icons md-light"> photo </span>
        სურათების დამატება (0)
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
                        let uploader = $("#uploader").pluploadQueue({
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
                            },
                            init: {
                                UploadComplete: function (up) {
                                    setTimeout(function () {
                                        location.reload()
                                    }, 1000)
                                }
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
</div>
