@extends('layouts.app')

@section('content')
    <div class="col-sm-12 col-md-10 justify-content-center">
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <h2> Ask Questions </h2>
                    <div class="ml-auto">
                        <a class="btn btn-outline-secondary" href="{{route('question.index')}}">Back</a>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <new-question></new-question>
            </div>
        </div>
    </div>
@endsection
