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

            <form action="{{route("io.store")}}" method="post">
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
                <select class="form-control" name="type" id="type">
                    <option value="">აირჩიეთ ტიპი</option>
                    <option value="1">ფონდი</option>
                    <option value="2">ანაწერი</option>
                    <option value="3">ფაილი</option>
                </select>
            </div>

            <div class="mb-3">
                <button class="btn btn-success">დამატება</button>
                <a class="btn btn-danger" href="{{route('io.index')}}">უკან</a>
            </div>

            </form>
        </div>
    </div>
@endsection