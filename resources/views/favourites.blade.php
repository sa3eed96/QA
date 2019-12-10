@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <h2> Favourite Questions </h2>
                    </div>
                </div>

                <div class="card-body">
                    @include('layouts._messages')
                    @forelse ($favourites as $question)
                        @include('questions._excerpt')
                    @empty
                        <div class="alert alert warning">
                            <strong>Sorry</strong> There is no questions available.
                        </div>
                    @endforelse

                    {{ $favourites->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection