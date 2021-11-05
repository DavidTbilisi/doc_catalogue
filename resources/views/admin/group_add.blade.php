@extends('layouts.admin')
@section('body')
    <div class="container">
        <div class="mt-3">
            <p class="h1"> ჯგუფის შექმნა </p>
            <small class="text-muted"></small>

            <div class="mb-3">
                <label for="alias" class="form-label">ჯგუფის სახელი</label>
                <input type="text" class="form-control" id="alias" placeholder="სახელი">
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">ჯგუფის აღწერა</label>
                <textarea class="form-control" id="description" rows="3"></textarea>
            </div>

            <div class="mb-3">
                <button class="btn btn-success">შენახვა</button>
                <a class="btn btn-danger" href="{{url()->previous()}}">უკან</a>
            </div>

        </div>
    </div>
@endsection