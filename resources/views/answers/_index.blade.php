<div class="col-sm-12 col-md-10 offset-md-2 mt-4">
    <div class="card">
        <div class="card-body">
            <div class="card-title">
                <h2>{{ $answersCount." ".str_plural('Answer',$answersCount)}}</h2>
            </div>
            <hr>
            @include('layouts._messages')
            @foreach($answers as $answer)
                @include('answers._answer')
            @endforeach
        </div>
    </div>
</div>

