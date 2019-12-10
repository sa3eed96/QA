<div id="sidebar" class="col-sm-12 col-md-2">
    <a href="/question">Questions</a>
    @if (Auth::user())
        <a href="/user/{{Auth::id()}}/favourites">Favourites</a>
    @endif
    <a href="/tags">Tags</a>
    @if(Auth::user())
        <a href="/question/create">Ask a Question</a>
    @endif
</div>