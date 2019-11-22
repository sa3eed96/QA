<div class="media post">
    <div class="d-flex felx-column counters">
        <div class="vote">
            <strong>{{$question->votes_count}}</strong>{{str_plural('vote', $question->votes_count)}}
        </div>
        <div class="status {{ $question->status}}">
            <strong>{{$question->answers_count}}</strong>{{str_plural('answer', $question->answers_count)}}
        </div>
        <div class="view">
            {{$question->views.' '.str_plural('view', $question->views)}}
        </div>
    </div>
    <div class="media-body">
        <div class="d-flex align-items-center">
            <h3 class="mt-0">
                <a href="{{$question->url}}"> {{ $question->title }} </a>
            </h3>
            <div class="ml-auto">
                @can('update', $question)
                    <a href="{{ route('question.edit', $question->id) }}" class="btn btn-sm btn-outline-info">Edit</a>
                @endcan
                @can ('delete', $question)
                    <form class="form-delete" action="{{route('question.destroy',$question->id)}}" method="post">
                        @method('DELETE')
                        @csrf
                        <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Are You Sure?')">Delete</button>
                    </form>
                @endcan
            </div>
        </div>
        <div class="d-flex">
            @foreach ($question->tags as $tag)
                <div class="ml-1 rounded p-1 bg-info">
                    {{$tag->tag}}
                </div>
            @endforeach
        </div>
        <p class="lead">
            By <a href="{{$question->user->url}}">{{ $question->user->name }}</a>
            <small class="text-muted"> {{$question->created_date}} </small>
        </p>
        {{ $question->excerpt }}
    </div>
</div>