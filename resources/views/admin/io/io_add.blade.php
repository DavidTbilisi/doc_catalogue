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



        <div class="mt-3">
            <p class="h1"> საინფორმაციო ობიექტის დამატება </p>

            <form action="{{route("io.store")}}" method="post" id="io" >
            @csrf
            <div class="mb-3">
                <label for="prefix" class="form-label">პრეფიქსი</label>
                <input type="text" class="form-control" name="prefix" id="prefix" placeholder="პრეფიქსი">
            </div>

            <div class="mb-3">
                <label for="identifier" class="form-label">იდენტიფიკატორი</label>
                <input type="text" class="form-control" name="identifier" id="identifier" placeholder="იდენტიფიკატორი">
            </div>

            <div class="mb-3">
                <label for="suffix" class="form-label">სუფიქსი</label>
                <input type="text" class="form-control" name="suffix" id="suffix" placeholder="სუფიქსი">
            </div>

            <div class="mb-3">
                <label for="type" class="form-label">ტიპი</label>
                <select class="form-control" name="type" id="type" onchange="getFields(event)">
                    <option value="">აირჩიეთ ტიპი</option>
                @foreach($types as $type)
                    <option value="{{$type->table}}">{{$type->name}}</option>
                @endforeach
                </select>
            </div>
            <div id="datatable">
                <div class="inputs">

                </div>
            </div>

            <div class="mb-3">
                <button class="btn btn-success" >დამატება</button>
                <a class="btn btn-danger" href="{{route('io.index')}}">უკან</a>
            </div>

            </form>
        </div>
    </div>


    {{--<form id="datatable" action="{{route("io.store")}}"   method="post">--}}
        {{--@csrf--}}
        {{--<div class="inputs">--}}

        {{--</div>--}}

    {{--</form>--}}


    <script>


        function drawFields(element){
            $("#datatable .inputs").append(`
            <div class="mb-3">
                <label for="${element}" class="form-label">${element}</label>
                <input type="text" class="form-control" name="${element}" id="${element}" placeholder="${element}">
            </div>`);

        }


        function getFields(event){
            $.ajax({
                url: "{{route("columns")}}/"+event.target.value,
                success:function(data) {
                    $("#datatable .inputs").empty();
                    $("#datatable .inputs").append(
                        `<input type="hidden" value="${event.target.value}" name="table">`
                    );
                    for (let el in data.data){
                        drawFields(data.data[el].Field)
                    }

                },
                error:function() {
                    $("#datatable .inputs").empty();
                }

            })

        }

        // function save(event) {
        //     event.preventDefault();
        //     $.ajax({
        //         data: $('#datatable').serialize(),
        //         method:"post",
        //         url: $('#datatable').attr("action"),
        //         success:function (data) {
        //             let toPost = $('#io').serialize();
        //
        //             toPost+=`&data_id=${data.inserted_id}`;
        //             toPost+=`&io_type_id=${data.io_type_id}`;
        //
        //             $.ajax({
        //                 method:"post",
        //                 url: $('#io').attr("action"),
        //                 data: toPost,
        //                 success: function (data) {
        //                     console.log(data);
        //                 }
        //             });
        //
        //
        //         }
        //     });
        // }

        // $("#io").on('submit', function (event) {save(event)})

    </script>
@endsection