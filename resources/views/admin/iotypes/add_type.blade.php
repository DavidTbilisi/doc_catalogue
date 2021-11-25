@extends('layouts.admin')
@section('body')

    <form action="{{route("types.store")}}" method="post" class="mt-5 ">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @csrf

        <div class="form-group mb-5">
            <div class="row">
                <div class="col">
                    <label for="typename">ტიპის სახელი</label>
                    <input type="text" class="form-control" id="typename" name="name" placeholder="Type name">
                </div>
                <div class="col">
                    <label for="tablename">ტექნიკური სახელი</label>
                    <input type="text" class="form-control" id="tablename" name="tablename" placeholder="Type name">
                </div>

            </div>
        </div>


            <div class="inputs">
                <div class="form-group mt-2">
                    <label for="parent_id">Parent</label>
                    <select name="parent_id" class="form-control" id="parent_id">
                        <option value="0">No Parent</option>
                        @foreach($types as $type)
                            <option value="{{$type->table}}">{{$type->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>


            <div class="inputs" id="fields">
                <div class="form-group mt-2">
                    <div class="row">
                        <div class="col-8">
                            <label for="field1">Filed</label>
                            <input type="text" name="field[]" class="form-control" id="field1" placeholder="Field"
                                   value="">
                        </div>
                        <div class="col">
                            <label for="type">Type</label>
                            <select name="type[]" class="form-control" id="Type">
                                <option value="string">Text</option>
                                <option value="integer">Number</option>
                                <option value="longText">Long text</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group mt-5">
                <button type="submit" class="btn btn-primary">Submit</button>
                <button class="btn btn-primary" onclick="addInput(event)">Add Input</button>
            </div>
    </form>


    <script>

        function addInput(event) {
            event.preventDefault();
            document.querySelector(".inputs").insertAdjacentHTML("beforeEnd", document.querySelector("#fields .form-group").innerHTML)
        }


    </script>



@endsection