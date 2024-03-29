@extends('layouts.admin')
@section('body')


    <form action="{{route("types.store")}}" method="post" class="mt-5 ">

{{--    <div class="alert alert-warning" role="alert">--}}
{{--        ტექნიკური სახელები უნდა შედგებოდეს მხოლოდ ლათინური ასოებისგან.--}}
{{--    </div>--}}

        <x-error-alert></x-error-alert>
        <h1>ობიექტის ტიპის დამატება</h1>

        @csrf

        {{-- Table Name --}}
        <div class="form-group mb-5">
            <div class="row">
                <div class="col">
                    <label for="typename">ობიექტის ტიპის სახელი</label>
                    <input type="text"
                           class="form-control"
                           id="typename"
                           name="name"
                           placeholder="Type name"
                           pattern="[ა-ჰ0-9 ]{1,20}"
                           oninvalid="this.setCustomValidity('სახელი უნდა შედგებოდეს ქართული სიმბოლოებისგან')"
                           oninput="this.setCustomValidity('')"
                    >
                </div>
{{--                <div class="col">--}}
{{--                    <label for="tablename">ობიექტის ტიპის ტექნიკური სახელი</label>--}}
{{--                    <input type="text" class="form-control" id="tablename" name="tablename" placeholder="Type name" pattern="[a-z]{1,20}">--}}
{{--                </div>--}}

            </div>
        </div>


        {{-- Fields Group --}}
        <div class="inputs" id="fields">
            <div class="form-group mt-2">
                <div class="row">

                    <div class="col-8 mt-2">

                        <label for="field1 mb-1">ტიპის აღწერის ველის დასახელება</label>
                        <input type="text"
                                name="names[]"
                                class="form-control"
                                id="field1"
                                placeholder="Field"
                                pattern="[ა-ჰ0-9 ]{1,20}"
                               oninvalid="this.setCustomValidity('სახელი უნდა შედგებოდეს ქართული სიმბოლოებისგან')"
                               oninput="this.setCustomValidity('')"
                               value="">
                    </div>


                    <div class="col mt-2">
                        <label for="type">ტიპი</label>
                        <select name="type[]" class="form-control" id="Type">
                            <option value="longText">Text</option>
                            <option value="integer">Number</option>
                            <option value="date">Date</option>
                        </select>
                    </div>

                </div>
            </div>
        </div>

        <div class="form-group mt-5">
            <button type="submit" class="btn btn-primary">ობიექტის შენახვა</button>
            <button class="btn btn-success" onclick="addInput(event)">ველის დამატება</button>
        </div>
    </form>


    <script>
        let incremented = 1;
        function addInput(event) {
            event.preventDefault();
            let newField = document.querySelector("#fields .form-group").innerHTML;

            // increment input id
            incremented ++;
            newField = newField.replaceAll("field1", "field"+incremented);

            let fieldGroups = document.querySelector(".inputs");
            fieldGroups.insertAdjacentHTML("beforeEnd", newField)
        }


    </script>




@endsection
