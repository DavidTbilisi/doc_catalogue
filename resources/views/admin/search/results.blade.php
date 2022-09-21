@extends('layouts.admin')
@section('body')
    <div class="container pt-4">
        <x-error-alert></x-error-alert>
        <h1 class="mb-5">ძიების შედეგები</h1>

        @if(count($results) > 0)
        @foreach($results as $result)
            <div class="card mt-3" >
                <div class="card-body">
                    <h5 class="card-title"> {{$result->reference}} </h5>
                    <h6 class="card-subtitle mb-2 text-muted">საინფორმაციო ობიექტის ID: {{$result->io_id}}</h6>
                    <p class="card-text">
                        @foreach($fields as $field)
{{--                            {{$result->$field}}--}}
                        @endforeach
                    </p>
                    <a target="_blank" href="{{route("io.show",["id"=>$result->io_id])}}" class="card-link">გადასვლა</a>
                </div>
            </div>
        @endforeach
        @else
            <p>ძიება უშედეგოდ დასრულდა</p>
        @endif


        @if(count($results) > 10)
        <nav aria-label="Page navigation example" class="mt-3">
            <ul class="pagination">
                <li class="page-item"><a class="page-link" href="#">Previous</a></li>
                <li class="page-item"><a class="page-link" href="#">1</a></li>
                <li class="page-item"><a class="page-link" href="#">2</a></li>
                <li class="page-item"><a class="page-link" href="#">3</a></li>
                <li class="page-item"><a class="page-link" href="#">Next</a></li>
            </ul>
        </nav>
        @endif
    </div>
@endsection
