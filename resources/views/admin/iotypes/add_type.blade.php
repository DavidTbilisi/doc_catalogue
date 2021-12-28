@extends('layouts.admin')
@section('body')


    <form action="{{route("types.store")}}" method="post" class="mt-5 ">

    <div class="alert alert-warning" role="alert">
        "ტექნიკური სახელი" გულისხმობს იმას, რომ ველებში შეიძლება იყოს შეყვანილი მხოლოდ ლათინური ასოები.
    </div>

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

        <!-- Table Name -->
        <div class="form-group mb-5">
            <div class="row">
                <div class="col">
                    <label for="typename">ობიექტის ტიპის სახელი</label>
                    <input type="text" class="form-control" id="typename" name="name" placeholder="Type name">
                </div>
                <div class="col">
                    <label for="tablename">ობიექტის ტიპის ტექნიკური სახელი</label>
                    <input type="text" class="form-control" id="tablename" name="tablename" placeholder="Type name">
                </div>

            </div>
        </div>


        <!-- Fields Group -->
        <div class="inputs" id="fields">
            <div class="form-group mt-2">
                <div class="row">
                    <div class="col-8 mt-2">
                        <label for="field1 mb-1">ობიექტის ტიპის აღწერის ველის ტექნიკური დასახელება</label>
                        <input type="text" 
                                name="field[]" 
                                class="form-control" 
                                id="field1" 
                                placeholder="Field"
                                value="">
                    </div>
                    <div class="col mt-2">
                        <label for="type">ტიპი</label>
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
            <button class="btn btn-success" onclick="addInput(event)">Add Input</button>
        </div>
    </form>


    <script>

        function addInput(event) {
            event.preventDefault();
            let newField = document.querySelector("#fields .form-group").innerHTML;
            let fieldGroups = document.querySelector(".inputs");
            fieldGroups.insertAdjacentHTML("beforeEnd", newField)
        }


    </script>




@endsection