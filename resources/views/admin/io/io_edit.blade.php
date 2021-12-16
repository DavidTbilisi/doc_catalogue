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

            <form action="{{route("io.update",['id'=>$io->id])}}" method="post" id="io" >
            @csrf

            <input type="hidden" name="io_type_id" value="{{$io->io_type_id}}">

            <div class="mb-3">
                <label for="prefix" class="form-label">პრეფიქსი</label>
                <input type="text" class="form-control" name="prefix" id="prefix" placeholder="პრეფიქსი" value="{{$io->prefix}}">
            </div>

            <div class="mb-3">
                <label for="identifier" class="form-label">იდენტიფიკატორი</label>
                <input type="text" class="form-control" name="identifier" id="identifier" placeholder="იდენტიფიკატორი" value="{{$io->identifier}}">
            </div>

            <div class="mb-3">
                <label for="suffix" class="form-label">სუფიქსი</label>
                <input type="text" class="form-control" name="suffix" id="suffix" placeholder="სუფიქსი" value="{{$io->suffix}}">
            </div>

            <div class="mb-3">
                <label for="type" class="form-label">ტიპი</label>
                <select class="form-control" name="io_type_id" id="type" onchange="getFields(event)">
                    @foreach($types as $type)
                        @if($io->type->table == $type->table)
                            <option selected value="{{$type->id}}">{{$type->name}}</option>
                        @else
                            <option value="{{$type->id}}">{{$type->name}}</option>
                        @endif
                    @endforeach
                </select>
            </div>


            <div class="mb-3">
                <button class="btn btn-success" >განახლება</button>
                <a class="btn btn-danger" href="{{route('io.index')}}">უკან</a>
            </div>

            </form>
        </div>
    </div>


@endsection