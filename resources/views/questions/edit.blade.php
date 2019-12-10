@extends('layouts.app')

@section('content')
    <div class="col-sm-12 col-md-10 justify-content-center">
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <h2> Edit Question </h2>
                    <div class="ml-auto">
                        <a class="btn btn-outline-secondary" href="{{route('question.index')}}">Back</a>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <form action="{{route('question.update', $question->id)}}" method="post">
                    @method('PUT')
                    @include("questions._form",['buttonText' => "Update"])
                </form>
            </div>
        </div>
    </div>
@endsection
