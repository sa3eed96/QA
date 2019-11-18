@if ($model instanceof App\Question)
    @php
        $name = 'question';
        $UriSegmnet = 'questions';
    @endphp
@else if($model instanceof App\Answer)
    @php
        $name = 'answer';
        $UriSegmnet = 'answers';
    @endphp
@endif

<div class="d-felx flex-column vote-controls">
        <a  title="Useful" 
            class="vote-up {{Auth::guest()? 'off':''}}"
            onclick="event.preventDefault(); document.getElementById('up-vote-{{$name}}-{{$model->id}}').submit()">
            <i class="fas fa-caret-up fa-2x"></i>
        </a>
        <form action="/{{$UriSegment}}/{{$model->id}}/vote" id="up-vote-{{$name}}-{{$model->id}}" method="post" style="display:none;">
            @csrf
            <input type="hidden" name="vote" value="1" />
        </form>
        <span class="votes-count"> {{$model->votes_count}} </span>
        <a  title="not useful" 
            class="vote-up {{Auth::guest()? 'off':''}}"
            onclick="event.preventDefault(); document.getElementById('down-vote-{{$name}}-{{$model->id}}').submit()">
        <i class="fas fa-caret-down fa-2x"></i>
        </a>
        <form action="/{{$UriSegment}}/{{$model->id}}/vote" id="down-vote-{{$name}}-{{$model->id}}" method="post" style="display:none;">
            @csrf
            <input type="hidden" name="vote" value="-1" />
        </form>
    </div>