@extends('layouts.admin')
@section('body')

    <div class="container">
        <x-error-alert></x-error-alert>
    </div>

    <h1 class="mt-4">{{$io->reference}} ({{$io->type->name}})</h1>
        @foreach($permissions as $index => $perms)
            @if ($perms['group']->name == "admin")
                @continue
            @endif
            <h2 class="mb-4 mt-4">{{$perms['group']->alias}} </h2>

          <form id="form_{{$perms['group']->alias}}" action="{{route("io_perms.update", ['group_id'=>$perms['group']->id, 'io_id'=>$io->id])}}" method="POST">
            @csrf()
        <div class="row">

            @foreach($perms["all"] as $permission)

                @if(in_array( $permission->const_name, $permissions[$index]['io_permitted'] ))
                    @if ( in_array($permission->const_name, $perms['group_perms']))
                        {{-- Group Allows To Change --}}
                        <x-io-permission :permission="$permission" :perms="$perms"></x-io-permission>
                    @else
                        {{-- Have No Perm From Groups --}}
                        <x-io-permission :permission="$permission" :perms="$perms" :disabled="true"></x-io-permission>
                    @endif

                @else
                        <x-io-permission :permission="$permission" :perms="$perms" :disabled="true"></x-io-permission>
                @endif
            @endforeach
            <div class="col-3">
                <input type="submit" value="შენახვა" class="btn btn-success">
            </div>
        </div>

      </form>



    @endforeach
@endsection
