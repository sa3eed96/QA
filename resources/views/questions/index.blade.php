@extends('layouts.app')

@section('content')
    <div class="col-sm-12 col-md-10">
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <h2> Latest Questions </h2>
                    <div class="ml-auto">
                        @if(Auth::user())
                            <a class="btn btn-outline-secondary" href="{{route('question.create')}}">Add a Question</a>
                        @endif
                    </div>
                </div>
            </div>

            <div class="card-body">
                @include('layouts._messages')
                @forelse ($questions as $question)
                    @include('questions._excerpt')
                @empty
                    <div class="alert alert warning">
                        <strong>Sorry</strong> There is no questions available.
                    </div>
                @endforelse

                {{ $questions->links() }}
            </div>
        </div>
    </div>
@endsection
