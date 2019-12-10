@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Popular Tags</div>
                    <div class="card-body">
                        @forelse ($tags as $tag)
                            <a  href="/question?tags={{$tag}}" class="text-dark btn btn-outline-primary m-1 p-1">
                                {{ $tag->tag }}
                                <div class="font-weight-light d-inline">
                                    x{{$tag->count}}
                                </div>
                            </a>   
                        @empty
                            <div class="alert alert warning">
                                <strong>Sorry</strong> There is no Tags available.
                            </div>
                        @endforelse
                        {{ $tags->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
