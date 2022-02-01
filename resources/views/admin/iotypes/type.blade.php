@extends('layouts.admin')
@section('body')


    <x-error-alert></x-error-alert>


    <h1 class="mt-3"> {{$tablename->name}}</h1>

    <div class="alert alert-warning" role="alert">
        არსებული კოლონების წაშლამ ან შეცვლამ შეიძლება გამოიწვიოს ამ ცხრილში არსებული ინფორმაციის სრული დაკარგვა...
    </div>

    <form action="{{route("types.columnchange")}}" method="POST">
    @csrf
    <div class="inputs">
        <input type="hidden" name="table" value="{{$tablename->table}}">
    @foreach($columns as $col)
        <div class="form-group mb-2">


            <div class="row">
                <!-- თარგმანი -->
                <div class="col">
                    <label for="{{$col->Field}}">ველის სახელი<span onclick="removeColumn(event)" class="material-icons md-light">delete</span> </label>
                    <input type="text" class="form-control" id="{{$col->Field}}" name="names[]" value="{{$translation[$col->Field] ?? $col->Field}}" >
                </div>

                <!-- ტექნიკური  -->
                <div class="col">
                    <label for="{{$col->Field}}">ტექნიკური სახელი <span onclick="removeColumn(event)" class="material-icons md-light">delete</span> </label>
                    <input type="text" oninput="changeName(event)" data-oldName="{{$col->Field}}" class="form-control" id="{{$col->Field}}" name="cols[]" value="{{$col->Field}}" >
                </div>


            </div>


        </div>
    @endforeach
    </div>
        <div class="row">
            <div class="col">
                <a href="{{route('types.index')}}" class="btn btn-danger w-100">უკან</a>
            </div>
            <div class="col">
                <button class="btn btn-success w-100">შენახვა</button>
            </div>
        </div>
    </form>
    <div class="add-button">
        <div style="position:fixed; right: 100px; bottom: 100px; border-radius: 50%; background-color: #00fa9a; padding: 5px; border: 1px solid black; cursor:pointer">
            <div" onclick="addColumn()"> <span class="material-icons md-light">add</span> </div>
        </div>
    </div>

    <script>

        const input = (name) => `
        <div class="form-group mb-2">
            <div class="row">
                <label for="${name}">${name} <span onclick="removeColumn(event)" class="material-icons md-light">delete</span> </label>
                <input oninput="changeName(event)" type="text" class="form-control" id="${name}" name="${name}">
            </div>
        </div>`

        function addColumn() {
            document.querySelector(".inputs").insertAdjacentHTML("beforeEnd", input("newColumn"))
        }

        function changeName(event) {
            // ფუნქციის გამოძახება ხდება input FN ში
            // საჭიროა ფორმის POST-ით გაშვებისთვის

            let This = event.target; // New Column
            let val = event.target.value; // User input.
            const label = This.previousElementSibling
            const rmBtn = `<span onclick="removeColumn(event)" class="material-icons md-light">delete</span>`;

            const newValue = () => {
                let toReturn;
                if (This.dataset.oldname != undefined) {
                    toReturn = This.dataset.oldname+","+val
                    return This.dataset.oldname+","+toReturn.replaceAll(This.dataset.oldname+",","")
                } return val;
            }


            This.setAttribute("name", "cols[]"); // Required
            This.setAttribute("id", val);
            This.value =  newValue();

            label.setAttribute("for",val);
            label.innerHTML = val + " " + rmBtn;

        }


        function removeColumn(event){
            let This = event.target;
            const column = This
                            .parentElement
                            .parentElement
                            .parentElement;
            if (confirm("დარწმუნებული ხარ რომ გინდა წაშალო ეს ველი?")) {
                column.remove()
            }

        }


    </script>

@endsection
