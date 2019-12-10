@extends('layouts.app')

@section('content')
    <question :question="{{$question}}"></question>
    <answers :question="{{$question}}"></answers>
@endsection
