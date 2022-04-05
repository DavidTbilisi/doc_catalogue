@extends('layouts.admin')
@section('body')
    <div class="container pt-4">
        <x-error-alert></x-error-alert>


        <form action="{{route('searchresults')}}" method="POST">
            @csrf
            <select id="table-selector" class="form-select form-select-lg mb-3" name="table" aria-label=".form-select">
            @foreach($types as $type)
                @if(Session::has("search_table") && Session::get("search_table") == $type->table)
                    <option selected value="{{$type->table}}">{{$type->name}}</option>
                @else
                    <option value="{{$type->table}}">{{$type->name}}</option>
                @endif
            @endforeach
            </select>

            <input type="submit" class='btn btn-success' value="ძიება">
        </form>


        <script>

            function drawInputs(type = "text", placeholder = "ადგილის დამკავებელი", technical="") {

// debugger
                let input = `
  <div class="row" id="${technical}">
    <div class="col">
      <input class="dynamic form-control mb-3" type="button" disabled value="${placeholder}">
    </div>
    <div class="col">
        <input class="dynamic dinput form-control mb-3" type="${type}" placeholder="${placeholder}" name="${technical}">
    </div>
  </div>
`
                    // input = parser.parseFromString(input, "text/html");

                let input_count = document.querySelector("form").childElementCount -1
                let referenceNode = document.querySelector("form").children[input_count]
                referenceNode.insertAdjacentHTML( "beforebegin", input);
            }



            function getFields(event, table = false){
                table = table == false? event.target.value: table;

                $.ajax({
                    url: "{{route("columns")}}/"+table,
                    success:function(data) {
                        console.log(data)
                        $(".dynamic").remove()
                        for (const [key, value] of Object.entries(data.data)) {
                            let technicalName = value.Field;
                            let translation = data.translation[technicalName];
                            let type = value.Type;

                            if (value.Type == "longtext") {
                                type = "text"
                            } else if (value.Type == "int(11)") {
                                type = "number"
                            }


                            drawInputs(type, translation, technicalName)
                        }

                    },
                    error:function() {
                        $("#datatable .inputs").empty();
                    }

                })

            }


            function fill_from_session() {
                let inputs = document.querySelectorAll(".dynamic.dinput");
                let values = JSON.parse('{!! json_encode(Session::get("search_fields"), JSON_FORCE_OBJECT| JSON_UNESCAPED_UNICODE) !!}')
                values = Object.values(values)

                inputs.forEach((element, index)=>{
                    console.log(index, element)
                    element.value = values[index]
                })

            }



            $("#table-selector").on("change", function() {
                getFields(event)
            })

            @if(Session::has("search_table"))
            getFields(false,"{{ Session::get("search_table")}}")
            setTimeout(() => { fill_from_session() }, 500)
            @endif
        </script>
    </div>
@endsection
