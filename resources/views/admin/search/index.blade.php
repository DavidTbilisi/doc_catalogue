@extends('layouts.admin')
@section('body')
    <div class="container pt-4">
        <x-error-alert></x-error-alert>


        <form action="{{route('search')}}" method="get">
            @csrf
            <select id="table-selector" class="form-select form-select-lg mb-3" aria-label=".form-select">
                <option selected>აირჩიე ტიპი</option>

            @foreach($types as $type)
                <option value="{{$type->table}}">{{$type->name}}</option>
            @endforeach
            </select>

            <input class="form-control mb-3" type="text" placeholder="საძიებო სიტყვა">

            <input type="submit" class='btn btn-success' value="ძიება">
        </form>


        <script>

            function drawInputs(type = "text", placeholder = "ადგილის დამკავებელი") {

// debugger
                let input = `<input class="dynamic form-control mb-3" type="${type}" placeholder="${placeholder}">`
                    // input = parser.parseFromString(input, "text/html");

                let input_count = document.querySelector("form").childElementCount -2
                let referenceNode = document.querySelector("form").children[input_count]
                referenceNode.insertAdjacentHTML( "beforebegin", input);
            }



            function getFields(event){
                $.ajax({
                    url: "{{route("columns")}}/"+event.target.value,
                    success:function(data) {
                        console.log(data)
                        $(".dynamic").remove()
                        for (const [key, value] of Object.entries(data.data)) {
                            let technicalName = value.Field
                            let translation = data.translation[technicalName];
                            let type = value.Type;
                            drawInputs(type, translation)
                        }

                    },
                    error:function() {
                        $("#datatable .inputs").empty();
                    }

                })

            }


            $("#table-selector").on("change", function() {
                getFields(event)
            })


        </script>
    </div>
@endsection
