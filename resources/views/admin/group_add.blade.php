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
            <p class="h1"> ჯგუფის შექმნა </p>
            <small class="text-muted"></small>

            <form action="{{route("groups.store")}}" method="post">
            @csrf
            <div class="mb-3">
                <label for="alias" class="form-label">ჯგუფის სახელი</label>
                <input type="text" class="form-control" id="alias" name="alias" placeholder="სახელი">
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">ჯგუფის აღწერა</label>
                <textarea class="form-control" name="description" id="description" rows="3"></textarea>
            </div>

            <div class="mb-3">
                <button class="btn btn-success">შენახვა</button>
                <a class="btn btn-danger" href="{{route("groups.index")}}">უკან</a>
            </div>

            </form>

        </div>
    </div>
@endsection