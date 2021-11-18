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
            <label for="typename">ტიპის სახელი</label>
            <input type="text" class="form-control" id="typename" name="name" placeholder="Type name">
        </div>



        <div class="form-group mt-2">
            <div class="row">
            <div class="col-8">
                <label for="field1">Filed</label>
                <input type="text" name="field[]" class="form-control" id="field1" placeholder="Field" value="">
            </div>
            <div class="col">
                <label for="type">Type</label>
                <select name="type[]" class="form-control" id="Type">
                    <option value="varchar">Text</option>
                    <option value="int">Number</option>
                    <option value="text">Long text</option>
                </select>
            </div>
            </div>
        </div>

        <div class="form-group mt-2">
            <div class="row">
            <div class="col-8">
                <label for="field1">Filed</label>
                <input type="text" name="field[]" class="form-control" id="field1" placeholder="Field" value="">
            </div>
            <div class="col">
                <label for="type">Type</label>
                <select name="type[]" class="form-control" id="Type">
                    <option value="varchar">Text</option>
                    <option value="int">Number</option>
                    <option value="text">Long text</option>
                </select>
            </div>
            </div>
        </div>



        <div class="form-group mt-5">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>

    </form>

@endsection