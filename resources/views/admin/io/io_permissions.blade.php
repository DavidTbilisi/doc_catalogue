@extends('layouts.admin')
@section('body')

    <div class="container">
        <x-error-alert></x-error-alert>
    </div>

    <h1 class="mt-4">{{$io->reference}} ({{$io->type->name}})</h1>




        @foreach($permissions as $index => $perms)

            <h2 class="mb-4 mt-4">{{$perms['group']->alias}} </h2>

          <form action="{{route("io_perms.update", ['group_id'=>$perms['group']->id, 'io_id'=>$io->id])}}" method="POST">
            @csrf()
        <div class="row">

        @foreach($perms["all"] as $permission)
            @if(in_array( $permission->const_name, $permissions[$index]['permitted'] ))

                <div class="col-3">
                    <div class="form-check form-switch">
                        <input class="form-check-input" name="{{$permission->power}}" type="checkbox" role="switch" id="{{$permission->const_name.$perms['group']->alias}}" checked>
                        <label class="form-check-label" for="{{$permission->const_name.$perms['group']->alias}}">{{$permission->name}}</label>
                    </div>
                </div>

            @else

                <div class="col-3">
                    <div class="form-check form-switch">
                        <input class="form-check-input" name="{{$permission->power}}" type="checkbox" role="switch" id="{{$permission->const_name.$perms['group']->alias}}">
                        <label class="form-check-label" for="{{$permission->const_name.$perms['group']->alias}}">{{$permission->name}}</label>
                    </div>
                </div>

            @endif


        @endforeach
            <div class="col-3">
                <input type="submit" value="შენახვა" class="btn btn-success">
            </div>
        </div>

      </form>



    @endforeach
@endsection
