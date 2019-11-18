@extends('layouts.app')

@section('heads')
<script src="https://kit.fontawesome.com/d8e65388c0.js" crossorigin="anonymous"></script>
@endsection

@section('content')
<div class="container">
    <question :question="{{$question}}"></question>
    <answers :question="{{$question}}"></answers>
</div>
@endsection
